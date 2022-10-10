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
            <?php
                if (@$_POST['Tab'] == "Logga ut"){
                    Header('Location:index.php');
                }
                if (isset($_POST)){
                    $sql = "SELECT Namn,`Password`,Personnummer,`Admin`,ID FROM anvandare";
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
                if ((!isset($_SESSION['PersonNum'])) && (!isset($_SESSION['Password']))){
                    Header('Location:index.php');
                }
                if (!isset($_POST['Tab'])){
                    $_POST['Tab'] = "Användare";
                }
            ?>
            <div id="navigering">
                <!--<button id="anvknapp">Användare</button>-->
                <form method='post'>
                    <input type='submit' name='Tab' value='Användare' class="knapp" <?php if ($_POST['Tab'] == "Användare"){ echo "id='knapptryck' ";}?>>
                </form>
                <form method='post'>
                    <input type='submit' name='Tab' value='Bok' class="knapp" <?php if ($_POST['Tab'] == "Bok"){ echo "id='knapptryck' ";}?>>
                </form>
                <form method='post'>
                    <input type='submit' name='Tab' value='Författare' class="knapp" <?php if ($_POST['Tab'] == "Författare"){ echo "id='knapptryck' ";}?>>
                </form>
                <form method='post'>
                    <input type='submit' name='Tab' value='Film' class="knapp" <?php if ($_POST['Tab'] == "Film"){ echo "id='knapptryck' ";}?>>
                </form>
                <form method='post'>
                    <input type='submit' name='Tab' value='Regissör' class="knapp" <?php if ($_POST['Tab'] == "Regissör"){ echo "id='knapptryck' ";}?>>
                </form>
                <form method='post'>
                    <input type='submit' name='Tab' value='Logga ut' class="knapp">
                </form>
            </div>
            <?php
                if ($_POST['Tab'] == "Användare"){ // Användare
                    echo "<div id='TabAnvändare' class='Tab'>";
                        echo " <br>";
                        $sql = "SELECT Namn FROM anvandare";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<br>" . $row['Namn'];
                            }
                        }
                    echo "</div>";
                }

                if ($_POST['Tab'] == "Bok"){ // Bok
                    echo "<div id='TabBok' class='Tab'>";
                        echo " <br>";
                        $sql = "SELECT Namn FROM bok";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<br>" . $row['Namn'];
                            }
                        }
                    echo "</div>";
                }

                if ($_POST['Tab'] == "Författare"){ // Författare
                    echo "<div id='TabFörfattare' class='Tab'>";
                        $sql = "SELECT Namn FROM forfattare WHERE Namn LIKE ?";
                        $stmt = $conn->prepare($sql); 
                        $stmt->bind_param("s", $Namn);
                        $Namn = "%".$Namn."%";
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while ($row = $result->fetch_assoc()) {
                            echo "<br>" . $row['Namn'];
                        }
                    echo "</div>";
                }

                if ($_POST['Tab'] == "Film"){ // Film
                    echo "<div id='TabFilm' class='Tab'>";
                        echo " <br>";
                        $sql = "SELECT Titel FROM film";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<br>" . $row['Titel'];
                            }
                        }
                    echo "</div>";
                }

                if ($_POST['Tab'] == "Regissör"){ // Regissör
                    echo "<div id='TabRegissör' class='Tab'>";
                        echo " <br>";
                        $sql = "SELECT Namn FROM regissor";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<br>" . $row['Namn'];
                            }
                        }
                    echo "</div>";
                }
            ?>
        </div>
    </body>
</html>