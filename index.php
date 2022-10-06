<?php
    session_start();
    require_once('db.php');
    $_SESSION['PersonNum'] = NULL;
    $_SESSION['Pass'] = NULL;
    $Fel = 1;
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
        <div id="loginbg">
            <?php
                if (isset($_POST['PersonNum'])){
                    $sql = "SELECT Namn,`Password`,Personnummer FROM anvandare";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            if ((isset($_POST['PersonNum'])) && ($_POST['PersonNum'] == $row['Personnummer']) && ($_POST['Pass'] == $row['Password'])){
                                $_SESSION['PersonNum'] = $row['Personnummer'];
                                $_SESSION['Pass'] = $row['Password'];
                                $Fel = 0;
                                Header('Location:anvandare.php');
                            }
                        }
                    }
                if ((isset($_POST['Fel']) == 1) && ($Fel == 1)){
                    $_SESSION['Fel'] = 1;
                    Header('Location:index.php');
                }
                }
            ?>

            <?php
                if ($_SESSION['Fel'] === 1){
                    echo "Fel";
                }
                if (isset($_POST['Fel']) == 0){
                    $_SESSION['Fel'] = 0;
                }
            ?>

            <form method='post' class="Loginbox">
                <input type='hidden' name='Fel' value='1'>
                Personnummner:<input type='text' name='PersonNum' required placeholder='YYYYMMDDXXXX' minlength='12' maxlength='12' class="Text"><br>
                Lösenord:<input type='password' name='Pass' required class="Text"><br>
                <input type='submit' value='Logga in' class="Text">
            </form>
            <form method = 'post' action='registrera.php'>
                <input type='submit' value='Registrera dig!' class="Text">
            </form>    
        </div>
    </body>
</html>