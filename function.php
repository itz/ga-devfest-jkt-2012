<?php

function xdebug($param=array()) {
    echo '<pre>' . print_r($param, true) . '</pre>';
}

function minute($seconds) {
    $mins = floor($seconds / 60);
    $secs = round($seconds - ($mins * 60));
    if (strlen($secs) == 1)
        $secs = '0' . $secs;
    return $mins . ':' . $secs;
}