<?php
$Routes->add('login', '/login', 'Home', 'login');

$Routes->add('edit-profile', '/profile/{id}/edit', 'Profile', 'edit');

$Routes->add('hello-world', '/hello-world', 'Home', 'helloWorld');

$Routes->root('/', 'Home', 'index');

$Routes->resources('student', 'Student');

//print_r($Routes->routes());