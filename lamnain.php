<?php
    session_start();
    require_once('db.php');
?>
<?php
    $EID = $_POST['EID']; 
    $sql = "SELECT lan.ID FROM lan WHERE lan.EID = $EID AND Inlamnad = 0";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $ID = $row['ID'];
            echo $row['ID'];
            $sql = "UPDATE lan SET Inlamnad = 1 WHERE ID = $ID AND Inlamnad != 1";
            $result = $conn->query($sql);
            Header("Location:anvandare.php");
        }
    }
?>