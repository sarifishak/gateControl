<?php

$myfile = fopen('gateStatus.txt', 'w') or die('Unable to open file!');
    
$txt = "1\n";
fwrite($myfile, $txt);
fclose($myfile);
?>