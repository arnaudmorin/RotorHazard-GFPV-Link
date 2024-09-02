<?php

function dolog($mess){
    $log_entry = "[" . date("Y-m-d H:i:s") . "] " . $mess . "\n";
    file_put_contents('log.txt', $log_entry, FILE_APPEND | LOCK_EX);
}
