<?php

require 'lib/async.php';
require 'tests/AsyncManagerTest.php';

class AllTests {
    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('All tests');
      
        $suite->addTestSuite('AsyncManagerTest');
        
        return $suite;
    }
}

