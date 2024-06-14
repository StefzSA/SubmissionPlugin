jQuery(document).ready(function ($) {
  $("#sbm_form").submit(function (e) {
    e.preventDefault(); // Prevent default form submission
    grecaptcha.ready(function () {
      grecaptcha.execute(siteKey, { action: "sbm_form_submission" }).then(function (token) {
        $("#sbm_form").prepend('<input type="hidden" id="recaptcha_token" name="token" value="' + token + '">');
        $("#sbm_form").prepend('<input type="hidden" name="action" value="sbm_form_submission">');

        // Clear any previous error messages or response
        $(".sbm-error-message").remove();
        $("#sbm_response").empty();
        $("#sbm_response").removeClass("r-success").removeClass("r-error");

        // Validate title
        if ($.trim($("#sbm_title").val()) === "") {
          $("#sbm_title").after(
            '<span class="sbm-error-message">Please enter a title for your submission.</span>'
          );
          return false;
        }

        // Validate content
        if ($.trim($("#sbm_content").val()) === "") {
          $("#sbm_content").after(
            '<span class="sbm-error-message">Please enter some content for your submission.</span>'
          );
          return false;
        }

        // Validate email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test($("#sbm_email").val())) {
          $("#sbm_email").after('<span class="sbm-error-message">Please enter a valid email address.</span>');
          return false;
        }
        
        //sets the data for ajax call
        const data = {
          sbm_title: $("#sbm_title").val(),
          sbm_content: $("#sbm_content").val(),
          sbm_email: $("#sbm_email").val(),
          nonce: ajax.nonce,
          action: ajax.action,
          token: $("#recaptcha_token").val(),
        };

        $.ajax({
          url: ajax.url,
          type: "POST",
          data: data,
          dataType: "json",
          success: function (response) {
            $(".sbm-error-message").remove();
            if (response.success) {
              $("#sbm_response").addClass("r-success").text(response.message);
              $("#sbm_form")[0].reset();
            } else {
              $("#sbm_response").addClass("r-error").text(response.message);
            }
          },
          error: function () {
            $("#sbm_response").addClass("r-error").text("An error has occurred!");
          },
        });
      });
    });
  });
});
