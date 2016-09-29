<?php
    require 'required/includes.php';
    require 'required/blogPost.php';

    $database = new Database;
    $database->query('SELECT * FROM posts');
    $rows = $database->resultset();

    if(@$_POST['delete'])
    {
        $delete_id = $_POST['delete_id'];
        $database->query('DELETE FROM posts WHERE id = :id');
        $database->bind(':id', $delete_id);
        $database->execute();
    }

    if(@$_POST['update'])
    {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $body = $_POST['body'];

        $database->query('UPDATE posts SET title = :title, body = :body WHERE id = :id');
        $database->bind(":title", $title);
        $database->bind(':body', $body);
        $database->bind(':id', $id);
        $database->execute();
    }

     if(@$_POST['submit'])
     {
         $title = $_POST['title'];
         $body = $_POST['body'];
         $id = $_POST['id'];

         $database->query('INSERT INTO posts (id, title, body) VALUES(:id, :title, :body)');
         $database->bind(':title', $title);
         $database->bind(':body', $body);
         $database->bind(':id', $id);
         $database->execute();

         if($database->lastInsertId())
         {
           echo '<p>Post Added!</p>';
         }
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

                    <ul class="nav navbar-nav navbar-right">
                        <li><a id="loginLink">Login <span class="glyphicon glyphicon-log-in"></span></a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">

            <?php $count = 0; foreach($rows as $row){ ?>
            <div class="row">
                <div class="col-xs-12 col-sm-10 col-sm-pull-1">
                    <div class="row row-content">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div style="float: left">
                                        <h2 style="margin-top: 10px"><?= $row['title']?></h2>
                                    </div>
                                    <div style="float: left; margin-top: 12px; margin-left: 15px">
                                        <?php
                                                    $database->query('SELECT * FROM tags LEFT JOIN (blogPostTags) ON (tags.tagsId = blogPostTags.tagsId) WHERE blogPostTags.postId = :inId');
                                                    $database->bind(':inId', $inPostId);
                                                    $database->execute();
                                        ?>
                                        <a class="btn btn-sm btn-success" style="color: white">School</a>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-2" style="float: left">
                                            <img src="<?= $row['imgUrl']?>" height=200 width=350>
                                        </div>
                                        <div class="col-md-7 col-md-push-3" style="white-space: normal; float: left">
                                            <p>
                                                <?= $row['body']?>
                                            </p>
                                            <div style="float: right; margin-bottom: 10px">
                                                <button class="btn btn-primary">Read More</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($count == 0) { ?>
                <div class="col-xs-12 col-sm-2 col-sm-pull-1">
                    <div class="row row-content">
                        <div class="col-md-12">
                            <label>Tags</label>
                            <hr>
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
    </body>
</html>