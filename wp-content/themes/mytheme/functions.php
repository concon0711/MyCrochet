// Ajax action handler for adding a row
add_action('wp_ajax_add_row', 'add_row_callback');
add_action('wp_ajax_nopriv_add_row', 'add_row_callback');

function add_row_callback() {
    // Check nonce for security
    check_ajax_referer('add_row_nonce', 'nonce');

    // Example: Insert data into database
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_crochet_table';

    $user_id = get_current_user_id();
    $row_number = sanitize_text_field($_POST['row_number']);
    $stitch_type = sanitize_text_field($_POST['stitch_type']);
    $project_name = sanitize_text_field($_POST['project_name']);

    $wpdb->insert(
        $table_name,
        array(
            'user_id' => $user_id,
            'row_number' => $row_number,
            'stitch_type' => $stitch_type,
            'project_name' => $project_name,
        )
    );

    wp_die(); // This is required to terminate immediately and return a proper response
}

