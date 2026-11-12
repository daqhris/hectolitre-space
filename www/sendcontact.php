<?php
/**
 * Contact form handler for hectolitre.space (form: contact.html).
 *
 * - accepts POST only; field names: name, email, message (+ hidden "website" honeypot)
 * - validates on the server (the browser script is only a convenience)
 * - sends a plain-text UTF-8 mail from a fixed address of the site's own domain,
 *   with the visitor's address in Reply-To (sending "From: visitor" fails SPF/DMARC checks)
 */

// ---- Settings: change here only -------------------------------------------
define( 'RECIPIENT_EMAIL', 'info@hectolitre.be' );       // who receives the messages (the public mailbox)
define( 'SENDER_EMAIL',    'info@hectolitre.space' );    // technical sender only: must stay a mailbox of the hosting domain (.space) for the form to work; not shown to visitors
define( 'SENDER_NAME',     'Site HECTOLITRE' );
define( 'EMAIL_SUBJECT',   'Message du site hectolitre.space' );
define( 'MAX_NAME',        100 );
define( 'MAX_MESSAGE',     5000 );
define( 'MAX_LINKS',       3 );                          // more links than this is treated as spam
// -----------------------------------------------------------------------------

function finish_page( $ok ) {
  if ( $ok ) {
    header( 'Location: thanks.html', true, 303 );
    exit;
  }
  http_response_code( 400 );
  header( 'Content-Type: text/html; charset=UTF-8' );
  echo '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8">'
     . '<meta name="viewport" content="width=device-width, initial-scale=1.0">'
     . '<meta name="robots" content="noindex"><title>HECTOLITRE</title></head>'
     . '<body style="font-family:Arial,sans-serif;max-width:36em;margin:4em auto;padding:0 1em">'
     . '<h1>Votre message n’a pas pu être envoyé.</h1>'
     . '<p>Vérifiez que votre nom, une adresse e-mail valide et un message sont renseignés, puis réessayez.</p>'
     . '<h2>Your message could not be sent.</h2>'
     . '<p>Please check that your name, a valid email address and a message are filled in, then try again.</p>'
     . '<p><a href="contact.html">&larr; contact</a> &middot; <a href="mailto:' . RECIPIENT_EMAIL . '">' . RECIPIENT_EMAIL . '</a></p>'
     . '</body></html>';
  exit;
}

// Only a submitted form is accepted; anything else goes back to the form.
if ( ( $_SERVER['REQUEST_METHOD'] ?? '' ) !== 'POST' ) {
  header( 'Location: contact.html', true, 303 );
  exit;
}

// Honeypot: the field is hidden from people; bots fill it. Answer as if it worked.
if ( trim( (string) ( $_POST['website'] ?? '' ) ) !== '' ) {
  finish_page( true );
}

function clean_text( $value, $max, $multiline ) {
  $value = is_string( $value ) ? $value : '';
  $value = str_replace( "\0", '', $value );
  $value = $multiline
    ? preg_replace( '/[^\P{C}\n\r\t]/u', '', $value )   // drop control characters, keep line breaks
    : preg_replace( '/\p{C}+/u', ' ', $value );          // no line breaks at all in one-line fields
  if ( $value === null ) {                               // invalid UTF-8 input
    return '';
  }
  $value = trim( $value );
  return mb_substr( $value, 0, $max, 'UTF-8' );
}

$name    = clean_text( $_POST['name'] ?? '', MAX_NAME, false );
$email   = clean_text( $_POST['email'] ?? '', 254, false );
$message = clean_text( $_POST['message'] ?? '', MAX_MESSAGE, true );
$message = preg_replace( '/\r\n?/', "\n", $message );

if ( $name === '' || $message === '' || !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
  finish_page( false );
}

// Too many links: almost always spam. Show the normal thank-you page, send nothing.
if ( preg_match_all( '#https?://|www\.#i', $message ) > MAX_LINKS ) {
  finish_page( true );
}

$body = "Nom : " . $name . "\n"
      . "Email : " . $email . "\n\n"
      . $message . "\n";

$headers = array(
  'From: ' . SENDER_NAME . ' <' . SENDER_EMAIL . '>',
  'Reply-To: ' . $email,
  'MIME-Version: 1.0',
  'Content-Type: text/plain; charset=UTF-8',
  'Content-Transfer-Encoding: base64',
);

$sent = mail(
  RECIPIENT_EMAIL,
  EMAIL_SUBJECT,
  chunk_split( base64_encode( $body ) ),
  implode( "\r\n", $headers )
);

finish_page( $sent );
