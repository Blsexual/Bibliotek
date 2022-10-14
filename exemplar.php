<?php
    session_start();
    require_once('db.php');
?>

<?php
    $ISBN = $_POST['ISBN']; 
    print_r($_POST);

    $sql = "INSERT INTO `exemplar` (`ID`, `FID`, `ISBN`) VALUES (NULL, NULL, '$ISBN')";
    echo $sql;
    $result = $conn->query($sql);
    Header("Location:admin.php");

?>