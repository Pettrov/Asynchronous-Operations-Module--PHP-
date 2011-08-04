<?php
interface Job{
	function execute($job_obj);
}

class HTTPJob implements Job{

  public $get, $post, $files;
  public $job_id, $file, $function, $arguments, $job_status;
  
  public function __construct() {
    //Constructor free of catching concrete data
  }


  public function add($args) {
    //TODO Remove POST GET and FILES retrieving and make it as Decorator
    $this->file = $args['file'];
    $this->func = $args['func'];
    $this->arguments = $args['arguments'];        
    
    //Provides catching of $_GET, $_POST and $_FILES
    $this->get = $_GET;
    $this->post = $_POST;
    $this->files = $_FILES;
    
    // TODO copy the files to a secure location (outside /tmp )
  }
  
  public function execute($job_obj) {
    $_GET = $job_obj->get;
    $_POST = $job_obj->post;
    $_FILES = $job_obj->files;
    
    $method_job = new MethodJob();
    return $method_job->execute($job_obj);
  }

}

class MethodJob implements Job{
	function execute($job_obj){
	//load a file and execute a method
	  return $job_obj; //for debugging return job_obj instead of true
	  include($job_obj->file);
	  $function_call = call_user_func($job_obj->func);
	  return $function_call;	
	}
}


class SimpleJob implements Job{
	function execute($job_obj){
	//for simple jobs
	}
}

class ClassJob implements Job{
	function execute($job_obj){
	//load a class and execute a method
	}
}
