<?php
    require 'required/includes.php';
    $database = new Database;
    session_start();

    // If the user isn't logged in, try to log them in
    if (!isset($_SESSION['user_id']))
    {
        if (@$_POST['login-submit'])
        {
            // Grab the user-entered log-in data
            $user_username = $_POST['username'];
            $user_password = $_POST['password'];

            // Look up the username and password in the database
            $database->query("SELECT userId, userName FROM people WHERE username = :user_username AND password = SHA(:user_password)");
            $database->bind(':user_username', $user_username);
            $database->bind(':user_password', $user_password);
            $database->execute();
            $row = $database->resultset();

            if ($database->rowNum == 1)
            {
                // The log-in is OK so set the user ID and username session vars (and cookies), and redirect to the home page
                $_SESSION['user_id'] = $row['userId'];
                $_SESSION['username'] = $row['userName'];
                unset($_SESSION['error']);

                if(isset($_POST['remember']))
                {
                    setcookie('user_id', $row['userId'], time() + (60 * 60 * 24 * 30), "/");    // expires in 30 days
                    setcookie('username', $row['userName'], time() + (60 * 60 * 24 * 30), "/");  // expires in 30 days
                }
            }
            else
            {
                // The username/password are incorrect so set an error message
                $error_msg = '<p style="color: red">Sorry, you must enter a valid username and password to log in.</p>>';
                $_SESSION['error'] = $error_msg;
            }
        }
    }

    if(@$_POST['register'])
    {
        $database->query("INSERT INTO people (email, firstName, lastName, password, userName) VALUES (:email, :firstName, :lastName, SHA(:password), :userName);");
        $database->bind(':email', $_POST['email']);
        $database->bind(':firstName', $_POST['firstName']);
        $database->bind(':lastName', $_POST['lastName']);
        $database->bind(':password', $_POST['password']);
        $database->bind(':userName', $_POST['username']);
        $database->execute();

        // The log-in is OK so set the user ID and username session vars (and cookies), and redirect to the home page
        $_SESSION['user_id'] = $database->lastInsertId();
        $_SESSION['username'] = $_POST['username'];
    }

    if(isset($_GET['logout']))
    {
        unset($_SESSION['username']);
        unset($_SESSION['user_id']);
        unset($_SESSION['error']);
        session_destroy();

        setcookie('user_id', '', time() - 3600, '/');
        setcookie('username', '', time() - 3600, '/');
        unset($_COOKIE['user_id']);
        unset($_COOKIE['username']);
    }


    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
    header('Location: ' . $home_url);