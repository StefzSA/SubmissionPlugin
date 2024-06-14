<?
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

function sbm_sanitize(){
    // Sanitize data
    $title   = sanitize_text_field( $_POST['sbm_title'] );
    $content = sanitize_textarea_field( $_POST['sbm_content'] );
    $email   = sanitize_email( $_POST['sbm_email'] );
    
    return array(
        'title'     => $title,
        'content'   => $content,
        'email'     => $email
    );
}

function sbm_insert($sbm)
{
    $new_post = array(
        'post_title'    => $sbm['title'],
        'post_type'     => 'submissions', // Set post type to 'submissions'
        'post_status'   => 'publish',
    );

    // Attempt to insert the new post
    $post_id = wp_insert_post($new_post);
    
    // Check if post was inserted successfully
    if (is_wp_error($post_id)) {
        wp_die('Error saving submission: ' . $post_id->get_error_message(), 'Submission Error');
    }

    add_post_meta($post_id, 'sbm_email', $sbm['email']);
    add_post_meta($post_id, 'sbm_content', $sbm['content']);

    echo 'Submission successful!';
    return true;
}

function sbm_notif_settings_page(){
    require(SBM_NOTIF_DIR.'admin/sbm_settings_page.php');
}
