<?php

$dir = __dir__.'/lib-css';
$libs = scandir($dir);
foreach ($libs as $lib) {
    if ($lib!=='.'&&$lib!=='..') {
        include $dir.'/'.$lib;
    }
}
