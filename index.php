<?php
    // Start the session
    session_start();

    if(isset($_COOKIE['username']) && isset($_COOKIE['user_id']))
    {
        $_SESSION['user_id'] = $_COOKIE['user_id'];
        $_SESSION['username'] = $_COOKIE['username'];
    }

    require 'required/includes.php';
    $database = new Database;


    if(isset($_GET['tagsId']))
    {
        $database->query('SELECT * FROM posts LEFT JOIN blogPostTags ON (posts.postId = blogPostTags.postId) WHERE blogPostTags.tagsId = :inId ');
        $database->bind(':inId', $_GET['tagsId']);
        $rows = $database->resultset();
    }
    else
    {
        $database->query('SELECT * FROM posts ORDER BY create_date DESC');
        $rows = $database->resultset();
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>My Blog</title>

        <link href="required/css/bootstrap.min.css" rel="stylesheet">
        <link href="required/css/styles.css" rel="stylesheet">
        <link href="required/css/animate.css" rel="stylesheet">
    </head>

    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>

                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
                    </ul>

<!--                    <ul class="nav navbar-nav">-->
<!--                        <li><button type="button" class="btn btn-link"><span class="glyphicon glyphicon-home"></span> Home</button></li>-->
<!--                    </ul>-->

                    <?php
                        if(isset($_SESSION['user_id']))
                        {
                            ?>
                            <ul class="nav navbar-nav">
                                <li><a id="addPost" href="blogPost.php"><span class="glyphicon glyphicon-plus-sign"></span> Add a Post</a></li>
                            </ul>
                            <ul class="nav navbar-nav navbar-right">
                                <li class="dropdown">
                                    <a class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-log-out"> </span> <?= $_SESSION['username'] ?> <span class="caret"></span></a>

                                    <ul class="dropdown-menu dropdown-lr animated slideInRight" role="menu">
                                        <div class="col-lg-12">
                                            <div class="text-center"><h3><b>Log Out</b></h3></div>
                                            <form method="post" action="logIO.php" role="form">
                                                <div class="form-group">
                                                    <button name="logout" class="btn btn-danger" value="logout">Logout</button>
                                                </div>
                                            </form>
                                        </div>
                                    </ul>
                                </li>
                            </ul>
                            <?php

                        }
                        else
                        {
                    ?>

                    <ul class="nav navbar-nav">
                        <li><a id="addPost" data-toggle="tooltip" data-placement="bottom" title="Log in first to add a post." href="blogPost.php"><span class="glyphicon glyphicon-plus-sign"></span> Add a Post</a></li>
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown">Register <span class="caret"></span></a>
                            <ul class="dropdown-menu dropdown-lr animated slideInRight" role="menu">
                                <div class="col-lg-12">
                                    <div class="text-center"><h3><b>Register</b></h3></div>
                                    <form method="post" role="form">
                                        <div class="form-group">
                                            <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="firstName" id="firstName" class="form-control" placeholder="First Name" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="lastName" id="lastName" class="form-control" placeholder="Last Name" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="email" name="email" id="email" class="form-control" placeholder="Email Address" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="confirm-password" id="confirm-password" class="form-control" placeholder="Confirm Password" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" name="register-submit" id="register-submit" class="form-control btn btn-info" value="Register Now">
                                        </div>
                                    </form>
                                </div>
                            </ul>
                        </li>

                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-log-in"> </span> Log In <span class="caret"></span></a>

                            <ul class="dropdown-menu dropdown-lr animated slideInRight" role="menu">
                                <div class="col-lg-12">
                                    <div class="text-center"><h3><b>Log In</b></h3></div>
                                    <form method="post" role="form" action="logIO.php">
                                        <div class="form-group">
                                            <label for="username">Username</label>
                                            <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xs-7">
                                                    <input type="checkbox" name="remember" id="remember" value="remember">
                                                    <label for="remember"> Remember Me</label>
                                                </div>
                                                <div class="col-xs-5 pull-right">
                                                    <input type="submit" name="login-submit" id="login-submit" class="form-control btn btn-success" value="Log In">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="text-center">
                                                        <a class="forgot-password">Forgot Password?</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </ul>
                        </li>
                    </ul>
                    <?php }?>
                </div>
            </div>
        </nav>

        <div class="container">
            <?php $count = 0; foreach($rows as $row){ ?>
            <div class="row">
                <div class="col-xs-12 col-sm-10">
                    <div class="row row-content">
                        <?php if(isset($_SESSION['error']) && $count == 0) echo $_SESSION['error'] ?>
                        <div class="col-md-12"  style="border: solid lightgray 1px; width: 900px">
                            <div class="row">
                                <div class="col-md-12">
                                    <div style="float: left">
                                        <h2 style="margin-top: 10px"><?= $row['title']?></h2>
                                    </div>
                                    <div style="float: left; margin-top: 12px; margin-left: 15px">
                                        <?php
                                            $database->query('SELECT name FROM tags LEFT JOIN (blogPostTags) ON (tags.tagsId = blogPostTags.tagsId) WHERE blogPostTags.postId = :inId');
                                            $database->bind(':inId', $row['postId']);
                                            $tagName = $database->resultset();

                                            if($database->rowNum != 1)
                                            {
                                                foreach($tagName as $name)
                                                    echo '<a class="btn btn-sm btn-success" style="color: white">' . $name["name"] . '</a> ';
                                            }
                                            else
                                                echo '<a class="btn btn-sm btn-success" style="color: white">' . $tagName['name'] . '</a>';
                                            ?>
                                    </div>

                                    <?php
                                        $database->query('SELECT userId FROM posts WHERE postId = :inId');
                                        $database->bind(':inId', $row['postId']);
                                        $userId = $database->resultset();

                                        if(@$userId['userId'] == @$_SESSION['user_id'])
                                        {

                                    ?>
                                        <div style="float: right">
                                            <a class="btn-link" href="editPost.php?postId=<?= $row['postId'] ?>"><span class="glyphicon glyphicon-edit"></span> Edit</a>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="col-md-12" style="margin-top: 12px; margin-bottom: 5px">
                                    Posted by <span class="glyphicon glyphicon-user"></span>
                                    <?php
                                        $originalDate = $row['create_date'];
                                        $date = new DateTime($originalDate);
                                        $time = date("g:i A", strtotime($row['create_date']));

                                        $database->query('SELECT firstName, lastName FROM people LEFT JOIN posts ON people.userId = posts.userId WHERE posts.postId = :inId');
                                        $database->bind(':inId', $row['postId']);
                                        $names = $database->resultset();
                                        echo " " . $names['firstName'] . " " . $names['lastName'] . " on <span class='glyphicon glyphicon-calendar'> </span> " . $date->format('m-d-Y') . " at <span class='glyphicon glyphicon-time'> </span> " . $time;
                                    ?>
                                </div>

                                <div class="col-md-12">
                                    <div class="row" style="position: relative">
                                        <div class="col-md-2" style="margin-bottom: 10px">
                                            <img src="pictures/<?= $row['imgUrl']?>" height=200 width=350>
                                        </div>

                                        <div class="col-md-7 col-md-push-3">
                                            <p style="line-height: 200%">
                                                <?php
                                                    $cutOff = (strlen($row['body']) > 400) ? substr($row['body'], 0, 400) . '...' : $row['body'];
                                                    echo $cutOff;
                                                ?>
                                            </p>
                                        </div>

                                        <span style="position: absolute; right: 10px; bottom: 9px;"> <button class="btn btn-primary">Read More</button></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($count == 0) { ?>
                    <div class="col-xs-12 col-sm-2 col-sm-pull-1" style="position: fixed; padding-left: 55px">
                        <div class="row row-content" style="padding: 50px 0;">
                            <div class="col-md-12" style="width: 200px">
                                <h3>Tags</h3>
                                <hr style="margin-top: 2px; margin-bottom: 8px">

                                <?php
                                $database->query('SELECT name FROM tags');
                                $names = $database->resultset();

                                if($database->rowNum != 1)
                                {
                                    foreach($names as $name)
                                        echo "<a class='btn btn-success btn-xs' style='margin-top: 5px; margin-right: 5px'>". $name['name'] ."</a>";
                                }
                                else
                                    echo "<a class=\"btn btn-success btn-xs\" style=\"margin-top: 5px; margin-right: 5px\">". $names['name'] ."</a>";
                                ?>
                            </div>
                        </div>
                    </div>
                <?php } $count++;?>

            </div>

            <?php }?>
        </div>

        <!-- jQuery -->
        <script src="required/js/jquery.min.js"></script>
        <script src="required/js/bootstrap.min.js"></script>

        <script>
            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip();

                $("#addPost").on("click", function(event)
                {
                    if(<?php if(!isset($_SESSION['username'])) echo true; ?>)
                        event.preventDefault();
                });
            });
        </script>
    </body>
</html>