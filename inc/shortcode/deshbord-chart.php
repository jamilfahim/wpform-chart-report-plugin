<?php
/*********************************************
 *        This template for shortcode
 *********************************************/


 /**
 * Overall Test taken by a particular account 
 *
 */


function particular_test(){
        global $wpdb, $ipt_fsqm_info;
        $alluser =[];
        $submissions = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}fsq_data ORDER BY id ASC ", ARRAY_A  );
        foreach($submissions as $submission){
          $alluser[] = $submission['user_id'];
        
        }
       
        $unique_users = array_unique( $alluser );
      
  
      ob_start();
          ?>
          <form method="get" action="">
            <label for="from-date">From</label>
            <input type="date" class="from-date" name="from-date" value="<?php echo $_GET['from-date']?>" >
            <label for="to-date">to</label>
            <input type="date" class="to-date" name="to-date" value="<?php echo $_GET['to-date']?>">
            <select name="select_user" class="select-user">
              <option value="">Select User</option>
              <?php
            foreach($unique_users as $unique_user){
            $users_name =get_userdata($unique_user)->user_login;
       
        ?>
          <option  <?php if($_GET['select_user'] == $unique_user) echo"selected"; ?> value="<?php echo $unique_user; ?>"> <?php echo $users_name; ?></option>
        <?php
      }
      ?>
            
            
            </select>
            <button type="submit" id="filter-report" name="submit" value="filter"> Filter </button>
          </form>
          
          <button class="export-chart"  id="allChartbtn"> Export All </button> 
          <canvas id="testChart"></canvas>  
          <button class="export-chart"  id="testChartbtn"> Export </button>     
          <?php         
return ob_get_clean();
 
}
add_shortcode('particular-test', 'particular_test');


/**
 * Test score taken by a particular account 
 *
 */
function score_chart(){
  global $wpdb, $ipt_fsqm_info;
  $alluser =[];
  $submissions = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}fsq_data ORDER BY id ASC ", ARRAY_A  );
  foreach($submissions as $submission){
    $alluser[] = $submission['user_id'];
  
  }
 
  $unique_users = array_unique( $alluser );


ob_start();
    ?>
    <form method="get" action="">
      <label for="from-date">From</label>
      <input type="date" class="from-date" name="from-date" value="<?php echo $_GET['from-date']?>" >
      <label for="to-date">to</label>
      <input type="date" class="to-date" name="to-date" value="<?php echo $_GET['to-date']?>">
      <select name="select_user" class="select-user">
        <option value="">Select User</option>
        <?php
      foreach($unique_users as $unique_user){
      $users_name =get_userdata($unique_user)->user_login;
 
  ?>
    <option  <?php if($_GET['select_user'] == $unique_user) echo"selected"; ?> value="<?php echo $unique_user; ?>"> <?php echo $users_name; ?></option>
  <?php
}
?>
      
      
      </select>
      <button type="submit" id="filter-report" class="filter-report" name="submit" value="test-score-filter"> Filter </button>
    </form>
          <canvas id="scoreChart"></canvas>
          <button class="export-chart"  id="scoreChartBtn"> Export </button>     
        <?php         
return ob_get_clean();

}
add_shortcode('score-chart', 'score_chart');


/**
 * Trend test chart 
 *
 */
function trend_test_chart(){
  global $wpdb, $ipt_fsqm_info;
  $alluser =[];
  $submissions = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}fsq_data ORDER BY id ASC ", ARRAY_A  );
  foreach($submissions as $submission){
    $alluser[] = $submission['user_id'];
  
  }
 
  $unique_users = array_unique( $alluser );


ob_start();
    ?>
    <form method="get" action="">
      <label for="from-date">From</label>
      <input type="date" class="from-date" name="from-date" value="<?php echo $_GET['from-date']?>" >
      <label for="to-date">to</label>
      <input type="date" class="to-date" name="to-date" value="<?php echo $_GET['to-date']?>">
      <select name="select_user" class="select-user">
        <option value="">Select User</option>
        <?php
      foreach($unique_users as $unique_user){
      $users_name =get_userdata($unique_user)->user_login;
 
  ?>
    <option  <?php if($_GET['select_user'] == $unique_user) echo"selected"; ?> value="<?php echo $unique_user; ?>"> <?php echo $users_name; ?></option>
  <?php
}
?>
      
      
      </select>
      <button type="submit" id="filter-report" name="submit" value="filter"> Filter </button>
    </form>
         <canvas id="trendTestChart"></canvas>
         <button class="export-chart"  id="trendTestChartBtn"> Export </button>  
        <?php         
return ob_get_clean();

}
add_shortcode('trend-test-chart', 'trend_test_chart');

/**
 * All test chart from all account
 *
 */
function all_test_chart(){
  global $wpdb, $ipt_fsqm_info;
  $alluser =[];
  $submissions = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}fsq_data ORDER BY id ASC ", ARRAY_A  );
  foreach($submissions as $submission){
    $alluser[] = $submission['user_id'];
  
  }
 
  $unique_users = array_unique( $alluser );


ob_start();
    ?>
    <form method="get" action="">
      <label for="from-date">From</label>
      <input type="date" class="from-date" name="from-date" value="<?php echo $_GET['from-date']?>" >
      <label for="to-date">to</label>
      <input type="date" class="to-date" name="to-date" value="<?php echo $_GET['to-date']?>">
      <select name="select_user" class="select-user">
        <option value="">Select User</option>
        <?php
      foreach($unique_users as $unique_user){
      $users_name =get_userdata($unique_user)->user_login;
 
  ?>
    <option  <?php if($_GET['select_user'] == $unique_user) echo"selected"; ?> value="<?php echo $unique_user; ?>"> <?php echo $users_name; ?></option>
  <?php
}
?>
      
      
      </select>
      <button type="submit" id="filter-report" name="submit" value="filter"> Filter </button>
    </form>
         <canvas id="allTestChart"></canvas>
         <button class="export-chart"  id="allTestChartBtn"> Export </button>          
        <?php 
              
return ob_get_clean();

}
add_shortcode('all-test-chart', 'all_test_chart');

