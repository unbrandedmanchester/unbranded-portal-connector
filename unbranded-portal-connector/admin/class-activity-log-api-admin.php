<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.unbrandedmanchester.com
 * @since      1.0.0
 *
 * @package    Activity_Log_API
 * @subpackage Activity_Log_API/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * define admin-specific hooks, and then executes the run method to run the hooks.
 *
 * @package    Activity_Log_API
 * @subpackage Activity_Log_API/admin
 * @author     Your Name <your.email@example.com>
 */
class UNBPC_Activity_Log_API_Admin
{

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $activity_log_api    The ID of this plugin.
   */
  private $activity_log_api;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param    string    $activity_log_api       The name of this plugin.
   * @param    string    $version    The version of this plugin.
   */
  public function __construct($activity_log_api, $version)
  {

    $this->activity_log_api = $activity_log_api;
    $this->version     = $version;
    //Activity Logs
    add_action('admin_menu', array($this, 'add_plugin_admin_menu'));
    add_action('admin_init', array($this, 'register_plugin_settings'));

    //Activated Plugin
    add_action('activated_plugin', array($this, 'on_plugin_activated'));
    add_action('deactivated_plugin', array($this, 'on_plugin_deactivated'));

    add_action('user_register', array($this, 'on_user_added'));
    add_action('delete_user', array($this, 'on_user_deleted'));
    //add_action('wp_login', array($this, 'on_user_logged_in'), 99, 2);
    //add_action('wp_login_failed', array($this, 'on_wp_login_failed'));
    add_action('set_user_role', array($this, 'on_set_user_role'), 10, 3);

    add_action('wp_insert_post', array($this, 'on_post_created'), 10, 1);
    add_action('save_post', array($this, 'on_post_updated'), 10, 3);
    add_action('delete_post', array($this, 'on_post_deleted'));
    add_action('wp_after_insert_post', array($this, 'on_post_slug_changed'), 10, 3);
    add_action('update_option_blogname', array($this, 'on_update_option_blogname'), 10, 2);
    add_action('update_option_blog_public', array($this, 'on_update_option_blog_public'), 10, 2);
    add_action('update_option_permalink_structure', array($this, 'on_permalink_structure_updated'));
  }

  /**
   * Register the stylesheets for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_styles()
  {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in UNPC_UNBPC_Activity_Log_Api_Loader as all of the hooks are defined
     * in that class.
     *
     * The run() function will then run the hook-loader's run()
     * function, which will then define all the hooks defined
     * in this class.
     */

    wp_enqueue_style($this->activity_log_api, plugin_dir_url(__FILE__) . 'css/activity-log-api-admin.css', array(), $this->version, 'all');
  }

  /**
   * Register the JavaScript for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts()
  {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in UNPC_UNBPC_Activity_Log_Api_Loader as all of the hooks are defined
     * in that class.
     *
     * The run() function will then run the hook-loader's run()
     * function, which will then define all the hooks defined
     * in this class.
     */

    wp_enqueue_script($this->activity_log_api, plugin_dir_url(__FILE__) . 'js/activity-log-api-admin.js', array('jquery'), $this->version, false);
  }

  /**
   * Add the admin menu page.
   *
   * @since    1.0.0
   */
  public function add_plugin_admin_menu()
  {

    add_menu_page(
      'Activity Log API Settings', // Page title
      'Activity Log API',             // Menu title
      'manage_options',         // Capability
      $this->activity_log_api,        // Menu slug
      array($this, 'display_plugin_admin_page'), // Callback function
      'dashicons-admin-generic'  // Icon (optional)
    );
  }

  /**
   * Display the admin page content.
   *
   * @since    1.0.0
   */
  public function display_plugin_admin_page()
  {
?>
    <div class="wrap">
      <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
      <form method="post" action="options.php">
        <?php
        settings_fields($this->activity_log_api . '_options_group'); // Group name
        do_settings_sections($this->activity_log_api);               // Page name
        submit_button();
        ?>
      </form>
    </div>
  <?php
  }

  /**
   * Register the plugin settings.
   *
   * @since    1.0.0
   */
  public function register_plugin_settings()
  {
    // Register a setting group
    register_setting(
      $this->activity_log_api . '_options_group', // A settings group name
      $this->activity_log_api . '_options',         // The name of an option to create in the database
      array($this, 'sanitize_options')       // Sanitize callback
    );

    // Add a new section in the settings page
    add_settings_section(
      $this->activity_log_api . '_settings_section', // An ID
      'Plugin Settings',                      // Section title
      array($this, 'display_settings_section'), // Callback for section description
      $this->activity_log_api                         // Page to add section to
    );

    // Register the setting field for the checkbox
    add_settings_field(
      'api_key',
      'API Key',
      array($this, 'display_api_key_field'),
      $this->activity_log_api,
      $this->activity_log_api . '_settings_section'
    );
    add_settings_field(
      'project_id',
      'Project ID',
      array($this, 'display_project_id_field'),
      $this->activity_log_api,
      $this->activity_log_api . '_settings_section'
    );
  }

  /**
   * Sanitize the options.
   *
   * @since    1.0.0
   * @param    array    $input      The input array of options.
   * @return   array    $new_input  The sanitized array of options.
   */
  public function sanitize_options($input)
  {
    $new_input = array();
    $new_input['api_key'] = isset($input['api_key']) ? $input['api_key'] : '';
    $new_input['project_id'] = isset($input['project_id']) ? sanitize_text_field($input['project_id']) : '';
    return $new_input;
  }

  /**
   * Display the section description.
   *
   * @since    1.0.0
   */
  public function display_settings_section()
  {
    echo '<p>Enable or disable plugin features.</p>';
  }

  /**
   * Display the API Key field.
   *
   * @since 1.0.0
   */
  public function display_api_key_field()
  {
    $options = get_option($this->activity_log_api . '_options');
    $api_key = isset($options['api_key']) ? esc_attr($options['api_key']) : '';
  ?>
    <input type="text" id="api_key" name="<?php echo esc_html($this->activity_log_api); ?>_options[api_key]" value="<?php echo esc_html($api_key); ?>" />
    <label for="api_key">Enter your API Key</label>
  <?php
  }

  /**
   * Display the Project ID field.
   *
   * @since 1.0.0
   */
  public function display_project_id_field()
  {
    $options = get_option($this->activity_log_api . '_options');
    $project_id = isset($options['project_id']) ? esc_attr($options['project_id']) : '';
  ?>
    <input type="text" id="project_id" name="<?php echo esc_html($this->activity_log_api); ?>_options[project_id]" value="<?php echo esc_html($project_id); ?>" />
    <label for="project_id">Enter your Project ID</label>
<?php
  }

  /**
   * Function to send data to a third-party service using wp_remote_post.
   * This function is called when a plugin is activated.
   *
   * @since 1.0.0
   *
   * @param string $plugin_name The name of the plugin that was activated.
   * @return void
   */
  public function send_activity_log_data($event_id, $event, $event_detail, $user=null)
  {
    $options = get_option($this->activity_log_api . '_options');
    $api_key   = $options['api_key'];
    $project_id  = $options['project_id'];
    if ($user == null){
      $user = wp_get_current_user();
    }
    // Check if both API Key and Project ID are set before proceeding.
    if (! empty($api_key) && ! empty($project_id)) {
      $webhook_url = 'https://www.unbrandedmanchester.com/portal/wp-json/unbranded-portal/v1/webhook/activity_log/';

      $args = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body'        => wp_json_encode(array(
          'key'           => $api_key,
          'id'            => $project_id,
          'event_id'      => $event_id,
          'username'      => $user->user_login,
          'user_email'    => $user->user_email,
          'url'           => WP_HOME,
          'event'         => $event,
          'event_detail'  => $event_detail
        )),
      );
      $response = wp_remote_post($webhook_url, $args);      
      //error_log( 'WP_Error during wp_remote_post: ' . json_encode($response));

    }
  }
  /**
   * Hook into the 'activated_plugin' action.
   * This function is called whenever a plugin is activated.
   *
   * @since 1.0.0
   *
   * @param string $plugin The path to the activated plugin's main file, relative to the WordPress plugins directory.
   */
  public function on_plugin_activated($plugin)
  {
    // Get the plugin's name from the path.
    $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);
    $plugin_name = isset($plugin_data['Name']) ? $plugin_data['Name'] : $plugin; // Fallback to path if name not found.

    $event_id = 101;
    $event = "Plugin Activated";
    $event_detail = $plugin_name . " was activated";

    $this->send_activity_log_data($event_id, $event, $event_detail);
  }
  public function on_plugin_deactivated($plugin)
  {
    // Get the plugin's name from the path.
    $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);
    $plugin_name = isset($plugin_data['Name']) ? $plugin_data['Name'] : $plugin; // Fallback to path if name not found.

    $event_id = 102;
    $event = "Plugin Deactivated";
    $event_detail = $plugin_name . " was deactivated";

    $this->send_activity_log_data($event_id, $event, $event_detail);
  }
  public function on_user_added($user_id)
  {
    $user_info = get_userdata($user_id);
    $username = $user_info->user_login;
    $email = $user_info->user_email;

    $event_id = 201; // You might want a different ID for user creation events
    $event = "User Added";
    $event_detail = "User '" . $username . "' with email '" . $email . "' was added.";

    $this->send_activity_log_data($event_id, $event, $event_detail);
  }
  
  public function on_user_deleted($user_id)
  {
    $user_info = get_userdata($user_id);

    // It's possible the user data might not exist anymore by this point,
    // especially if deletion is in progress. So, add a check.
    if ($user_info) {
      $username = $user_info->user_login;
      $email = $user_info->user_email;
      $event_detail = "User '" . $username . "' with email '" . $email . "' was deleted.";
    } else {
      $event_detail = "User with ID " . $user_id . " was deleted.";
    }

    $event_id = 202;
    $event = "User Deleted";

    $this->send_activity_log_data($event_id, $event, $event_detail);
  }
  /*
  public function on_user_logged_in($username, $user)
  {
    $user = get_user_by('login', $username);
    if($user){
      $email = $user->user_email;
    }

    $event_id = 321;
    $event = "User Logged In";
    $event_detail = "User '" . $username . "' with email '" . $email . "' logged in.";

    $this->send_activity_log_data($event_id, $event, $event_detail, $user);
    //error_log('wp_login hook fired for user ID: ' . $username . ' / ' . $email); // Add this line

  }
    */
/*
  public function on_wp_login_failed($username)
  {
    $event_id = 987; // You might want a specific ID for failed login events
    $event = "Login Failed";
    $event_detail = "Login failed for user '" . $username . "'.";

    $this->send_activity_log_data($event_id, $event, $event_detail);
  }
*/
  public function on_post_created($post_id)
  {
    $post = get_post($post_id);
    if ($post) {
      $post_title = $post->post_title;
      $post_type = $post->post_type;
      $author = get_userdata($post->post_author)->user_login;

      $event_id = 301; // You might want a specific ID for post creation
      $event = "Post Created";
      $event_detail = "New post '" . $post_title . "' (Type: " . $post_type . ") was created by '" . $author . "'.";
      if ($post_type != "flamingo_contact"){
        $this->send_activity_log_data($event_id, $event, $event_detail);
      }
      
    }
  }

  public function on_post_updated($post_id, $post_after, $post_before)
  {
    $post_title_after = $post_after->post_title;
    $post_type = $post_after->post_type;
    $author = get_userdata($post_after->post_author)->user_login;

    $event_id = 303;
    $event = "Post Updated";
    $event_detail = "Post '" . $post_before->post_title . "' (Type: " . $post_type . ") was updated to '" . $post_title_after . "' by '" . $author . "'. (Post ID: " . $post_id . ")";
    if ($post_type != "flamingo_contact"){  
      $this->send_activity_log_data($event_id, $event, $event_detail);
    }
  }

  public function on_post_deleted($post_id)
  {
    $post = get_post($post_id);
    if ($post) {
      $post_title = $post->post_title;
      $post_type = $post->post_type;
      $author = get_userdata($post->post_author)->user_login;

      $event_id = 302; // You might want a specific ID for post deletion
      $event = "Post Deleted";
      $event_detail = "Post '" . $post_title . "' (Type: " . $post_type . ") deleted by '" . $author . "' (Post ID: " . $post_id . ").";

      $this->send_activity_log_data($event_id, $event, $event_detail);
    } else {
      // Post might be permanently deleted and no longer accessible
      $event_id = 302;
      $event = "Post Deleted";
      $event_detail = "Post with ID " . $post_id . " was deleted.";
      $this->send_activity_log_data($event_id, $event, $event_detail);
    }
  }

  public function on_post_slug_changed($post_id, $post, $old_post_name)
  {
    $post_title = $post->post_title;
    $post_type = $post->post_type;
    $author = get_userdata($post->post_author)->user_login;
    $new_slug = $post->post_name;

    $event_id = 204; 
    $event = "Post Slug Changed";
    $event_detail = "Slug of post '" . $post_title . "' (Type: " . $post_type . ") changed from '" . $old_post_name . "' to '" . $new_slug . "' by '" . $author . "' (Post ID: " . $post_id . ").";

    $this->send_activity_log_data($event_id, $event, $event_detail);
  }

  public function on_set_user_role($user_id, $role, $old_roles)
  {
    $user_info = get_userdata($user_id);
    if ($user_info) {
      $username = $user_info->user_login;
      $old_roles_string = !empty($old_roles) ? implode(', ', $old_roles) : 'None';

      $event_id = 501; // You might want a specific ID for user role changes
      $event = "User Role Changed";
      $event_detail = "Role of user '" . $username . "' changed from '" . $old_roles_string . "' to '" . $role . "'. (User ID: " . $user_id . ")";

      $this->send_activity_log_data($event_id, $event, $event_detail);
    }
  }

  public function on_update_option_blogname($old_value, $new_value)
  {
    $event_id = 801; // You might want a specific ID for site title changes
    $event = "Site Title Changed";
    $event_detail = "Site title changed from '" . $old_value . "' to '" . $new_value . "'.";

    $this->send_activity_log_data($event_id, $event, $event_detail);
  }

  public function on_update_option_blog_public($old_value, $new_value)
  {
    if ($old_value !== $new_value) {
      $old_status = ('1' === $old_value) ? 'discouraged' : 'encouraged';
      $new_status = ('1' === $new_value) ? 'discouraged' : 'encouraged';

      $event_id = 802; // You might want a specific ID for search engine visibility changes
      $event = "Search Engine Visibility Changed";
      $event_detail = "Search engine visibility changed from '" . $old_status . "' to '" . $new_status . "'.";

      $this->send_activity_log_data($event_id, $event, $event_detail);
    }
  }

  public function on_permalink_structure_updated()
  {
    $event_id = 803; // You might want a specific ID for permalink updates
    $event = "Permalink Structure Updated";
    $event_detail = "The permalink structure was updated to '" . get_option('permalink_structure') . "'.";

    $this->send_activity_log_data($event_id, $event, $event_detail);
  }
}
