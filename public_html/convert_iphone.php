<?php
require_once dirname(__file__) . '/common.php';

$command = dirname(__file__) . "\\..\\vendor\\ffmpeg\\bin\\ffmpeg.exe -i " . escapeshellarg(request_info()['abs_path']) . " -vcodec libx264 -vprofile baseline -preset slow -b:v 250k -maxrate 250k -bufsize 500k -vf scale=-1:360 -threads 0 -acodec libvo_aacenc -ab 96k " . escapeshellarg(request_info()['iphone']) . " > " . escapeshellarg(request_info()['log']) . " 2>&1";

function execInBackground($cmd) {  
    if (substr(php_uname(), 0, 7) == "Windows"){  
        pclose(popen("start /B ". $cmd, "r"));   
    }  
    else {  
        exec($cmd . " > /dev/null &");
    }
} 

execInBackground($command); 

header('Location: ../list.php');
die();