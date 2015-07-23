<?php
$app->get('/', 'battleships.controller:index');
$app->get('/play', 'battleships.controller:play')->bind('play');
$app->get('/new-game', 'battleships.controller:newGame')->bind('newGame');