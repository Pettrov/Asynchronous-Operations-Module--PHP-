<?php

// interface that provides simplified access to storage systems for the purposes of async library
interface Storage {  
  // adds a job to the storage and returns the id
  public function add(Job $job);
  
  // retrieves a job by it's id
  public function get($id);
  
  // retrieves all job id's
  public function all();
  
  // retrieves the next job from the queue
  public function first();
  
  // retrieves the status of a job
  public function status($id);
  
  // retrieves the result of a job
  public function result($id);
}
