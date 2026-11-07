$(document).ready(function ($) {

	// Simple email check: something@something.tld, no spaces
	function isValidEmailAddress(emailAddress) {
		return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailAddress);
	}

	// hide messages
	$(".error").hide();
	$(".success").hide();

	$('#contactForm input, #contactForm textarea').click(function () {
		$(".error").fadeOut();
	});

	// on submit: stop the post and show the matching message when a field is invalid
	$("#contactForm #submit").click(function () {
		$(".error").hide();

		var name = $.trim($("#name").val());
		if (name === "") {
			$('#fname').fadeIn('slow');
			$("#name").focus();
			return false;
		}

		var email = $.trim($("#email").val());
		if (email === "" || !isValidEmailAddress(email)) {
			$('#fmail').fadeIn('slow');
			$("#email").focus();
			return false;
		}

		var comments = $.trim($("#msg").val());
		if (comments === "") {
			$('#fmsg').fadeIn('slow');
			$("#msg").focus();
			return false;
		}
	});

});
