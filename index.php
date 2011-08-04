<?php
/**

  A test page to validate working of asynchronous module

*/


// the actual library implementation
require 'lib/async.php';

function controller($action='show', $job_count=1, $job_request=null) {
  
  $director = Director::getInstance("config/config.php");
  $manager = $director->get_manager();
  
  try{  
    $job = $director->build('HTTPJob');
  }  
  catch (Exception $e) {
    echo $e->getMessage();
  }
  
  
  switch ($action) {


// show the test page
  case 'show':
  default:
    require 'view/index.php';
    break;


// add a job to the queue
  case 'add_job':
  
    // HTTPJob implements Job interface and provides catching of $_GET, $_POST and $_FILES
    $job->add(array(
      'file' => 'worker.php', // the file that the job should include
      'func' => 'do_work', // the function to execute
      'arguments' => 'dummy', // arbitrary data that will be provided to the function
      ));    
    $job_id = $manager->add_job($job);
    
    // return success
    if($job_id){
      require 'view/test_add_job_success.php';
    } else{
      echo 'Error: The job was not added to the queue';
    }        
    break;

  
// execute one item from the queue
  case 'execute':
    if($job_request)
      $status = $manager->async_execute($job_request);
    else 
      $status = $manager->execute_first_job();      
  
    // return success
    if($status)
      require 'view/test_execute_success.php';
    else
      echo 'No jobs for execution found.';  
    break;


// execute all items from the queue
  case 'execute_all':
    $status = $manager->async_execute_all();
  
    // return success
    if($status)
      require 'view/test_execute_success_all.php';
    else echo "No jobs for execution found."  ;
    break;


// execute one job on top of the queue
  case 'first_job':
    $status = $manager->execute_first_job();
    // return success
    if($status)
      require 'view/test_execute_success_firstjob.php';
    else
      echo 'No waiting jobs found!';  
    break;


// returns list of all jobs
  case 'list':
    $list = $manager->async_list_jobs();
  
    // display the list of all jobs
    require 'view/test_job_list.php';
    break;


// returns status of a job
  case 'status':
    if($job_request){  
      $status = $manager->async_status($job_request);
      require 'view/test_job_status.php';
    }
    else
      echo "You have to provide job ID in order to complete your request";    
    break;


// returns the result of a job
  case 'result':
    if($job_request){
      $result = $manager->get_result($job_request);
      require 'view/test_job_result.php';
    }
    else
      echo "You have to provide job ID in order to complete your request";
    break;
  }
}


// handle the specified action
controller($_GET['action'], $_GET['count'], $_GET['job_id']);
?>
