<?php

// Check if user is logged in
if (!function_exists('isLoggedIn'))
{
    function isLoggedIn()
    {
        $CI = get_instance();
        $CI->load->model('Users_model');
        if (isset($_COOKIE['token'])) {
            if ($CI->Users_model->countToken($_COOKIE['token']) < 1) {
                return false;
            }
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
        if (!isLoggedIn()) {
            header('Location: /login');
            exit();
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