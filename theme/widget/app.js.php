<?php

$app = __dir__.'/app-js';
include $app.'/main.js';

if (is_dir($app.'/Factories')) {
    $factories = scandir($app.'/Factories');
    foreach ($factories as $factory) {
        if ($factory!=='.'&&$factory!=='..') {
            include $app.'/Factories/'.$factory;
        }
    }
}

if (is_dir($app.'/Services')) {
    $services = scandir($app.'/Services');
    foreach ($services as $service) {
        if ($service!=='.'&&$service!=='..') {
            include $app.'/Services/'.$service;
        }
    }
}

if (is_dir($app.'/Scopes')) {
    $scopes = scandir($app.'/Scopes');
    foreach ($scopes as $scope) {
        if ($scope!=='.'&&$scope!=='..') {
            include $app.'/Scopes/'.$scope;
        }
    }
}
