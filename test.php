<?php

$a=null;

if(isset($a)){  
  echo 'yes';
}
$a=$a ?: 2;

var_dump( $a);