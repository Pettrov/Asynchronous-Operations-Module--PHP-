<?php
if(!isset($_GET['id'])){
  //crawl the table with tasks
  $sql = <<<EOQ
            SELECT id, url FROM tasklist WHERE executed <> 1 LIMIT 4;  
EOQ;

  $query = mysql_query($sql);


  while ($data = tep_db_fetch_array($query)) {
    $taskid = (int)$data['id'];
    $url = $data['url'];    
    $cmd = sprintf("/usr/bin/wget -qO /dev/null --no-check-certificate '%s?id=%d'", tep_href_link('start_tasks.php'), $taskid);
    /*
    if (isset($_GET['force']))
      $cmd .= '&force=1';
    */  
    $cmd .= ' >/dev/null 2>&1 &';
    $p = exec($cmd);
    mysql_query("UPDATE `tasklist` SET `executed` = '1' WHERE `tasklist`.`id` =".$taskid.";");
    
  }
}
else{
require('includes/application_top.php');
  $taskid = (int)$_GET['id'];
  
  var_dump($taskid);
  $sql = <<<EOQ
            SELECT * FROM tasklist WHERE id=$taskid;  
EOQ;

  $query = mysql_query($sql);
  $task = array();
  $result = mysql_fetch_assoc($query);
  
  $post_array = unserialize($result['post']);
  $get_array = unserialize($result['get']); 
  $url = $result['url'];
  $file_name = $result['files'];
  
  $action = $get_array['action'];
  $target = $get_array['target'];  
  $function = $get_array['func'];
  
  
  include($url);
  $function();  
  // depending of the implementation of the functions you are calling:
  // $function($post_array['manufacturer'],$post_array['some_stuff'],$file_name)
  
  mysql_query("
              INSERT INTO `result_table` (
              `id` ,
              `value`
              )
              VALUES (
              NULL , '".$post_array['manufacturer']."'
              );  
  "); 
  
  // call concrete action with function name and parameters retrieved in post_array and get_array and files 
}  
?>  
