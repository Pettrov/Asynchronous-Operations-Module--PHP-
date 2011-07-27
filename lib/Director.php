<?php
//Custom Dependency Injection Container

function async_add_job(HTTPJob $job_obj){

  //making an object of AsyncManager with specific Storage Engine (PDO in our case)
  $store = new PDOStorage();
  $manager = new AsyncManager($store);
  
  
  $manager->add_job($job_obj);
  
}
