<?php
function sbm_notif_shortcode($atts)
{
  $atts = shortcode_atts(array(
    'title' => 'User Submission Form',
  ), $atts);

  // Form output
  $output = '<h3>' . esc_html($atts['title']) . '</h3>
      <form id="sbm_form" method="post" action="' . esc_url(admin_url('admin-post.php?action=sbm_notif_submit_form')) . '">
        <input type="text" id="sbm_title" name="sbm_title" placeholder="Write a descriptive subject here"><br><br>
        <textarea id="sbm_content" name="sbm_content" rows="5" placeholder="Tell us what you think!"></textarea><br><br>
        <input type="email" id="sbm_email" name="sbm_email" placeholder="Your goes email here."><br><br>';
  $output .= '<div class="sbm-form-controls">
  <input id="sbm_submit" type="submit" value="Submit">
  <div id="sbm_response" class="response"></div></div>
  </form>
  ';
  return $output;
}
add_shortcode('sbm_notif_shortcode', 'sbm_notif_shortcode');

function sbm_notif_submit_form()
{
  // Check if form is submitted
  if (!isset($_POST['sbm_title']) || !isset($_POST['sbm_content']) || !isset($_POST['sbm_email'])) {
    wp_send_json_error(["message" => 'Form sent incorrectly!']);
    wp_die();
  }

  if ( ! wp_verify_nonce( $_POST['nonce'], 'sbm_form_nonce' ) ) {
    wp_send_json_error(["message" => 'Submission failed, try again later!']);
    wp_die();
  }
  
  $sbm_data = sbm_sanitize();
  if (!sbm_insert($sbm_data)){
    wp_send_json_error(["message" => 'Submission failed, try again later!']);
    wp_die();
  }

  $adminEmail = (!get_option('sbm_notif_email') ? get_bloginfo('admin_email') : get_option('sbm_notif_email'));
  sbm_mail($adminEmail, array('sbm_title' => $sbm_data['title'], 'sbm_content' => $sbm_data['content'], 'sbm_email' => $sbm_data['email']));
  wp_send_json(["message" => 'Submission successful!', "success" => true]);
}

add_action('admin_post_sbm_notif_submit_form', 'sbm_notif_submit_form');
