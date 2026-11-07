$(document).ready(function ($) {

	// Simple email check: something@something.tld, no spaces
	function isValidEmailAddress(emailAddress) {
		return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailAddress);
	}

	// show an error message and bring it into view below the fixed header
	function showError(errorSelector, fieldSelector) {
		var box = $(errorSelector);
		box.fadeIn('slow');
		$(fieldSelector).focus();
		$('html, body').animate({ scrollTop: Math.max(0, box.offset().top - 180) }, 250);
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
			showError('#fname', '#name');
			return false;
		}

		var email = $.trim($("#email").val());
		if (email === "" || !isValidEmailAddress(email)) {
			showError('#fmail', '#email');
			return false;
		}

		var comments = $.trim($("#msg").val());
		if (comments === "") {
			showError('#fmsg', '#msg');
			return false;
		}
	});

	// once the form is really being sent, block a second click on the button
	$("#contactForm").on("submit", function () {
		var button = $("#submit");
		setTimeout(function () { button.prop("disabled", true); }, 0);
	});

});
