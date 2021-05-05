<?php
/**
 * Plugin Name:       Custom Jobs Post
 * Description:       Custom job post
 * Version:           1.0.0
 * Author:            salman
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

 //adding javascripts and stylesheet from external files and external sources
function job_load_admin_scripts() {
  wp_enqueue_style( 'style', "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css");
  wp_enqueue_style( 'styles-colorpicker', "https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css");
  wp_enqueue_script( 'scripts-colorpicker', "https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js");
  wp_enqueue_script( 'script', "https://code.jquery.com/jquery-1.10.2.js");
  wp_enqueue_script( 'scripts', "https://code.jquery.com/ui/1.10.4/jquery-ui.js");

  wp_register_style('job_admin', plugins_url( 'settingsmenu/css/styles.css' ), array(), '1.0.0', 'all');
  wp_enqueue_style('job_admin');

  wp_enqueue_script('job-admin-scripts', plugins_url( 'settingsmenu/js/script.js' ), array ('jquery'), '1.0.0', true);
}
add_action('admin_enqueue_scripts', 'job_load_admin_scripts');

/////////////////////
//custome post part//
/////////////////////

// Our custom post type function
function create_posttype() {

    register_post_type( 'application_jobs', //important, id to show the post
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Jobs' ), //name of custom post type
                'singular_name' => __( 'Job' ) //singular name of custom post type
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'jobs'),
            'show_in_rest' => true,

        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype' );


/////////////////////////
//settings submenu part//
/////////////////////////

//function to custom Submenu
 function job_custom_submenu() {
   add_submenu_page("edit.php?post_type=application_jobs", "Settings", "Settings", "manage_options", "settings-post", "post_settings_page_function");

 }
 add_action('admin_menu', 'job_custom_submenu');

// Callback function to include items from settings API
 function post_settings_page_function(){
   settings_errors();
   ?>
   <form method="post" action="options.php">
     <h1>Jobs Settings</h1>
     <?php
     settings_fields('job-settings-group');
     do_settings_sections('settings-post');
     submit_button();
     ?>
   </form>

   <?php
 }

//addings fiels to the custom submenu page
add_action('admin_init', 'sunset_custom_settings');
function sunset_custom_settings(){
  register_setting('job-settings-group', 'organisation_name');
  register_setting('job-settings-group', 'show_email');
  register_setting('job-settings-group', 'show_title_content');
  register_setting('job-settings-group', 'number_of_jobs');
  register_setting('job-settings-group', 'organisation_description');
  register_setting('job-settings-group', 'filter_date_picker');
  register_setting('job-settings-group', 'job_color_picker');

  add_settings_section('job-sidebar-options', 'Advanced Jobs Settings', 'job_sidebar_options', 'settings-post');

  add_settings_field('sidebar-name', 'Organization', 'job_sidebar_name', 'settings-post', 'job-sidebar-options');
  add_settings_field('show-email', 'Show email', 'job_sidebar_show_email', 'settings-post', 'job-sidebar-options');
  add_settings_field('show-title-content', 'Post Contains', 'job_sidebar_show_title_content', 'settings-post', 'job-sidebar-options');
  add_settings_field('number-of-jobs', 'Post Contains', 'job_sidebar_number_of_jobs', 'settings-post', 'job-sidebar-options');
  add_settings_field('organisation-description', 'Description', 'job_sidebar_description', 'settings-post', 'job-sidebar-options');
  add_settings_field('filter-date-picker', 'Valid Job Date', 'job_sidebar_date_picker', 'settings-post', 'job-sidebar-options');
  add_settings_field('job-color-picker', 'Pick the Color', 'job_sidebar_color_picker', 'settings-post', 'job-sidebar-options');
}

//functions for each field in the settings page of custom post
function job_sidebar_options(){
  echo "Customize your jobs informations";
}

function job_sidebar_name() {
  $organisationName = esc_attr( get_option( 'organisation_name' ) );
  echo '<input type="text" name="organisation_name" value="'. $organisationName .'" placeholder="Organization Name" />
  </p class="description">Enter the name of Organisation.</p>';
}

function job_sidebar_show_email() {
  $emailValue = esc_attr( get_option( 'show_email' ) );
  if($emailValue == ""){
    echo '<input type="checkbox" id="show_email" name="show_email" value="true" /><label> check this box if you need to show email</label>';
  }
  else if($emailValue == "true") {
    echo '<input type="checkbox" id="show_email" name="show_email" value="true" checked="checked" /><label> check this box if you need to show email</label>';
  }
}

function job_sidebar_show_title_content() {
  $showValue = esc_attr( get_option( 'show_title_content' ) );
  if($showValue == "show_title"){
    echo '<input type="radio" id="show_title" name="show_title_content" value="show_title" checked="checked">
          <label for="show_title">title only</label><br>
          <input type="radio" id="show_content" name="show_title_content" value="show_content">
          <label for="show_content">title and content</label><br>';
  }
  else if($showValue == "show_content") {
    echo '<input type="radio" id="show_title" name="show_title_content" value="show_title">
          <label for="show_title">title only</label><br>
          <input type="radio" id="show_content" name="show_title_content" value="show_content" checked="checked">
          <label for="show_content">title and content</label><br>';
  }

}

function job_sidebar_number_of_jobs() {
  $jobNumber = esc_attr( get_option( 'number_of_jobs' ) );
  echo '<input type="number" name="number_of_jobs" value="'. $jobNumber .'" placeholder="Number of Jobs" />
  </p class="description">Enter the number of jobs available.</p>';
}

function job_sidebar_description() {
  $orgDescp = esc_attr( get_option( 'organisation_description' ) );?>
  <textarea name="organisation_description" rows="4" cols="50"><?php echo $orgDescp; ?></textarea>
  <?php
}

function job_sidebar_date_picker() {
  $validationDate = esc_attr( get_option( 'filter_date_picker' ) );
  echo '<input type="" id="job-date-picker" name="filter_date_picker" value="'. $validationDate .'" />
        </p class="description">Job have validity before this date, the jobs are expired.</p>';
}

function job_sidebar_color_picker() {
  $color = esc_attr( get_option( 'job_color_picker' ) );
  // echo '<input type="text" id="target" name="job_color_picker" value="'. $color .'" placeholder="Pick the color" />
  //       </p class="description">Pick your favourite color.</p>';
  echo '<div class="color-picker" name="job_color_picker"></div>
        <input type="hidden" id="job_color_picker" name="job_color_picker" value="'. $color .'" ?>
        </p class="description">Pick your favourite color.</p>';
}




/////////////////////////////////
//company details meta box part//
/////////////////////////////////

//adding custom metabox
function company_details_add_meta_box(){
  add_meta_box('user_text', 'Company Details', 'company_details_callback_function', 'application_jobs', 'side');
}

//callback function to add custom metabox
function company_details_callback_function( $post ){

   wp_nonce_field('save_company_data_function', 'company_data_meta_box_nonce');
   //variables to maintane the value of fields in metabox while refreshing
   $name_value = get_post_meta($post->ID, '_company_name_key', true);
   $email_value = get_post_meta($post->ID, '_company_email_key', true);
   $job_expiry_value = get_post_meta($post->ID, '_job_expiry_key', true);
?>
        <div>
           <label for="company_name_field">Company Name</label>
           <input type="text" id="company_name_field" name="company_name_field" value="<?php echo esc_attr( $name_value ); ?>" size="25" />
           <label for="company_email_field">Company Email</label>
           <input type="email" id="company_email_field" name="company_email_field" value="<?php echo esc_attr( $email_value ); ?>" size="25" oninput="ValidateEmail()"/>
           <p>Enter Date: <input type = "text" id = "expiry_datepicker" name="expiry_datepicker" value="<?php echo esc_attr( $job_expiry_value ); ?>"></p>

         </div>
         <?php
}
add_action('add_meta_boxes', 'company_details_add_meta_box');

//filtering and saving data from post
function save_company_data_function( $post_id ){

  if( ! isset($_POST['company_data_meta_box_nonce'])){
    return;
  }
  if( ! wp_verify_nonce($_POST['company_data_meta_box_nonce'], 'save_company_data_function')){
    return;
  }
  if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
    return;
  }
  if( ! current_user_can('edit_post', $post_id)){
    return;
  }
  if(! isset($_POST['company_name_field'])){
    return;
  }
  if(! isset($_POST['company_email_field'])){
    return;
  }

  $company_name_data = sanitize_text_field($_POST['company_name_field']);
  update_post_meta($post_id, '_company_name_key', $company_name_data);
  $company_email_data = sanitize_text_field($_POST['company_email_field']);
  update_post_meta($post_id, '_company_email_key', $company_email_data);
  $job_expiry_data = sanitize_text_field($_POST['expiry_datepicker']);
  update_post_meta($post_id, '_job_expiry_key', $job_expiry_data);
}
add_action('save_post', 'save_company_data_function');

//function to display the saved post in accordance with the value of checkbox
function show_content_in_page( $content ) {
    global $post;

    $notice = "";
    //getting the value of checkbox
    $emailValue = esc_attr( get_option( 'show_email' ));

    // checking if checkbox is checked or not?
    if($emailValue == "true"){
      // retrieve the global notice for the current post
      $global_notice = esc_attr(get_post_meta( $post->ID, '_company_email_key', true ) );
      $notice = "<div class='sp_global_notice'>$global_notice</div>";
    }

    //getting the value of radio button
    $showValue = esc_attr( get_option( 'show_title_content' ) );
    //checking the radio button
    if($showValue == "show_content"){
      $contents = "<div class='sp_global_notice'>$content</div>";
    }

    //getting the value of dates
    $post_expiry_date = esc_attr(get_post_meta( $post->ID, '_job_expiry_key', true ) );
    $current_date = esc_attr( get_option( 'filter_date_picker' ));
    // checking weather the expiry date is less than current date
    if($post_expiry_date < $current_date){
      $status = "<div class='sp_global_notice'>Expired</div>";
    }
    else {
      $status = "<div class='sp_global_notice'>Apply now</div>";
    }
    //getting description value from settings
    $description = esc_attr(get_option('organisation_description'));
    $description = "<div class='sp_global_notice'>$description</div>";

    return $contents . $notice . $status . $description;
}
//filtering the content to show in site
add_filter('the_content', 'show_content_in_page');




//function to show the errors
if (!function_exists('write_log')) {
function write_log ( $log ) {
if ( true === WP_DEBUG ) {
if ( is_array( $log ) || is_object( $log ) ) {
error_log( print_r( $log, true ) );
} else {
error_log( $log );
}
}
}
}
