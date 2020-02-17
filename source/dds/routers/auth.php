<?php


$app->post('/login', '\DDSControllers\LoginController:postLogin'); 
$app->post('/signup', '\DDSControllers\RegisterController:postRegister'); 
$app->post('/logout', '\DDSControllers\LogoutController:postLogout'); 