<?php 
$webRoot = str_replace("\\","/",dirname(__FILE__))."/";
header("Content-type:image/jpg");
echo file_get_contents($webRoot.'nopic.gif') ;
?>
