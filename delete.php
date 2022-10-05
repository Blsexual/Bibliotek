<?php
    require_once('db.php');
    session_start();
?>

<?php
    if (isset($_POST['Bok'])){
        $ISBN = $_POST['Bok'];
        $sql = "DELETE FROM bok WHERE bok.ISBN = '$ISBN'";
        $conn->query($sql);

        header('Location:index.php');
    }
?>