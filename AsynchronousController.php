<?php
class AsynchronousController{
  const TYPE_IMPORT = 1;
  public function init(){  
    ob_start("fatal_error_handler");

    

    ob_end_flush();
  }  
  public function addAsyncOperation($type=TYPE_IMPORT){
  
  }
  
  public function registerAsyncOperation($type=TYPE_IMPORT, $script="admin/oe_imports.php"){
  
  }  
  
  public function registerAsyncOperation($type=TYPE_IMPORT, $script="admin/oe_imports.php", $function="ImportProducts"){
  
  }
  
}
