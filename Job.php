<?php
interface Job{
	function execute();
}

class HTTPJob implements Job{

  public $get, $post, $files;
  public $job_id, $file, $function, $arguments, $job_status;
  
  public function __construct() {
    //Constructor free of catching concrete data
  }


  public function add($args) {
    $this->file = $args['file'];
    $this->func = $args['func'];
    $this->arguments = $args['arguments'];        
    
    //Provides catching of $_GET, $_POST and $_FILES
    $this->get = $_GET;
    $this->post = $_POST;
    $this->files = $_FILES;
    
    // TODO copy the files to a secure location (outside /tmp )
}
  
  public function execute($stuff) {
    
    $simple_job = new SimpleJob();
    return $simple_job->execute($stuff);
  }

}

class SimpleJob implements Job{
	function execute($job_obj){
	//for simple jobs
	  include($job_obj->file);
	  return call_user_func($job_obj->func);
	}
}

class MethodJob implements Job{
	function execute(){
	//load a file and execute a method
	}
}

class ClassJob implements Job{
	function execute(){
	//load a class and execute a method
	}
}
