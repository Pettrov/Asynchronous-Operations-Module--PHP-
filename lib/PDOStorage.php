<?php

// PDO Storage implementation

class PDOStorage implements Storage {

  private $dsn, $user, $password;
  public $get, $post, $files;
  public $file, $function, $arguments;
  
  function __construct()
  {
      include 'config/storage.php';
      $this->dsn = $DSN;
      $this->user = $User;
      $this->password = $Pass;
  }



/**
  ****************************************************
  *  Adds a job to the storage and returns the id
  ****************************************************
  */  
  public function add(Job $job)
  {
      //Serialize the data here
      $job = serialize($job);
    
      try {
          $dbh = new PDO($this->dsn, $this->user, $this->password, array(PDO::ATTR_PERSISTENT => true));
          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
          echo 'Connection failed: ' . $e->getMessage();
      }
      
      $sql = <<<EOQ
          INSERT INTO `tasklist` (
          `id` ,
          `job` ,
          `status` ,
          `result`
          )
          VALUES (
          NULL , '$job', 0, NULL
          );  
EOQ;

      $dbh->query($sql);
      $exit = $dbh->lastInsertId();
      $dbh = null;
      
      return $exit;  
  }



/**
  ****************************************************
  *  Retrieves a job by it's id  
  ****************************************************
  */  
  public function get($id)
  {
      try {
          $dbh = new PDO($this->dsn, $this->user, $this->password, array(PDO::ATTR_PERSISTENT => true));
          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
          echo 'Connection failed: ' . $e->getMessage();
      }
      
      //crawl the table with tasks and fetch the one that is requested
      $sql = <<<EOQ
            SELECT * FROM tasklist WHERE id = '$id' AND status = 0 LIMIT 1;
EOQ;
       
      foreach($dbh->query($sql) as $row) {
        $job = unserialize($row['job']);
      }
      
//Set the status flag to 1 (started executing)
      if($job){
        $sql = <<<EOQ
        UPDATE `tasklist` SET `status` = '1' WHERE `id` = {$row['id']}
EOQ;

        $dbh->query($sql);
      }
      
      $dbh = null;
      
      if($job)
        return $job;   
      else
        return false;  
  }



/**
  ****************************************************
  *  Retrieves all jobs
  ****************************************************
  */  
  public function all()
  {
      try {
          $dbh = new PDO($this->dsn, $this->user, $this->password, array(PDO::ATTR_PERSISTENT => true));
          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
          echo 'Connection failed: ' . $e->getMessage();
      }
      
      //crawl the table with tasks and fetch all
      $sql = <<<EOQ
            SELECT * FROM tasklist ORDER BY id ASC;
EOQ;
      $tasks = array(); 
      foreach($dbh->query($sql) as $row) {
        $tasks[] = $row;
      }

      $dbh = null;
      
      if($tasks)
        return $tasks;   
      else
        return false;  
  }



/**
  ****************************************************
  *  Retrieves the first job in the queue
  ****************************************************
  */    
  public function first()
  {
      try {
          $dbh = new PDO($this->dsn, $this->user, $this->password, array(PDO::ATTR_PERSISTENT => true));
          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
          echo 'Connection failed: ' . $e->getMessage();
      }
      
      //crawl the table with tasks and fetch the last on top
      $sql = <<<EOQ
            SELECT * FROM tasklist WHERE status <> 4 AND status = 0 ORDER BY id ASC LIMIT 1;
EOQ;
       
      foreach($dbh->query($sql) as $row) {
        $job = unserialize($row['job']);
      }

      //Set the status flag to 1 (started executing)
      if($job){
        $sql = <<<EOQ
        UPDATE `tasklist` SET `status` = '1' WHERE `id` = {$row['id']}
EOQ;

        $dbh->query($sql);
      }
            
      $dbh = null;
      
      if($job)
        return $job;   
      else
        return false;  
  }
  


/**
  ****************************************************
  *  Retrieves the status of a job      
  ****************************************************
  */  
  public function status($id)
  {
      try {
          $dbh = new PDO($this->dsn, $this->user, $this->password, array(PDO::ATTR_PERSISTENT => true));
          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
          echo 'Connection failed: ' . $e->getMessage();
      }
      
      //crawl the table with tasks and fetch the one that is requested
      $sql = <<<EOQ
            SELECT * FROM tasklist WHERE id = '$id' LIMIT 1;
EOQ;

      $status = 9; //default status for 'job not found'
      foreach($dbh->query($sql) as $row) {
        $status = $row['status'];
      }
      
      $dbh = null;
      return $status;   
   }
  
  
  
/**
  ****************************************************
  *  Retrieves the result of a job
  ****************************************************
  */   
  public function result($id)
  {
      try {
          $dbh = new PDO($this->dsn, $this->user, $this->password, array(PDO::ATTR_PERSISTENT => true));
          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
          echo 'Connection failed: ' . $e->getMessage();
      }
      
      //crawl the table with tasks and fetch the one that is requested
      $sql = <<<EOQ
            SELECT * FROM tasklist WHERE id = '$id' LIMIT 1;
EOQ;

      $result = false; //default result for 'job not found'
      foreach($dbh->query($sql) as $row) {
        $result = $row['result'];
      }
      
      $dbh = null;
      return $result;     
  }
  
}
