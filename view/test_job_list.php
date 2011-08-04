<?php
echo "All Jobs present: <br /><br />";
foreach($list as $job){
  echo "Job <b>".$job[0]."</b> | Status <b>".$job[2]."</b> | Result <b>".$job[3]."</b><br />";
}
