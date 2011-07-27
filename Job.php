<?php
interface Job{
	function execute();
}

class HTTPJob implements Job{

  public $get, $post, $files;
  public $file, $function, $arguments;
  
  public function __construct($args) {
    $this->file = $args['file'];
    $this->func = $args['func'];
    $this->arguments = $args['arguments'];        
    
    //Provides catching of $_GET, $_POST and $_FILES
    $this->get = $_GET;
    $this->post = $_POST;
    $this->files = $_FILES;
    
    // TODO copy the files to a secure location (outside /tmp )
  }

  public function execute() {
    /*
    //     restores POST and GET and FILES
    $get = $_GET;
    $_GET = $this->get;
   ...
    $nested->execute();
    ...
    $_GET = $get;
    */
  }

}

class SimpleJob implements Job{
	function execute(){
	//for simple jobs
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
