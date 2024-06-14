jQuery(document).ready(function($) {
    $('#sbm_form').submit(function(event) {
      // Clear any previous error messages
      $('.error-message').remove();
  
      // Validate title
      if ($.trim($('#title').val()) === '') {
        $('#title').after('<span class="error-message">Please enter a title for your submission.</span>');
        return false;
      }
  
      // Validate content
      if ($.trim($('#content').val()) === '') {
        $('#content').after('<span class="error-message">Please enter some content for your submission.</span>');
        return false;
      }
  
      // Validate email (basic check)
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test($('#email').val())) {
        $('#email').after('<span class="error-message">Please enter a valid email address.</span>');
        return false;
      }
  
      // All validations pass, submit the form
      return true;
    });
  });