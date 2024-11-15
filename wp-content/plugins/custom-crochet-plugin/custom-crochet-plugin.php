<?php
/*
Plugin Name: Custom Crochet Plugin
Description: Plugin to handle crochet membership information.
Version: 1.0
*/

// Function to retrieve data from custom database table
function get_crochet_data() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_crochet_table';
    $results = $wpdb->get_results("SELECT * FROM $table_name");
    return $results;
}

// Shortcode to display crochet data on a page
function crochet_data_shortcode() {
    ob_start();
    $crochet_data = get_crochet_data();
    ?>
    <div id="crochet-data">
        <h2>Crochet Membership Information</h2>
        <table id="membership-table" class="display">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Row Number</th>
                    <th>Stitch Type</th>
                    <th>Project Name</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($crochet_data as $data) : ?>
                    <tr>
                        <td><?php echo $data->user_id; ?></td>
                        <td><?php echo $data->row_number; ?></td>
                        <td><?php echo $data->stitch_type; ?></td>
                        <td><?php echo $data->project_name; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('crochet_data', 'crochet_data_shortcode');

// Enqueue scripts and localize Ajax URL
function enqueue_custom_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('datatables', 'https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js', array('jquery'), '1.11.5', true);
    wp_enqueue_style('datatables-style', 'https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css');

    wp_enqueue_script('custom-ajax-script', plugin_dir_url(__FILE__) . 'js/custom-ajax-script.js', array('jquery'), '1.0', true);
    wp_localize_script('custom-ajax-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

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
?>