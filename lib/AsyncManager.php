<?php

class AsyncManager {

  private $storage = null;

  public function __construct($storage) {
    $this->storage = $storage;
  }

  public function add_job($job) {
    return $this->storage->add($job);
  }

}
