<?php
    session_start();
    require_once('db.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Bibliotek</title>
        <link rel="stylesheet" href="index.css">
    </head>
    <body>

        <?php
            if (isset($_POST)){
                $sql = "SELECT Namn,`Password`,Personnummer,`Admin` FROM anvandare";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        if ((@$_SESSION['PersonNum'] == $row['Personnummer']) && (@$_SESSION['Pass'] == $row['Password'])){
                            echo "Inloggad som " . $row['Namn'];
                            if ($row['Admin'] == 1){
                                Header('Location:admin.php');
                            }
                        }
                    }
                }
            }
        ?>
    </body>
</html>