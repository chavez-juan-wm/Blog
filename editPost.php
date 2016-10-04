<?php
    if(isset($_GET['postId']))
    {


    include "required/includes.php";
    session_start();

    $database = new Database();

    if(@$_POST['delete'])
    {
        $database->query('DELETE FROM posts WHERE postId = :id');
        $database->bind(':id', $_GET['postId']);
        $database->execute();

        $database->query('SELECT tagsId FROM blogPostTags WHERE postId = :id');
        $database->bind(':id', $_GET['postId']);
        $database->execute();
        $tagIds = $database->resultset();

        if ($database->rowNum == 1)
        {
            $database->query('SELECT tagsId FROM blogPostTags WHERE tagsId = :id');
            $database->bind(':id', $tagIds['tagsId']);
            $database->resultset();

            if($database->rowNum == 1)
            {
                $database->query('DELETE FROM tags WHERE tagsId = :id');
                $database->bind(':id', $tagIds['tagsId']);
                $database->execute();
            }
        }
        else
        {
            foreach($tagIds as $value)
            {
                $database->query('SELECT tagsId FROM blogPostTags WHERE tagsId = :id');
                $database->bind(':id', $value['tagsId']);
                $database->resultset();

                if($database->rowNum == 1)
                {
                    $database->query('DELETE FROM tags WHERE tagsId = :id');
                    $database->bind(':id', $value['tagsId']);
                    $database->execute();
                }
            }
        }

        $database->query('DELETE FROM blogPostTags WHERE postId = :id');
        $database->bind(':id', $_GET['postId']);
        $database->execute();

        header("Location: index.php");
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
        <h1>Edit Your Post</h1>
        <?php
            if(@$_POST['update'])
            {
                $screenshot_type = $_FILES['screenshot']['type'];
                $screenshot_size = $_FILES['screenshot']['size'];
                $screenshot = $_FILES['screenshot']['name'];

                $title = $_POST['title'];
                $body = $_POST['body'];
                $tags = $_POST['tag'];

                if($screenshot_size != 0 && $_FILES['screenshot']['error'] == 0)
                {
                    if ((($screenshot_type == 'image/gif') || ($screenshot_type == 'image/jpeg') || ($screenshot_type == 'image/pjpeg') || ($screenshot_type == 'image/png')) && ($screenshot_size > 0) && ($screenshot_size <= GW_MAXFILESIZE))
                    {
                        $target = GW_UPLOADPATH . $screenshot;
                        $file = "pictures/" . $result['imgUrl'];

                        if (!unlink($file))
                        {
                            echo ("Error deleting $file");
                        }
                        else
                        {
                            if(move_uploaded_file($_FILES['screenshot']['tmp_name'], $target))
                            {
                                $database->query('UPDATE posts SET title = :title, body = :body, imgUrl = :img WHERE postId = :id');
                                $database->bind(":title", $title);
                                $database->bind(':body', $body);
                                $database->bind(':id', $_GET['postId']);
                                $database->bind(':img', $screenshot);
                                $database->execute();

                                $database->query('SELECT tagsId FROM blogPostTags WHERE postId = :id');
                                $database->bind(':id', $_GET['postId']);
                                $database->execute();
                                $tagIds = $database->resultset();

                                if ($database->rowNum == 1)
                                {
                                    $database->query('SELECT tagsId FROM blogPostTags WHERE tagsId = :id');
                                    $database->bind(':id', $tagIds['tagsId']);
                                    $database->resultset();

                                    if($database->rowNum == 1)
                                    {
                                        $database->query('DELETE FROM tags WHERE tagsId = :id');
                                        $database->bind(':id', $tagIds['tagsId']);
                                        $database->execute();
                                    }
                                }
                                else
                                {
                                    foreach($tagIds as $value)
                                    {
                                        $database->query('SELECT tagsId FROM blogPostTags WHERE tagsId = :id');
                                        $database->bind(':id', $value['tagsId']);
                                        $database->resultset();

                                        if($database->rowNum == 1)
                                        {
                                            $database->query('DELETE FROM tags WHERE tagsId = :id');
                                            $database->bind(':id', $value['tagsId']);
                                            $database->execute();
                                        }
                                    }
                                }

                                $database->query('DELETE FROM blogPostTags WHERE postId = :id');
                                $database->bind(':id', $_GET['postId']);
                                $database->execute();

                                foreach($tags as $value)
                                {
                                    $database->query('SELECT tagsId FROM tags WHERE name = :name');
                                    $database->bind(':name', $value);
                                    $database->execute();
                                    $result = $database->resultset();

                                    if(!$database->rowNum)
                                    {
                                        $database->query('INSERT INTO tags (name) VALUES(:name)');
                                        $database->bind(':name', $value);
                                        $database->execute();
                                        $tagId = $database->lastInsertId();

                                        $database->query('INSERT INTO blogPostTags (tagsId, postId) VALUES(:tag, :post)');
                                        $database->bind(':tag', $tagId);
                                        $database->bind(':post', $_GET['postId']);
                                        $database->execute();
                                    }
                                    else
                                    {
                                        $database->query('INSERT INTO blogPostTags (tagsId, postId) VALUES(:tag, :post)');
                                        $database->bind(':tag', $result['tagsId']);
                                        $database->bind(':post', $_GET['postId']);
                                        $database->execute();
                                    }
                                }
                            }
                        }
                    }
                }
                else
                {
                    $database->query('UPDATE posts SET title = :title, body = :body WHERE postId = :id');
                    $database->bind(":title", $title);
                    $database->bind(':body', $body);
                    $database->bind(':id', $_GET['postId']);
                    $database->execute();

                    $database->query('SELECT tagsId FROM blogPostTags WHERE postId = :id');
                    $database->bind(':id', $_GET['postId']);
                    $database->execute();
                    $tagIds = $database->resultset();

                    if ($database->rowNum == 1)
                    {
                        $database->query('SELECT tagsId FROM blogPostTags WHERE tagsId = :id');
                        $database->bind(':id', $tagIds['tagsId']);
                        $database->resultset();

                        if($database->rowNum == 1)
                        {
                            $database->query('DELETE FROM tags WHERE tagsId = :id');
                            $database->bind(':id', $tagIds['tagsId']);
                            $database->execute();
                        }
                    }
                    else
                    {
                        foreach($tagIds as $value)
                        {
                            $database->query('SELECT tagsId FROM blogPostTags WHERE tagsId = :id');
                            $database->bind(':id', $value['tagsId']);
                            $database->resultset();

                            if($database->rowNum == 1)
                            {
                                $database->query('DELETE FROM tags WHERE tagsId = :id');
                                $database->bind(':id', $value['tagsId']);
                                $database->execute();
                            }
                        }
                    }

                    $database->query('DELETE FROM blogPostTags WHERE postId = :id');
                    $database->bind(':id', $_GET['postId']);
                    $database->execute();

                    foreach($tags as $value)
                    {
                        $database->query('SELECT tagsId FROM tags WHERE name = :name');
                        $database->bind(':name', $value);
                        $database->execute();
                        $result = $database->resultset();

                        if(!$database->rowNum)
                        {
                            $database->query('INSERT INTO tags (name) VALUES(:name)');
                            $database->bind(':name', $value);
                            $database->execute();
                            $tagId = $database->lastInsertId();

                            $database->query('INSERT INTO blogPostTags (tagsId, postId) VALUES(:tag, :post)');
                            $database->bind(':tag', $tagId);
                            $database->bind(':post', $_GET['postId']);
                            $database->execute();
                        }
                        else
                        {
                            $database->query('INSERT INTO blogPostTags (tagsId, postId) VALUES(:tag, :post)');
                            $database->bind(':tag', $result['tagsId']);
                            $database->bind(':post', $_GET['postId']);
                            $database->execute();
                        }
                    }
                }

                echo "<p style='color: green'>Your post was updated.</p>";
            }
        ?><hr>

        <form enctype="multipart/form-data" method="post">
            <div class="form-group">
                <div class="col-md-2">
                    <label>Post Title</label><br />
                    <input type="text" name="title" placeholder="Add a Title..." value="<?php if(isset($_POST['title'])) echo $_POST['title']; else echo $result['title'];?>" required><br /><br />

                    <div id="tags">
                        <label for="tag2">Tags</label><span style="margin-left: 5px"><button class="btn btn-default btn-xs" id="addTag">+</button></span>

                        <?php
                            $database->query('SELECT name FROM tags LEFT JOIN (blogPostTags) ON (tags.tagsId = blogPostTags.tagsId) WHERE blogPostTags.postId = :inId');
                            $database->bind(':inId', $_GET['postId']);
                            $tagName = $database->resultset();
                            $count = $database->count;

                            if($database->rowNum == 1){
                                echo '<input list="tag" id="tag2" name="tag[]" style="width: 135px" value="' . $tagName['name'] . '" required>';
                            }
                            else
                            {
                                $check = true;
                                $idNumber = 1;
                                foreach($tagName as $name)
                                {
                                    if($check)
                                    {
                                        echo '<input list="tag" id="tag2" name="tag[]" style="width: 135px" value="' . $name['name'] . '" required>';
                                        $check = false;
                                    }
                                    else
                                    {
                                        echo "<input list='tag' id='". $idNumber ."' name='tag[]' style='margin-top: 10px; width: 135px' value='" . $name['name'] . "' required> <button class='btn btn-default btn-xs remove' id='". $idNumber ."button'>-</button>";
                                        $idNumber++;
                                    }
                                }
                            }
                        ?>
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
                    <input type="file" id="screenshot" name="screenshot">
                </div>
                <div class="col-md-10" style="position: relative">
                    <label>Post Body</label><br />
                    <textarea name="body" style="width: 935px; height: 350px" required><?php if(isset($_POST['body'])) echo $_POST['body']; else echo $result['body']; ?></textarea><br /><br /><br>

                    <input class="btn btn-danger" style="position: absolute; bottom: 0; right: 75px" type="submit" id="delete" name="delete" value="Delete" />
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
    $(document).ready(function()
    {
        $('[data-toggle="tooltip"]').tooltip();

        var count = <?= $count ?>;
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

