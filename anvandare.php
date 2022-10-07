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
        <script src="index.js"></script>
    </head>
    <body>
        <div id="anvbg">
            <div id="navigering">
                <!--<button id="anvknapp">Användare</button>-->
                <form method='post'>
                    <input type='submit' name='Tab' value='Användare' style="height:13vh; width:20vw;" id="anvknapp">
                </form>
                <form method='post'>
                    <input type='submit' name='Tab' value='Bok' style="height:13vh; width:20vw;" id="bokknapp"/>
                </form>
                <form method='post'>
                    <input type='submit' name='Tab' value='Författare' style="height:13vh; width:20vw;" id="forknapp">
                </form>
                <form method='post'>
                    <input type='submit' name='Tab' value='Film' style="height:13vh; width:20vw;" id="filmknapp">
                </form>
                <form method='post'>
                    <input type='submit' name='Tab' value='Regissör' style="height:13vh; width:20vw;" id="regknapp">
                </form>
            </div>
            <?php
                if (isset($_POST)){
                    $sql = "SELECT Namn,`Password`,Personnummer FROM anvandare";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            if ((@$_SESSION['PersonNum'] == $row['Personnummer']) && (@$_SESSION['Pass'] == $row['Password'])){
                                echo "Inloggad som " . $row['Namn'];

                            }
                        }
                    }
                }
            ?>
        </div>
    </body>
</html>