<?php
$meta_boxes = array(
    array(
        'id' => 'sbm_details_meta_box',
        'title' => __('Submission Details', 'sbm-notif'),
        'page' => 'submissions',
        'context' => 'normal',
        'priority' => 'high',
        'fields' => array(
            array(
                'name' => 'sbm_content',
                'type' => 'textarea', // Non-WYSIWYG content
                'label' => __('Submission Content', 'sbm-notif'),
                'required' => true,
            ),
            array(
                'name' => 'sbm_email',
                'type' => 'email', // Email address
                'label' => __('User Email', 'sbm-notif'),
                'required' => true,
            ),
        ),
    ),
);

// Function to add metaboxes to the edit/add screen
function add_sbm_meta_boxes()
{
    global $meta_boxes;
    if ( empty($meta_boxes) ) wp_die();
    foreach ($meta_boxes as $meta_box) {
        add_meta_box($meta_box['id'], $meta_box['title'], 'display_sbm_meta_box', $meta_box['page'], $meta_box['context'], $meta_box['priority']);
    }
}

//callback function to display metaboxes by echoing html
function display_sbm_meta_box($post){
    global $meta_boxes;
    $meta_data = get_post_meta($post->ID);
    foreach ($meta_boxes as $meta_box) {
        foreach ($meta_box['fields'] as $field) {
            $meta_value = (isset($meta_data[$field['name']]) ? $meta_data[$field['name']][0] : '');
            // Use appropriate HTML elements based on the field type
            switch ($field['type']) {
                case 'textarea':
                    echo '<div id="' . esc_attr($field['name']) . '">';
                    echo '<h2>Content:</h2>';
                    echo '<p>' . esc_textarea($meta_value) . '</p>';
                    echo '</div>';
                break;

                case 'email':
                    echo '<div id="' . esc_attr($field['name']) . '">';
                    echo '<h2>Email:</h2>';
                    echo '<p>' . esc_textarea($meta_value) . '</p>';
                    echo '</div>';
                break;
            }
        }
    }
}

add_action('admin_menu', 'add_sbm_meta_boxes');
?>