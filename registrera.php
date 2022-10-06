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
        <?php
            if(@$_SESSION['PerFel'] == 1){
                echo "Personnumret finns redan registrerat";
                $_SESSION['PerFel'] = 0;
            }
            if(@$_SESSION['PassFel'] == 1){
                echo "Lösenorden stämmer inte";
                $_SESSION['PassFel'] = 0;
            }
        ?>
        <?php
            if (isset($_POST)){
                $sql = "SELECT Personnummer FROM anvandare";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        if (@$_POST['PersonNum'] == $row['Personnummer']){
                            $_SESSION['PerFel'] = 1;  
                            Header('Location:registrera.php');
                        }
                    }
                }
                if ((@$_POST['Pass1'] != @$_POST['Pass2']) && ($_SESSION['PerFel'] != 1)){
                    $_SESSION['PassFel'] = 1;  
                    Header('Location:registrera.php');
                }
                if ((@$_SESSION['PerFel'] != 1) && (@$_SESSION['PassFel'] != 1) && (@$_POST['Fel'] == 1)){
                    $sql = $conn->prepare("INSERT INTO anvandare (Namn,`Password`,Personnummer) VALUES (?,?,?)");
                    $sql->bind_param("sss", $Namn,$Pass,$Pers);

                    $Namn = $_POST['Namn'];
                    $Pass = $_POST['Pass1'];
                    $Pers = $_POST['PersonNum'];

                    $sql->execute();
                    $sql->close();
                    
                    $_SESSION['PersonNum'] = $Pers;
                    $_SESSION['Pass'] = $Pass;
                    Header('Location:anvandare.php');
                }
            }
        ?>
        <form method='post'>
            <input type='hidden' name='Fel' value='1'>
            Namn:<input type='text' name='Namn' required><br>
            Personnummner:<input type='text' name='PersonNum' required placeholder='YYYYMMDDXXXX' minlength='12' maxlength='12'><br>
            Lösenord:<input type='password' name='Pass1' required minlength='6' maxlength='30'><br>
            Verifiera lösenord:<input type='password' name='Pass2' required minlength='6' maxlength='30'><br>
            <input type='submit' value='Registrera'>
        </form>





















































    </body>
</html>
