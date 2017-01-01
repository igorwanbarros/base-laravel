<?php

$app->get('/home', [
    //'middleware'    => 'auth:base_home',
    'as'    => 'home',
    'uses'  => 'HomeController@index'
]);

