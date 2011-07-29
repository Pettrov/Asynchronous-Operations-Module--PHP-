<?php
//Custom Dependency Injection Container

function async_add_job(HTTPJob $job_obj){

  //making an object of AsyncManager with specific Storage Engine (PDO in our case)
  $store = new PDOStorage();
  $manager = new AsyncManager($store);
  
  
  return $manager->add_job($job_obj);
  
}

function async_execute_pop($id){
  //executing last job on stack
  $store = new PDOStorage();
  $manager = new AsyncManager($store);  
  
  return $manager->execute_pop_job();
  
}
