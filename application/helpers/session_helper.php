<?php

// Get real visitor IP behind CloudFlare network
if (!function_exists('isLoggedIn'))
{
    function isLoggedIn()
    {
        if (isset($_COOKIE['token'])){
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('checkToken'))
{
    function checkToken()
    {
        $CI = get_instance();
        $CI->load->model('Users_model');
        if (isLoggedIn()){
            if ($CI->Users_model->countToken($_COOKIE['token']) < 1) {
                header('Location: /logout');
                exit;
            }
        } else {
            header('Location: /login');
            exit;
        }
    }
}

// Check if Admin rank
if (!function_exists('isAdmin'))
{
    function isAdmin()
    {
        $CI = get_instance();
        $CI->load->model('Users_model');
        if($CI->Users_model->getUserRankByToken($_COOKIE['token']) >= 10){
            return true;
        } else {
            return false;
        }
    }
}