<?php

function check_login()
{
    if (in_array($_SERVER['REMOTE_ADDR'], array('1.2.3.4')))
    {
        return true;
    }

    return logged_in();
}

function logged_in()
{
    return (isset($_SESSION['login']) && $_SESSION['login'] == true);
}

define('PASSWORDMD5', 'HASHHERE');
define('USERNAME', 'lee');
define('DOCROOT', dirname(__FILE__));
