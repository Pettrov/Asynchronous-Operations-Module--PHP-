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
  
  public function add(Job $job)
  {
    // adds a job to the storage and returns the id
  
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
  
  public function get($id)
  {
    // retrieves a job by it's id  
  }
  
  public function all()
  {
    // retrieves all jobs
  }
  
  public function first()
  {
    // retrieves the next job from the queue
    
      try {
          $dbh = new PDO($this->dsn, $this->user, $this->password, array(PDO::ATTR_PERSISTENT => true));
          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
          echo 'Connection failed: ' . $e->getMessage();
      }
      
      //crawl the table with tasks and fetch the last on top
      $sql = <<<EOQ
            SELECT * FROM tasklist WHERE status <> 4 ORDER BY id ASC LIMIT 1;
EOQ;
       
      foreach($dbh->query($sql) as $row) {
        $job = unserialize($row['job']);
      }
      
      $dbh = null;
      return $job;   
      
  }
  
  public function status($id)
  {
    // retrieves the status of a job      
  }
  
  public function result($id)
  {
    // retrieves the result of a job
  }
}
