<?php
  if(isset($_GET['message'])) echo htmlspecialchars($_GET['message'])."<br />";
  echo '
    <form action="index.php?action=add_job&target=products&script=my_import.php&func=InsertProducts" method="post" enctype="multipart/form-data">
    <input type="text" name="manufacturer" />
    <input type="file" name="ufile" />  
    <input type="submit" name="submit1" value="Execute sample task"/>
    </form>
    
    <form action="?action=execute_cron_job" method="post" enctype="multipart/form-data">
    <input type="submit" name="submit2" value="Perform the task later like a CRON"/>
    </form>    
    ';
