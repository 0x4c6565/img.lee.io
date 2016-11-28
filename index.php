<?php

session_start();

include_once('config.php');

function validate_login_credentials($username, $password)
{
    return ($username == USERNAME && md5($password) == PASSWORDMD5);
}

if (isset($_POST['login']))
{
    if (validate_login_credentials($_POST['username'], $_POST['password']))
    {
        $_SESSION['login'] = true;
    }
    else
    {
        $errorcode=1;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/css/img.lee.io.css" rel="stylesheet">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/img.lee.io_alert.js"></script>
<script src="/js/img.lee.io.js"></script>
<script type="text/javascript">
// We start by checking if the browser supports the 
// Clipboard object. If not, we need to create a 
// contenteditable element that catches all pasted data 

</script>
</head>
<body>
    <div class="col-md-10 col-md-offset-1 clear-top">
        <div id="messages">
        </div>
    </div>
    <?php
        if (!check_login())
        {
            echo '
            <div class="col-md-2 col-md-offset-5 clear-top">
                        <form action="/" method="POST" class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                        </div>
                                <div class="form-group">
                                    <label class="control-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        </div>
                        <div class="form-group">
                                        <button type="submit" class="btn btn-success pull-right" name="login">Upload</button>
                                </div>
                        </form>
            </div>

            ';
        }
        else
        {
            echo '
            <script>initialisePaste();</script>
            <div class="col-md-2 col-md-offset-5 text-center clear-top">PASTE!'; 
            if (logged_in())
            { 
                echo '[<a href="logout.php">Logout</a>]';
            }
            echo '
                </div>
            ';
        }

        if (isset($errorcode))
        {
            switch($errorcode)
            {
                case 1:
                    echo "<script>addAlertError('Invalid username/password');</script>";
                    break;
            }
        }

    ?>
</body>
</html>
