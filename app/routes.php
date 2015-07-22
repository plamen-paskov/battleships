<?php
$app->get('/', 'battleships.controller:index');
$app->get('/play', 'battleships.controller:play')->bind('play');