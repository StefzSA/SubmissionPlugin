<?php
//Callback for the shortcode, prints the api script of recaptcha on detection
//meaning the two needed keys are set up on the settings page.
function sbm_notif_shortcode($atts)
{
  $atts = shortcode_atts(array(
    'title' => 'User Submission Form',
  ), $atts);

  $output = '';
  $data_recap = 'false';
  
  if(sbm_detect_recaptcha()){
    $data_recap = 'true';
    $output .= sbm_detect_recaptcha();
  }

  // Form output
  $output .= '<h3>' . esc_html($atts['title']) . '</h3>
            <form id="sbm_form" method="post" data-recap="'.$data_recap.'">
              <input type="text" id="sbm_title" name="sbm_title" placeholder="Write a descriptive subject here"><br><br>
              <textarea id="sbm_content" name="sbm_content" rows="5" placeholder="Tell us what you think!"></textarea><br><br>
              <input type="email" id="sbm_email" name="sbm_email" placeholder="Your goes email here."><br><br>';
  $output .= '<div class="sbm-form-controls">
                <input id="sbm_submit" type="submit" value="Submit">
                <div id="sbm_response" class="response"></div>
              </div>
            </form>
  ';
  return $output;
}
add_shortcode('sbm_notif_shortcode', 'sbm_notif_shortcode'); //register the shortcode

//Callback for the ajax call which gets data from the form
function sbm_notif_submit_form()
{
  // Check if form is submitted correctly
  if (!isset($_POST['sbm_title']) || !isset($_POST['sbm_content']) || !isset($_POST['sbm_email'])) {
    wp_send_json_error(["message" => 'Form sent incorrectly!']);
    wp_die();
  }

  // Nonce verification, nonce define at sbm-notif.php, line 71.
  if ( ! wp_verify_nonce( $_POST['nonce'], 'sbm_form_nonce' ) ) {
    wp_send_json_error(["message" => 'Submission failed, try again later!']);
    wp_die();
  }

  //Checks if recaptcha is enabled, meaning it has the two keys set up
  //then validates the recaptcha by checking the response json from the cURL on recaptcha_validation()
  if( sbm_detect_recaptcha() ){
    $recaptcha = recaptcha_validation();
    if (!$recaptcha['success']){
      wp_send_json_error(["message" => 'Submission failed, try again later!']);
      wp_die();
    }
  }

  //Use custom function to sanitize and return sanitized data.
  //Then check if the custom function for inserting the data returned false.
  $sbm_data = sbm_sanitize();
  if (!sbm_insert($sbm_data)){
    wp_send_json_error(["message" => 'Submission failed, try again later!']);
    wp_die();
  }

  //If not set the email from either the dedicated option or the basic admin email field.
  //then pass the arguments to custom function to send the email. Which are email and data.
  $adminEmail = (!get_option('sbm_notif_email') ? get_bloginfo('admin_email') : get_option('sbm_notif_email'));
  sbm_mail($adminEmail, array('sbm_title' => $sbm_data['title'], 'sbm_content' => $sbm_data['content'], 'sbm_email' => $sbm_data['email']));
  wp_send_json(["message" => 'Submission successful!', "success" => true]);
}
