<?php
    require 'classes/database.php';

    $database = new Database;
    $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    $database->query('SELECT * FROM posts');
    $rows = $database->resultset();
    //print_r($rows);

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

     if(@$post['submit'])
     {
         $title = $post['title'];
         $body = $post['body'];
         $id = $post['id'];

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

     $database->query('SELECT * FROM posts');
    ?>

    <h1>Add Post</h1>
     <form method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
         <label>Post ID</label><br>
         <input type="text" name="id" placeholder="Specify ID"><br><br>

        <label>Post Title</label><br />
        <input type="text" name="title" placeholder="Add a Title..." /><br /><br />

        <label>Post Body</label><br />
        <textarea name="body"></textarea><br /><br />

        <input type="submit" name="submit" value="Submit" />
        <input type="submit" name="update" value="Update">
     </form>

    <h1>Posts</h1>
    <div>
        <?php foreach($rows as $row) { ?>
        <div>
            <h3><?= $row['title']; ?></h3>
            <p><?= $row['body']; ?></p>
            <br>

            <form method="post" action="<?= $_SERVER['PHP_SELF'];?>">
                <input type="hidden" name="delete_id" value="<?= $row['id']; ?>">
                <input type="submit" name = "delete" value="Delete">
            </form>
        </div>
      <?php } ?>
    </div>