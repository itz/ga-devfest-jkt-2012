<?php

session_start();

include('config.php');
include('function.php');

if (isset($_GET['code'])) {
    $googleauth->authenticate();
    $token = $googleauth->getAccessToken();
    $token_data = json_decode($token, true);
    $user = $userdata->userinfo->get();
    if ($token_data && $user) {
        $_SESSION['token'] = $token;

        $db['name'] = $user['name'];
        $db['username'] = $user['email'];
        $db['acc_token'] = $token;
        $db['acc_access_token'] = $token_data['access_token'];
        $db['acc_token_type'] = $token_data['token_type'];
        $db['acc_expires_in'] = $token_data['expires_in'];
        $db['acc_id_token'] = $token_data['id_token'];
        $db['acc_refresh_token'] = $token_data['refresh_token'];
        $db['acc_created'] = $token_data['created'];
        $db['acc_id'] = $user['id'];
        $db['acc_verified_email'] = $user['verified_email'];
        $db['postdate'] = date('Y-m-d H:i:s');

#		xdebug($db);

        $redirect = 'http://thinkwebdev1.net/devfest/selection.php';
        header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
    }
} else {
    $authUrl = $googleauth->createAuthUrl();
    echo "<a class='login' href='$authUrl'>Connect Me!</a>";
}
