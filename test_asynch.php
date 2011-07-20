<?php
require('includes/application_top.php');

$action = isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '';
$target = isset($HTTP_GET_VARS['target']) ? $HTTP_GET_VARS['target'] : '';
$script = isset($HTTP_GET_VARS['script']) ? $HTTP_GET_VARS['script'] : '';

/**
 **************** Act as if CRON was starting this script *************
 */
 
if($action == 'execute_cron_job'){
  ob_start();

  //do tasks
  require ("start_tasks.php");

  ob_end_flush();
}

/**
 *************************** Insert the task into database ********
 ********************************* for later execution ************
 ***************************** and return confirmation message ****
 */

if ($action == 'importing_into_db' && strlen($_FILES['ufile']['tmp_name']) > 0){
  
  $manufacturer = $_POST['manufacturer'];
  $file_name = $_FILES['ufile']['name'];
  $tmp_file_name = $_FILES['ufile']['tmp_name'];  
//  move_uploaded_file($tmp_file_name, $file_name);
  $post_array = mysql_real_escape_string(serialize($_POST));
  $get_array = mysql_real_escape_string(serialize($_GET));
  
$sql = <<<EOQ
          INSERT INTO `tasklist` (
          `id` ,
          `url` ,
          `get` ,
          `post` ,
          `files`
          )
          VALUES (
          NULL , '$script', '$get_array', '$post_array', '$file_name'
          );  
EOQ;

$qres = mysql_query($sql);

$exit_message = urlencode("Your request was accepted successfully and it will be performed later");
header("Location: test_asynch.php?message=".$exit_message);

}

/**
************************ Draw forms ***********************************
*/

else{
  if(isset($_GET['message'])) echo htmlspecialchars($_GET['message'])."<br />";
  echo '
    <form action="?action=importing_into_db&target=products&script=my_import.php" method="post" enctype="multipart/form-data">
    <input type="text" name="manufacturer" />
    <input type="file" name="ufile" />  
    <input type="submit" name="submit1" value="Execute sample task"/>
    </form>
    
    <form action="?action=execute_cron_job" method="post" enctype="multipart/form-data">
    <input type="submit" name="submit2" value="Perform the task later like a CRON"/>
    </form>    
        ';
}      
?>
