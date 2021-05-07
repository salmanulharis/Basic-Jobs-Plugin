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

function child_enqueue_styles() {
  wp_enqueue_style( 'applicant_form-style', plugins_url( 'settingsmenu/css/styles.css' ), array(), '1.0.0', 'all');
  wp_enqueue_script( 'applicant_form-scripts', plugins_url( 'settingsmenu/js/form.js' ), array ('jquery'), '1.0.0', true);
  wp_localize_script( 'applicant_form-scripts', 'settings',array('ajaxurl' => admin_url( 'admin-ajax.php' )));
}
add_action( 'wp_enqueue_scripts', 'child_enqueue_styles');

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

// Our custom post type function
function create_posttype_applicants() {

    register_post_type( 'applicants', //important, id to show the post
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Applicats' ), //name of custom post type
                'singular_name' => __( 'Applicant' ) //singular name of custom post type
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'applicants'),
            'show_in_rest' => true,

        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype_applicants' );


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
    // if(!empty($_POST['applicant_name']) && !empty($_POST['applicant_email']) && !empty($_POST['applicant_exp'])){
    // }

    //getting the value of checkbox
    $emailValue = esc_attr( get_option( 'show_email' ));

    // checking if checkbox is checked or not?
    if($emailValue == "true"){
      // retrieve the global notice for the current post
      $global_notice = esc_attr(get_post_meta( $post->ID, '_company_email_key', true ) );
      $emailVal = "<div class='sp_global_notice'>$global_notice</div>";
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
      $status = '<div class="sp_global_notice"><button id="exp-button" disabled>Expired</button></div>';
    }
    else {
      $status = '<div class="sp_global_notice"><button id="apply-button">Apply now</button></div>';
    }
    //getting description value from settings
    $description = esc_attr(get_option('organisation_description'));
    $description = "<div class='sp_global_notice'>$description</div>";

    $application_form = job_applicant_form();

    $applicant_data = "<div id='data-container'>
                      <h4 id='application-status'></h4>
                      <div id='details-container'>
                      <div class='sp_global_notice' id='name-data'>Name: </div>
                      <div class='sp_global_notice' id='email-data'>Email: </div>
                      <div class='sp_global_notice' id='phone-data'>Phone: </div>
                      <div class='sp_global_notice' id='exp-data'>Experience: </div>
                      </div>
                      </div>";

    $slug = "application_jobs";
    if($slug != $post->post_type){
      return $content;
    }

    return $contents . $emailVal . $description . $status . $application_form . $applicant_data;
}
//filtering the content to show in site
add_filter('the_content', 'show_content_in_page');

//to only show the content in the custom post 'Applicants' and only its button, while opening post of "applicant" custom post type.
function show_content_in_page_applicants( $content ) {
    global $post;
    $status = '<div class="sp_global_notice"><button id="delete-button">Delete</button></div>';

    $slug = "applicants";
    if($slug != $post->post_type){
      return $content;
    }
    $id = $post->ID;
    $ids = "<div id='post_id'>$id</div>";

    return $content . $status . $ids;
}
add_filter('the_content', 'show_content_in_page_applicants');


function job_applicant_form() {
  ob_start(); ?>
    <form id="applicantsform" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post">
      <fieldset>
        <p>
          <label for="applicant_name">First name</label>
          <input type="text" id="applicant-name" name="applicant_name" value="">
        </p>
        <p>
          <label for="applicant_email">Email</label>
          <input type="email" id="applicant-email" name="applicant_email" value="">
        </p>
        <p>
          <label for="applicant_phone">Phone</label>
          <input type="text" id="applicant-phone" name="applicant_phone" value="">
        </p>
        <p>
          <label for="applicant_exp">Experience</label>
          <input type="number" id="applicant-exp" name="applicant_exp" value="">
        </p>
        <p>
          <input type="hidden" name="action" value="custom_action">
          <input type="submit" name="" id="app-submit" value="Submit">
        </p>
      </fieldset>
      <?php wp_nonce_field( 'cpt_nonce_action', 'cpt_nonce_field' ); ?>
    </form>
  <?php
  return ob_get_clean();
}


add_action( 'wp_ajax_custom_action', 'custom_action' );
add_action( 'wp_ajax_nopriv_custom_action', 'custom_action' );
function custom_action() {
  global $post;

  write_log('hi');
  // $content ='Name: ' . $_POST['applicant_name'] . '</br>' . 'Email: ' . $_POST['applicant_email'] . '</br>' .
  //           'Phone: ' . $_POST['applicant_phone'] . '</br>' . 'Experience: ' . $_POST['applicant_exp'];
  $name = $_POST['applicant_name'];
  $email = $_POST['applicant_email'];
  $phone = $_POST['applicant_phone'];
  $exp = $_POST['applicant_exp'];

  $content = "<div id='applicant_name'>$name</div>
              <div id='applicant_email'>$email</div>
              <div id='applicant_phone'>$phone</div>
              <div id='applicant_exp'>$exp</div>
              ";

  // if (isset( $_POST[‘cpt_nonce_field’] ) && wp_verify_nonce( $_POST['cpt_nonce_field'], 'cpt_nonce_action' ) ) {
    $post = array(
        'post_title'    => $_POST['applicant_name'],
        'post_content'  => $content,
        // 'post_category' => $_POST['applicants'],
        // 'tags_input'    => $_POST['post_tags'],
        'post_status'   => 'draft',   // Could be: publish
        'post_type' 	=> 'applicants' // Could be: `page` or your CPT
    );
    write_log($post);
    wp_insert_post($post);

  // }
    // Don't forget to exit at the end of processing
    // exit(json_encode($response));
}

add_action( 'wp_ajax_my_delete_post', 'my_delete_post' );
function my_delete_post(){

    $permission = check_ajax_referer( 'my_delete_post_nonce', 'nonce', false );
    if( $permission == false ) {
        echo 'error';
    }
    else {
        wp_delete_post( $_REQUEST['id'] );
        echo 'success';
    }

    die();

}

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
