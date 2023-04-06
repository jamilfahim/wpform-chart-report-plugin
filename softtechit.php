<?php
/**
 * Plugin Name:       Soft Tech IT
 * Plugin URI:        https://softtech-it.com/
 * Description:       This is a custom chart plugin Develop by softtechit.
 * Version:           1.0.0
 * Author:            jhfahim
 * Author URI:        https://jhfahim.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       softtechit
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Enqueue script
 */
function chart_enqueue_script()
{   
		
    wp_enqueue_style( 'chart-css', plugin_dir_url( __FILE__ ) . 'assets/css/style.css', array(), '1.0.0', 'all' );
    wp_enqueue_script( 'chart-js', plugin_dir_url( __FILE__ ) . 'assets/js/chart.js', array('jquery'), '1.0.0', true );
    wp_enqueue_script( 'chart-js-pdf', plugin_dir_url( __FILE__ ) . 'assets/js/chartpdf.min.js', array('jquery'), '1.0.0', true );
    wp_enqueue_script( 'chart-js-plugin', plugin_dir_url( __FILE__ ) . 'assets/js/chartjs-plugin.js',array('jquery'), '1.0.0', true );
    wp_enqueue_script( 'my-custom-script', plugin_dir_url( __FILE__ ) . 'assets/js/custom.js',array('jquery'), '1.0.0', true );
    wp_localize_script( 'my-custom-script', 'softvar', array(
       'home_url' => home_url()
    ));
	 
}
add_action('admin_enqueue_scripts', 'chart_enqueue_script');

/**
 * This template for shortcode
 */
require plugin_dir_path( __FILE__ ) . 'inc/shortcode/deshbord-chart.php';



//add chart in deshboard menu
global $wp_meta_boxes;

add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');
 
function my_custom_dashboard_widgets() {
wp_add_dashboard_widget('all_test_widget', 'My All Test', 'particular_test_dashboard_display');
wp_add_dashboard_widget('score_widget', 'My Test Score', 'chart_score_dashboard_display');
wp_add_dashboard_widget('trend_widget', 'My Test Score Trends', 'trends_score_dashboard_display');
wp_add_dashboard_widget('all_test_score_widget', 'Overall Statics', 'all_test_score_dashboard_display');
}
 
//All test taken by particular account 
function particular_test_dashboard_display() {

   echo do_shortcode( '[particular-test]' );

   global $wpdb, $ipt_fsqm_info;
   $submissions = $wpdb->get_results( "SELECT * FROM `wp_fsq_data` ORDER BY `wp_fsq_data`.`user_id` ASC", ARRAY_A );

  
}


//All test score by particular account 
function chart_score_dashboard_display() {

   echo do_shortcode( '[score-chart]' );

   

}


//trends of  number of test taker particular account
function trends_score_dashboard_display() {

   echo do_shortcode( '[trend-test-chart]' );


}

//All test taken by all account
function all_test_score_dashboard_display() {

   echo do_shortcode( '[all-test-chart]' );

   

}


add_action('admin_init', function(){

   setcookie('current_user', wp_get_current_user()->ID, time() + (86400 * 30));

});



add_action('rest_api_init', function(){

   register_rest_route('softtechit/v1', '/tests/', array( 
      'methods' => 'GET',
      'callback' => function(){

         global $wpdb, $ipt_fsqm_info;
         $forms = $wpdb->get_results( "SELECT id, name FROM {$ipt_fsqm_info['form_table']} ORDER BY id ASC", ARRAY_A );

         return $forms;

      }
   ));


   register_rest_route('softtechit/v1', '/submissions/', array( 
      'methods' => 'GET',
      'callback' => function(){

         global $wpdb, $ipt_fsqm_info;
         $submissions = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}fsq_data ORDER BY id ASC", ARRAY_A );

         return $submissions;

      }
   ));


   register_rest_route('softtechit/v1', '/submissions/user/(?P<id>\d+)', array( 
      'methods' => 'GET',
      'callback' => function(WP_REST_Request $request){


         global $wpdb, $ipt_fsqm_info;
         $submissions = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}fsq_data ORDER BY id ASC", ARRAY_A );

         $submission_length = count($submissions);
         $khali_submission = [];

         $percentages = [];
         $i = 0;
         foreach($submissions as $submission){
            

            if ($submission['user_id'] == $request['id']) {
               $khali_submission[] = $submission;
            }

            $count = 0;
            foreach($submissions as $secondsubmission){
               if($submission['form_id'] == $secondsubmission['form_id']){
                  ++$count;
               }
            }

            $percentages[$i]['form_id'] = $submission['form_id'];
            $percentages[$i]['percentage'] = $count;

            $i++;

         }

         $final_array = [];

         foreach($percentages as $percentage){
            $final_array[ $percentage['form_id'] ] = $percentage['percentage'];
         }

         $final = [];

         foreach($final_array as $single_array){
            $final[] = $single_array;
         }
         return $final;

      }
   ));

   register_rest_route('softtechit/v1', '/submissions/alluser', array( 
      'methods' => 'GET',
      'callback' => function(){
         $alluser = array();
         global $wpdb, $ipt_fsqm_info;
         $submissions = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}fsq_data ORDER BY id ASC", ARRAY_A );
         foreach($submissions as $submission){
            $alluser[] = $submission['user_id'];
         }
         $unique_users = array_unique( $alluser );

         $final_users = [];

         foreach($unique_users as $unique_user){
            $final_users[] = get_userdata($unique_user)->user_login;
         }
         return $final_users;

      }
   ));

   register_rest_route('softtechit/v1', '/submissions/alluser/data', array( 
      'methods' => 'GET',
      'callback' => function(){
         $alluser = array();
         global $wpdb, $ipt_fsqm_info;
         $submissions = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}fsq_data ORDER BY id ASC", ARRAY_A );
         foreach($submissions as $submission){
            $alluser[] = $submission['user_id'];
         }
         $unique_users = array_unique( $alluser );
         $count = 0;

         $users_test_count = [];

         foreach($unique_users as $unique_user){
            $z = 0;
            foreach($submissions as $submission){
               if($unique_user == $submission['user_id']){
                  ++$z;
               }
            }

            $users_test_count[] = $z;

         }

         
         return $users_test_count;

      }
   ));


   function rand_color() {
      return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
   }




   register_rest_route('softtechit/v1', '/submissions/user/test/(?P<id>\d+)', array( 
      'methods' => 'GET',
      'callback' => function(WP_REST_Request $request){

         global $wpdb, $ipt_fsqm_info;
         $submissions = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}fsq_data WHERE user_id = {$request['id']} ORDER BY id ASC", ARRAY_A );

         $forms = $wpdb->get_results( "SELECT id, name FROM {$ipt_fsqm_info['form_table']} ORDER BY id ASC", ARRAY_A );


         $user_data = [];

         

         foreach($submissions as $submission){
            $rand_color = rand_color();

            foreach($forms as $form){
               if($submission['form_id'] == $form['id']){

                  $scores_per_test = [];

                  foreach($submissions as $submission2){
                     if($submission2['form_id'] == $form['id']){
                        $scores_per_test[] = $submission2['score'];
                     }
                  }
                  $user_data[$form['id']] = [
                     "label" => $form['name'],
                     "data" => $scores_per_test,
                     "borderColor" => [$rand_color],
                     "backgroundColor" => [$rand_color],
                  ];
               }
            }
            
         }
         

         
         return array_values( $user_data );

      }
   ));









   

/*********************************************
 *    Get data From REST API between two dates
 *********************************************/

register_rest_route('softtechit/v1', '/submissions/by/date/(?P<from>[0-9-]+)/(?P<to>[0-9-]+)', array( 
   'methods' => 'GET',
   'callback' => function( WP_REST_Request $request ){

      global $wpdb, $ipt_fsqm_info;

         $from_date = $request->get_param('from');
         $to_date = $request->get_param('to');
        
         $submissions_by_date = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}fsq_data  WHERE `date`
         BETWEEN '$from_date' AND '$to_date' ORDER BY id ASC", ARRAY_A );
         return $submissions_by_date;

   }
));


// Get user particular test count
register_rest_route('softtechit/v1', '/submissions/user/by/date/(?P<id>\d+)/(?P<from>[0-9-]+)/(?P<to>[0-9-]+)', array( 
   'methods' => 'GET',
   'callback' => function(WP_REST_Request $request){
      
      $from_date = $request->get_param('from');
      $to_date = $request->get_param('to');
      $selected_id = $request->get_param('id');


      global $wpdb, $ipt_fsqm_info;
      $submissions = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}fsq_data  WHERE `date`
      BETWEEN '$from_date' AND '$to_date' ORDER BY id ASC", ARRAY_A );

      $submission_length = count($submissions);
      $khali_submission = [];

      $percentages = [];
      $i = 0;
      foreach($submissions as $submission){
         

         if ($submission['user_id'] == $selected_id) {
            $khali_submission[] = $submission;
         }

         $count = 0;
         foreach($khali_submission as $secondsubmission){
            if($submission['form_id'] == $secondsubmission['form_id']){
               ++$count;
            }
         }

         $percentages[$i]['form_id'] = $secondsubmission['form_id'];
         $percentages[$i]['percentage'] = $count;

         $i++;

      }

      $final_array = [];

      foreach($percentages as $percentage){
         $final_array[ $percentage['form_id'] ] = $percentage['percentage'];
      }

      $final = [];

      foreach($final_array as $single_array){
         $final[] = $single_array;
      }
      return $final;

   }
));


register_rest_route('softtechit/v1', '/submissions/alluser/by/date/(?P<from>[0-9-]+)/(?P<to>[0-9-]+)', array( 
   'methods' => 'GET',
   'callback' => function( WP_REST_Request $request ){

      global $wpdb, $ipt_fsqm_info;

         $from_date = $request->get_param('from');
         $to_date = $request->get_param('to');
         //$to_date= $_POST['to-date'];
         $alluser = array();
      $submissions = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}fsq_data WHERE `date`
      BETWEEN '$from_date' AND '$to_date' ORDER BY id ASC ", ARRAY_A  );
      foreach($submissions as $submission){
         $alluser[] = $submission['user_id'];
      }
      $unique_users = array_unique( $alluser );

      $final_users = [];

      foreach($unique_users as $unique_user){
         $final_users[] = get_userdata($unique_user)->user_login;
      }
      return $final_users;
   }
));


register_rest_route('softtechit/v1', '/submissions/alluser/data/by/date/(?P<from>[0-9-]+)/(?P<to>[0-9-]+)', array( 
   'methods' => 'GET',
   'callback' => function( WP_REST_Request $request ){

      global $wpdb, $ipt_fsqm_info;

         $from_date = $request->get_param('from');
         $to_date = $request->get_param('to');
         //$to_date= $_POST['to-date'];
         $alluser = array();
         global $wpdb, $ipt_fsqm_info;
         $submissions = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}fsq_data WHERE `date`
         BETWEEN '$from_date' AND '$to_date'  ORDER BY id ASC ", ARRAY_A  );
         foreach($submissions as $submission){
            $alluser[] = $submission['user_id'];
         }
         $unique_users = array_unique( $alluser );
         //$count = 0;
   
         $users_test_count = [];
   
         foreach($unique_users as $unique_user){
            $z = 0;
            foreach($submissions as $submission){
               if($unique_user == $submission['user_id']){
                  ++$z;
               }
            }
   
            $users_test_count[] = $z;
   
         }
   
         
         return $users_test_count;
   
   }
));

register_rest_route('softtechit/v1', '/submissions/user/test/by/date/(?P<id>\d+)/(?P<from>[0-9-]+)/(?P<to>[0-9-]+)', array( 
   'methods' => 'GET',
   'callback' => function(WP_REST_Request $request){

      global $wpdb, $ipt_fsqm_info;
      $from_date = $request->get_param('from');
      $to_date = $request->get_param('to');
      $submissions =  $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}fsq_data WHERE user_id = {$request['id']} AND  `date`
      BETWEEN '$from_date' AND '$to_date' ORDER BY id ASC", ARRAY_A );
      $forms = $wpdb->get_results( "SELECT id, name FROM {$ipt_fsqm_info['form_table']} ORDER BY id ASC", ARRAY_A );

      foreach($submissions as $submission){
         $all_user_id[] = $submission['user_id'];
      }
      $user_data = [];

      
     
         foreach($submissions as $submission){
            $rand_color = rand_color();
   
            foreach($forms as $form){
               if($submission['form_id'] == $form['id']){
   
                  $scores_per_test = [];
   
                  foreach($submissions as $submission2){
                     if($submission2['form_id'] == $form['id']){
                        $scores_per_test[] = $submission2['score'];
                     }
                  }
                  $user_data[$form['id']] = [
                     "label" => $form['name'],
                     "data" => $scores_per_test,
                     "borderColor" => [$rand_color],
                     "backgroundColor" => [$rand_color],
                  ];
               }
            }
            
         }
   
      

      
      return array_values( $user_data );

   }
));



});




















