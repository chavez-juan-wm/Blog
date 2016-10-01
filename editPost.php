<?php
    if(isset($_GET['postId']))
    {


    include "required/includes.php";
    session_start();

    $database = new Database();

    if(@$_POST['delete'])
    {
        $delete_id = $_POST['delete_id'];
        $database->query('DELETE FROM posts WHERE id = :id');
        $database->bind(':id', $delete_id);
        $database->execute();
    }

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
                <li class="active"><a href="blogPost.php"><span class="glyphicon glyphicon-plus-sign"></span> Add a Post</a></li>
            </ul>

            <?php
            if(@isset($_SESSION['user_id']))
            {
                ?>
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

            } ?>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row row-content">
        <h1>Edit Your Post</h1>
        <?php
            if(@$_POST['update'])
            {
                $title = $_POST['title'];
                $body = $_POST['body'];

                $database->query('UPDATE posts SET title = :title, body = :body WHERE postId = :id');
                $database->bind(":title", $title);
                $database->bind(':body', $body);
                $database->bind(':id', $_GET['postId']);
                $database->execute();

                header("Location: index.php");
            }
        ?><hr>

        <form enctype="multipart/form-data" method="post">
            <div class="form-group">
                <div class="col-md-2">
                    <label>Post Title</label><br />
                    <input type="text" name="title" placeholder="Add a Title..." value="<?= $result['title']?>" required><br /><br />

                    <div id="tags">
                        <label for="tag2">Tags</label><span style="margin-left: 5px"><button class="btn btn-default btn-xs" id="addTag">+</button></span>
                        <input list="tag" id="tag2" name="tag[]" style="width: 135px" required>
                        <datalist id="tag">
                            <?php
                            $database->query('SELECT * FROM tags');
                            $names = $database->resultset();

                            if($database->rowNum != 1)
                            {
                                foreach($names as $name)
                                    echo "<option value='". $name['name'] ."'></option>";
                            }
                            else
                                echo "<option value='". $names['name'] ."'></option>";
                            ?>
                        </datalist>
                    </div>

                    <br><label for="screenshot">Image:</label><br>
                    <input type="file" id="screenshot" name="screenshot" required>
                </div>
                <div class="col-md-10" style="position: relative">
                    <label>Post Body</label><br />
                    <textarea name="body" style="width: 935px; height: 350px" required><?= $result['body'] ?></textarea><br /><br /><br>

                    <input class="btn btn-primary" style="position: absolute; bottom: 0; right: 0" type="submit" id="update" name="update" value="Update" />
                </div>
            </div>
        </form>
    </div>
</div>

<!-- jQuery -->
<script src="required/js/jquery.min.js"></script>
<script src="required/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function(){
        var count = 1;
        $("#addTag").on("click", function(event)
        {
            event.preventDefault();
            var input = "<input list='tag' id='"+ count +"' name='tag[]' style='margin-top: 10px; width: 135px' required> <datalist id='tag'></datalist> <button class='btn btn-default btn-xs remove' id='"+ count +"button'>-</button>";
            $("#tags").append(input);
            count++;
        });

        $('body').on("click", ".btn.btn-default.btn-xs.remove" , function(e)
        {
            e.preventDefault();
            var buttonId = parseInt(this.id);

            $("#" + buttonId +"").remove();
            $("#" + this.id +"").remove();
        });
    });
</script
</body>
</html>

<?php }
    else
    {
        echo "Choose a post to edit!";
    }
?>

