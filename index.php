<?php
/**

  A test page to validate working of asynchronous module

*/

// the actual library implementation
require 'lib/async.php';

function controller($action='show') {
  
  switch ($action) {

  // show the test page
  case 'show':
  default:
    require 'view/index.php';
    break;

  // add a job to the queue
  case 'add_job':
  
    // HTTPJob implements Job interface and provides catching of $_GET, $_POST and $_FILES
    $job = new HTTPJob();
    $job->add(array(
      'file' => 'worker.php', // the file that the job should include
      'func' => 'do_work', // the function to execute
      'arguments' => 'dummy', // arbitrary data that will be provided to the function
      ));    
    $job_id = async_add_job($job);
    
    // return success
    if($job_id){
      require 'view/test_add_job_success.php';
    } else{
      echo 'Error: The job was not added to the queue';
    }        
    break;
  
  // execute one item from the queue
  case 'execute':
    $status = async_execute(1);
  
    // return success
    require 'view/test_execute_success.php';
    break;

  // execute all items from the queue
  case 'execute_all':
    $status = async_execute_all();
  
    // return success
    require 'view/test_execute_success.php';
    break;

// execute one job on top of the queue
  case 'pop_job':
  
    $job = new HTTPJob();
    $result = async_execute_pop($job);
    $status = $job->execute($result);
    // return success
    if($status)
      require 'view/test_execute_success.php';
    else
      echo 'Error: The job was not executed successfully';  
    break;

// returns list of all jobs
  case 'list':
    $list = async_list_jobs();
  
    // display the list of all jobs
    require 'view/test_job_list.php';
    break;

  // returns status of a job
  case 'status':
    $status = async_status($_GET['job']);
  
    // display the job status
    require 'view/test_job_status.php';
    break;

  // returns the result of a job
  case 'result':
    $result = async_status($_GET['job']);
  
    // display the result
    require 'view/test_job_result.php';
    break;
  }
}

// handle the specified action
controller($_GET['action']);
