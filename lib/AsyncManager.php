<?php
//Facade
class AsyncManager {

  private $storage = null;



  function __construct($storage) {
    $this->storage = $storage;
  }



/*****************************************************
 * Adds a job in the storage.
 *****************************************************/ 
  public function add_job($job) {
    return $this->storage->add($job);
  }



/*****************************************************
 * Executes a job by given $id.
 *****************************************************/ 
  public function async_execute($id) {
    $job = $this->storage->get($id);
    if($job){
      $this->storage->set_status($id, 1); //'Started executing' flag    
      $exec_result = $this->execute($job);
      if($exec_result){
        $this->storage->set_status($id, 3); //'Finished' flag                
        $this->storage->set_result($id, 'Job result'); //Write some result to db                
        return true;
      }
      else{
        $this->storage->set_status($id, 2); //'Failed' flag                     
        $this->storage->set_result($id, 'Fail reason'); // Write the fail reason in the result field in db
        return false;
      }    
    }
    else
      return false;
  }



/*****************************************************
 * Executes all not successfully executed jobs.
 *****************************************************/ 
  public function async_execute_all() {
    $exec_only = true;
    $result = "";
    $jobs = $this->storage->all($exec_only);
    if($jobs){    
      foreach($jobs as $job){
        $this->storage->set_status($job[0], 1); //'Started executing' flag          
           
        $exec_result = $this->execute($job[1]);
        
        if($exec_result){
          $this->storage->set_status($job[0], 3); //'Finished' flag                
          $this->storage->set_result($job[0], 'Job result'); //Write some result to db                
          $result .= 'Job <b>'.$job[0].'</b> executed successfully!<br />';          
        }
        else{
          $this->storage->set_status($job[0], 2); //'Failed' flag                     
          $this->storage->set_result($job[0], 'Fail reason'); // Write the fail reason in the result field in db
          $result .= 'Job <b>'.$job[0].'</b> failed to execute!<br />';                    
        }
      }
    }
    return $result;
  } 
  
  
  
/*****************************************************
 * Executes the first job in the queue.
 *****************************************************/    
  public function execute_first_job() {
    $id = false; //passing empty $id by reference
    $job = $this->storage->first($id);
    if($job){
    
      $this->storage->set_status($id, 1); //'Started executing' flag        
      
      $exec_result = $this->execute($job);
      if($exec_result){
        $this->storage->set_status($id, 3); //'Finished' flag                
        $this->storage->set_result($id, 'Job result'); //Write some result to db                
        return $id;
      }
      else{
        $this->storage->set_status($id, 2); //'Failed' flag                     
        $this->storage->set_result($id, 'Fail reason'); // Write the fail reason in the result field in db
        return false;
      }    
    }
    else
      return false;
  }



/*****************************************************
 * Returns a list of all jobs
 *****************************************************/  
  public function async_list_jobs(){
    return $this->storage->all();
  }
  
  

/*****************************************************
 * Returns status of a job
 *****************************************************/    
  public function async_status($id){
    return $this->storage->status($id);
  }



/*****************************************************
 * Returns result of a job
 *****************************************************/   
  public function get_result($id){    
    return $this->storage->result($id);
  }



/*****************************************************
 * Executes a job. Takes care of not executing 2 jobs simultaneously.
 *****************************************************/    
  public function execute($job) {
    $fp = fopen("file_storage/tmp/lock.txt", "r+");
        
    if (flock($fp, LOCK_EX)) { // do an exclusive lock
        fwrite($fp, "Write some locking info\n");
        
        $execute = $job->execute($job);    

        flock($fp, LOCK_UN); // release the lock
    }
    
    fclose($fp);
    return $execute;    
  }
  
}
