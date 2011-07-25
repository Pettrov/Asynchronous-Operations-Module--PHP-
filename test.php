<?php

require 'lib/async.php';

class AsyncTest extends PHPUnit_Framework_TestCase {

  private $async;
  private $storage;

  protected function setUp() {
    // create a mock storage to simplify testing
    $this->storage = $this->getMock('Storage', array('add', 'get', 'all', 'pop', 'status', 'result'));
    $this->assertNotNull($this->storage);
    $this->async = new AsyncManager($this->storage);
  }  
  

  // we test with simple values for job and id, because we want to validate the core framework works
  public function testAddJob() {
    $this->storage
      ->expects($this->once())
      ->method('add')
      ->with($this->identicalTo("job"))
      ->will($this->returnValue(1));
    
    $this->assertEquals(1, $this->async->add_job("job"));
  }

}
