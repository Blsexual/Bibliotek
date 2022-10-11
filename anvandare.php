<?php
    session_start();
    require_once('db.php');
    $x = '"eeee"';
    $Din = NULL;
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
                                $AnvID = $row['ID'];
                                // if ($row['Admin'] == 1){
                                //     Header('Location:admin.php');
                                // }
                            }
                        }
                    }
                }
                if ((!isset($_SESSION['PersonNum'])) && (!isset($_SESSION['Password']))){
                    Header('Location:index.php');
                }
                if (isset($_POST['Tab'])){
                    $_SESSION['Tab'] = $_POST['Tab'];
                }
            ?>
            <div id="navigering">
                <!--<button id="anvknapp">Användare</button>-->
                <form method='post'>
                    <input type='submit' name='Tab' value='Användare' class="knapp" <?php if ($_SESSION['Tab'] == "Användare"){ echo "id='knapptryck' ";}?>>
                </form>
                <form method='post'>
                    <input type='submit' name='Tab' value='Bok' class="knapp" <?php if ($_SESSION['Tab'] == "Bok"){ echo "id='knapptryck' ";}?>>
                </form>
                <form method='post'>
                    <input type='submit' name='Tab' value='Författare' class="knapp" <?php if ($_SESSION['Tab'] == "Författare"){ echo "id='knapptryck' ";}?>>
                </form>
                <form method='post'>
                    <input type='submit' name='Tab' value='Film' class="knapp" <?php if ($_SESSION['Tab'] == "Film"){ echo "id='knapptryck' ";}?>>
                </form>
                <form method='post'>
                    <input type='submit' name='Tab' value='Regissör' class="knapp" <?php if ($_SESSION['Tab'] == "Regissör"){ echo "id='knapptryck' ";}?>>
                </form>
                <form method='post'>
                    <input type='submit' name='Tab' value='Logga ut' class="knapp" onclick='return confirm("Är du säker på att du vill logga ut?")'>
                </form>
            </div>
            <?php
                if ($_SESSION['Tab'] == "Användare"){ // Användare
                    echo "<div id='TabAnvändare' class='Tab'>";
                        $sql = "SELECT DISTINCT lan.EID AS EID FROM lan INNER JOIN exemplar ON lan.AID = $AnvID AND Inlamnad = 0";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $EID = $row['EID'];
                                echo $row['EID'] . " ";
                                $sql2 = "SELECT bok.Namn FROM bok INNER JOIN exemplar ON bok.ISBN = exemplar.ISBN AND exemplar.ID = $EID";
                                $result2 = $conn->query($sql2);
                                if ($result2->num_rows > 0) {
                                    while($row2 = $result2->fetch_assoc()) {
                                        echo $row2['Namn'];
                                        echo "<form method='post' action='lamnain.php'>";
                                            echo "<input type='hidden' name='EID', value='$EID'>";
                                            echo "<input type='submit' value='Lämna in'>";
                                        echo "</form>";
                                        echo "<br>";

                                    }
                                }
                            }
                        }
                        else{
                            echo "Du har inget lånat";
                        }
                    echo "</div>";
                }
                echo "<div id='BokTab'>";
                    if ($_SESSION['Tab'] == "Bok"){ // Bok

                        if (isset($_POST['GörLån'])){
                            $Day = date('d');
                            $Month = date('m');
                            $Month2 = $Month + 1;
                            $Year = date('Y');
                            $Year2 = $Year;
                            if ($Month2 == 13){
                                $Year2 = $Year + 1;
                                $Month2 = 1;
                            }
                            $StartD = $Year ."-". $Month ."-". $Day;
                            $SlutD = $Year2 ."-". $Month2 ."-". $Day;
                            $EID = $_POST['GörLån'];
                            $sql = "INSERT INTO lan (AID,EID,StartD,SlutD) VALUES ($AnvID,$EID,'$StartD','$SlutD')";
                            $result = $conn->query($sql);
                            Header("Location:anvandare.php");
                        }

                        echo "<div id='TabBok' class='Tab'>";
                            echo "<form method='post'>";
                                echo "<input type='hidden' name='Tab', value='Bok'>";
                                echo "<input type='text' name='VisaBok'>";
                                echo "<input type='submit' value='Sök'>";
                            echo "</form>";

                            if (!isset($_POST['VisaBok'])){
                                $_POST['VisaBok'] = "";
                            }

                            if (isset($_POST['VisaBok'])){
                                $sql = "SELECT DISTINCT bok.Namn,bok.ISBN FROM bok INNER JOIN exemplar ON bok.Namn LIKE ? AND bok.ISBN = exemplar.ISBN";
                                $stmt = $conn->prepare($sql); 
                                $stmt->bind_param("s", $Namn);
                                $Namn = $_POST['VisaBok'];
                                $Namn = "%".$Namn."%";
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while ($row = $result->fetch_assoc()) {
                                    $Num = 0;
                                    $ISBN = $row['ISBN'];
                                    echo "<br>" . $row['Namn'] . "<br> ----------- <br>";

                                    $sql2 = "SELECT bok.Namn AS Namn,exemplar.ID FROM `exemplar` INNER JOIN `bok` ON $ISBN = exemplar.ISBN AND $ISBN = bok.ISBN ORDER BY `exemplar`.`ID` ASC";
                                    $result2 = $conn->query($sql2);
                                    if ($result2->num_rows > 0) {
                                        while($row2 = $result2->fetch_assoc()) {
                                            $Num += 1;
                                            echo $Num . " " . $row2['Namn'];
                                            $EID = $row2['ID'];

                                            $sql3 = "SELECT lan.Inlamnad FROM `lan`,`exemplar` WHERE $EID = lan.EID ORDER BY lan.Inlamnad ASC;";
                                            $result3 = $conn->query($sql3);
                                            if ($result3->num_rows > 0) {
                                                while($row3 = $result3->fetch_assoc()) {
                                                    $Din = 0;
                                                    if ($row3['Inlamnad'] != 1){
                                                        $sql4 = "SELECT DISTINCT lan.EID AS EID FROM lan INNER JOIN exemplar ON lan.AID = $AnvID AND Inlamnad = 0";
                                                        $result4 = $conn->query($sql4);
                                                        if ($result4->num_rows > 0) {
                                                            while($row4 = $result4->fetch_assoc()) {
                                                                // echo $row4['EID'];
                                                                if ($EID == $row4['EID']){
                                                                    echo "<form method='post' action='lamnain.php'>";
                                                                        echo "<input type='hidden' name='EID', value='$EID'>";
                                                                        echo "<input type='submit' value='Lämna in'>";
                                                                    echo "</form>";
                                                                    $Din = 1;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        if ($Din == 1){
                                                            break;
                                                        }
                                                        echo "<br><button>Utlånad</button><br>";
                                                        break;
                                                    }
                                                    else{
                                                        echo "<form method='post'>";
                                                            echo "<input type='hidden' value='$EID' name='GörLån'>";
                                                            echo "<input type='submit' value='Låna' onclick='return confirm($x)'>";
                                                        echo "</form>";
                                                        break;
                                                    }
                                                }
                                            }
                                            else{
                                                echo "<form method='post'>";
                                                    echo "<input type='hidden' value='$EID' name='GörLån'>";
                                                    echo "<input type='submit' value='Låna' onclick='return confirm($x)'>";
                                                echo "</form>";
                                            }
                                        }
                                    }
                                    else{
                                        echo "Inga exemplar <br>";
                                    }
                                }
                            }
                        echo "</div>";
                    }
                echo "</div>";

                if ($_SESSION['Tab'] == "Författare"){ // Författare
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

                if ($_SESSION['Tab'] == "Film"){ // Film
                    echo "<div id='TabFilm' class='Tab'>";
                        echo "<form method='post'>";
                            echo "<input type='hidden' name='Tab', value='Film'>";
                            echo "<input type='text' name='VisaFilm'>";
                            echo "<input type='submit' value='Sök'>";
                        echo "</form>";
                        if (!isset($_POST['VisaFilm'])){
                            $_POST['VisaFilm'] = "";
                        }
                        if (isset($_POST['VisaFilm'])){
                            $sql = "SELECT DISTINCT film.Titel,film.ID FROM film INNER JOIN exemplar ON film.Titel LIKE ? AND film.ID = exemplar.FID";
                            $stmt = $conn->prepare($sql); 
                            $stmt->bind_param("s", $Titel);
                            $Titel = $_POST['VisaFilm'];
                            $Titel = "%".$Titel."%";
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while ($row = $result->fetch_assoc()) {
                                $Num = 0;
                                $ID = $row['ID'];
                                echo "<br><br>" . $row['Titel'] . "<br> ----------- <br>";

                                $sql2 = "SELECT film.Titel AS Titel,exemplar.ID FROM `exemplar` INNER JOIN `film` ON $ID = exemplar.FID AND $ID = film.ID ORDER BY `exemplar`.`ID` ASC";
                                $result2 = $conn->query($sql2);
                                if ($result2->num_rows > 0) {
                                    while($row2 = $result2->fetch_assoc()) {
                                        $Num += 1;
                                        echo $Num . " " . $row2['Titel'];
                                        $EID = $row2['ID'];

                                        $sql3 = "SELECT lan.Inlamnad FROM `lan`,`exemplar` WHERE $EID = lan.EID ORDER BY lan.Inlamnad ASC;";
                                        $result3 = $conn->query($sql3);
                                        if ($result3->num_rows > 0) {
                                            while($row3 = $result3->fetch_assoc()) {
                                                if ($row3['Inlamnad'] != 1){
                                                    echo "<button>Utlånad</button><br>";
                                                    break;
                                                }
                                                else{
                                                    echo "<button>Låna</button><br>";
                                                    break;
                                                }
                                            }
                                        }
                                        else{
                                            echo "<button>Låna</button><br>";
                                        }
                                    }
                                }
                                else{
                                    echo "Inga exemplar <br>";
                                }
                            }
                        }
                    echo "</div>";
                }

                if ($_SESSION['Tab'] == "Regissör"){ // Regissör
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