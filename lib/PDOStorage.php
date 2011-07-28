<?php

// PDO Storage implementation

class PDOStorage implements Storage {
  public $get, $post, $files;
  public $file, $function, $arguments;
  
  public function __construct(){
  
  
  }
  
  // adds a job to the storage and returns the id
  public function add(Job $job){
      //PDO Storage Engine
      $dsn = 'mysql:dbname=testdb;host=127.0.0.1'; //db type, dbname, host
      $user = 'foo'; // username
      $password = 'bar'; // password

      try {
          $dbh = new PDO($dsn, $user, $password, array(PDO::ATTR_PERSISTENT => true));
          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
          echo 'Connection failed: ' . $e->getMessage();
      }
      
      //TODO Serialize the data in $job here


      $exit = $dbh->query('INSERT INTO foo');
      $dbh = null;
      return $exit;  
  }
  
  // retrieves a job by it's id
  public function get($id){
  
  }
  
  // retrieves all job id's
  public function all(){

      //PDO
      $dsn = 'mysql:dbname=testdb;host=127.0.0.1'; //db type, dbname, host
      $user = 'foo'; // username
      $password = 'bar'; // password

      try {
          $dbh = new PDO($dsn, $user, $password, array(PDO::ATTR_PERSISTENT => true));
          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
          echo 'Connection failed: ' . $e->getMessage();
      }
      
       
      foreach($dbh->query('SELECT * from foo') as $row) {
	      $this->get = $row[1];
	      $this->post = $row[2];
	      $this->files = $row[3];
	      $this->file = $row[4];
 	      $this->function = $row[5];	      	      	      
	      $this->arguments = $row[6]; 	      
      }
      
      //TODO deserialize arguments before returning them
      
      $dbh = null;
      
      return $this;    
  
  }
  
  // retrieves the next job from the queue
  public function pop(){
  
  }
  
  // retrieves the status of a job
  public function status($id){
  
  }
  
  // retrieves the result of a job
  public function result($id){
  
  }
}
