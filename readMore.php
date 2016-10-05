<?php
if(isset($_GET['postId']))
{

    include "required/includes.php";
    session_start();

    $database = new Database();

    $database->query("SELECT * FROM posts WHERE postId = :id");
    $database->bind(':id', $_GET['postId']);
    $result = $database->resultset();

    ?>

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
                    <li><a href="index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
                </ul>

                <ul class="nav navbar-nav">
                    <li><a href="blogPost.php"><span class="glyphicon glyphicon-plus-sign"></span> Add a Post</a></li>
                </ul>

                <ul class="nav navbar-nav">
                    <li><a id="addPost" href="index.php?userId=<?= $_SESSION['user_id']?>">My Posts</a></li>
                </ul>

                <?php
                if(@isset($_SESSION['user_id']))
                {
                    ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a id="logOut" data-toggle="tooltip" data-placement="bottom" title="Log Out" href="logIO.php?logout=yes"><span class="glyphicon glyphicon-log-out"> </span> <?= $_SESSION['username'] ?></a></li>
                    </ul>
                    <?php

                } ?>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row row-content">
            <div class="col-md-12">
                <h1><?= $result['title'] ?></h1>

                Posted by <span class="glyphicon glyphicon-user"></span>
                <?php
                $originalDate = $result['create_date'];
                $date = new DateTime($originalDate);
                $time = date("g:i A", strtotime($result['create_date']));

                $database->query('SELECT firstName, lastName FROM people LEFT JOIN posts ON people.userId = posts.userId WHERE posts.postId = :inId');
                $database->bind(':inId', $result['postId']);
                $names = $database->resultset();
                echo " " . $names['firstName'] . " " . $names['lastName'] . " on <span class='glyphicon glyphicon-calendar'> </span> " . $date->format('m-d-Y') . " at <span class='glyphicon glyphicon-time'> </span> " . $time . " | ";

                $database->query('SELECT name FROM tags LEFT JOIN (blogPostTags) ON (tags.tagsId = blogPostTags.tagsId) WHERE blogPostTags.postId = :inId');
                $database->bind(':inId', $result['postId']);
                $tagName = $database->resultset();

                echo "Tags: ";
                if($database->rowNum != 1)
                {
                    foreach($tagName as $name)
                        echo '<a class="btn btn-xs btn-success" style="color: white">' . $name["name"] . '</a> ';
                }
                else
                    echo '<a class="btn btn-xs btn-success" style="color: white">' . $tagName['name'] . '</a>';
                ?>
                <hr>

                <div align="center">
                    <img src="pictures/<?= $result['imgUrl'] ?>" style="width: 540px; height: 240px">
                </div>

               <br> <p style="line-height: 225%; text-indent: 50px;"><?= $result['body'] ?></p>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="required/js/jquery.min.js"></script>
    <script src="required/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function()
        {
            $('[data-toggle="tooltip"]').tooltip();

        });
    </script
    </body>
    </html>

<?php }
else
{
    echo "Choose a post to read more of!";
}
?>

