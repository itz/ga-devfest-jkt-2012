<?php
session_start();

unset($_SESSION['token']);

session_destroy();

$redirect = 'http://thinkwebdev1.net/devfest/index.php';
header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
