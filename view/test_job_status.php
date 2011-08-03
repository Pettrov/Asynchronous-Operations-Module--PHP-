<?php
echo "Job <b>".$job_request."</b><br />";
switch((int)$status){
  case 0: echo "Status : <b>Not executed</b>";break;
  case 1: echo "Status : <b>Started execution</b>";break;
  case 2: echo "Status : <b>Failed</b>";break;
  case 3: echo "Status : <b>Finished</b>";break;    
  case 9: echo "Status : Job not found!";break;    
  default: echo "Status : Unknown";break;
}      


