<?php

// PDO Storage implementation

class PDOStorage implements Storage {
  public $get, $post, $files;
  public $file, $function, $arguments;
  
  public function __construct(){
  
  
  }
  
  // adds a job to the storage and returns the id
  public function add(Job $job){

      //Serialize the global arrays here
      $job->get = serialize($job->get);
      $job->post = serialize($job->post);
      $job->files = serialize($job->files);
    
      $dsn = 'mysql:dbname=oe_back;host=127.0.0.1'; //db type, dbname, host
      $user = 'root'; // username
      $password = '123456'; // password

      try {
          $dbh = new PDO($dsn, $user, $password, array(PDO::ATTR_PERSISTENT => true));
          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
          echo 'Connection failed: ' . $e->getMessage();
      }
      
      $sql = <<<EOQ
          INSERT INTO `tasklist` (
          `id` ,
          `url` ,
          `func` ,
          `arguments` ,                    
          `get` ,
          `post` ,
          `files`
          )
          VALUES (
          NULL , '{$job->file}', '{$job->func}', '{$job->arguments}', '{$job->get}', '{$job->post}', '{$job->get}'
          );  
EOQ;

      $dbh->query($sql);
      $exit = $dbh->lastInsertId();
      $dbh = null;
      
      return $exit;  
  }
  
  // retrieves a job by it's id
  public function get($id){
  
  }
  
  // retrieves all jobs
  public function all(){

  }
  
  // retrieves the next job from the queue
  public function pop($job){

      $dsn = 'mysql:dbname=oe_back;host=127.0.0.1'; //db type, dbname, host
      $user = 'root'; // username
      $password = '123456'; // password

      try {
          $dbh = new PDO($dsn, $user, $password, array(PDO::ATTR_PERSISTENT => true));
          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
          echo 'Connection failed: ' . $e->getMessage();
      }
      
      //crawl the table with tasks and fetch the last on top
      $sql = <<<EOQ
            SELECT * FROM tasklist WHERE executed <> 1 ORDER BY id DESC LIMIT 1;
EOQ;
       
      foreach($dbh->query($sql) as $row) {
        $job->job_id = $row['id'];
	      $job->file = $row['url'];
 	      $job->func = $row['func'];	      	      	      
	      $job->arguments = $row['arguments']; 
	            
	      $job->get = unserialize($row['get']);
	      $job->post = unserialize($row['post']);
	      $job->files = unserialize($row['files']);
	      
	      $job->job_status = $row['executed'];	      
      }
      
      $dbh = null;

      return $job;   
  
  }
  
  // retrieves the status of a job
  public function status($id){
  
  }
  
  // retrieves the result of a job
  public function result($id){
  
  }
}
