<?php
    session_start();
    require_once('db.php');
?>

<?php
    if (isset($_POST['Bok'])){
        ?>
            <a href="deletelink" onclick="return confirm('Are you sure?')">Delete</a>
        <?php
        $ISBN = $_POST['Bok'];
        $sql = "DELETE FROM bok WHERE bok.ISBN = '$ISBN'";
        $conn->query($sql);

        header('Location:admin.php');
    }
?>