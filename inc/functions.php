<?
//Function to send the email notification
//receives email and an array with data,
//meant to be used with function sbm_sanitize()
function sbm_mail($to, $data)
{
    $subject = 'New User Submission from: ' . $data['sbm_email'];
    $message = 'A new user submission has been received!' . PHP_EOL;
    $message .= 'Title: ' . $data['sbm_title'] . PHP_EOL;
    $message .= 'Content: ' . $data['sbm_content'] . PHP_EOL;
    $message .= 'Email: ' . $data['sbm_email'] . PHP_EOL;

    $headers = array('Content-Type: text/plain; charset=UTF-8');
    wp_mail($to, $subject, $message, $headers);
}

//sanitizes post data and returns sanitized in an array
function sbm_sanitize()
{
    // Sanitize data
    $title   = sanitize_text_field($_POST['sbm_title']);
    $content = sanitize_textarea_field($_POST['sbm_content']);
    $email   = sanitize_email($_POST['sbm_email']);

    return array(
        'title'     => $title,
        'content'   => $content,
        'email'     => $email
    );
}
//Function to insert a new post on submissions post type
//sets title, type and status, checks if creates it and add the metadata to the post
//returns true at the end of it
function sbm_insert($sbm)
{
    $new_post = array(
        'post_title'    => $sbm['title'],
        'post_type'     => 'submissions', // Set post type to 'submissions'
        'post_status'   => 'pending',
    );

    // Attempt to insert the new post
    $post_id = wp_insert_post($new_post);

    // Check if post was inserted successfully
    if (is_wp_error($post_id)) {
        wp_send_json_error(["message" => 'Error saving submission: ' . $post_id->get_error_message(), 'Submission Error']);
        wp_die();
    }

    add_post_meta($post_id, 'sbm_email', $sbm['email']);
    add_post_meta($post_id, 'sbm_content', $sbm['content']);

    return true;
}
//Callback for the settings page
function sbm_notif_settings_page()
{
    require(SBM_NOTIF_DIR . 'admin/sbm_settings_page.php');
}

//Detects if recaptcha settings on the page have anything.
//returns a script to add the recaptcha api with the site key to the head
//used at the form shortcode function sbm_notif_shortcode()
function sbm_detect_recaptcha()
{
    if ( !empty(get_option('sbm_recaptcha_site_key')) and !empty(get_option('sbm_recaptcha_secret_key'))) {
        $output = '<script>';
        $output .= "const siteKey = '" . get_option('sbm_recaptcha_site_key') . "';";
        $output .= 'if (siteKey) { 
                        const script = document.createElement("script");
                        script.src = `https://www.google.com/recaptcha/api.js?render=${siteKey}`; 
                        document.head.appendChild(script); 
                    }';
        $output .= '</script>';
        return $output;
    }
    return;
}

//Uses cURL to validate the token from the form with the secret key from the settings page.
function recaptcha_validation()
{
    $token = $_POST['token'];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' =>  get_option('sbm_recaptcha_secret_key'), 'response' => $token)));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($response, true);
    return $response;
}
