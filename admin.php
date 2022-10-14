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
        <title>Bibliotek admin</title>
        <link rel="stylesheet" href="index.css">
    </head>
    <body id="adminbg">
        <div class="loggaut">
            <?php
                if (isset($_POST)){
                    $sql = "SELECT ID,Namn,`Password`,Personnummer,`Admin` FROM anvandare";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            if ((@$_SESSION['PersonNum'] == $row['Personnummer']) && (@$_SESSION['Pass'] == $row['Password'])){
                                echo "Inloggad som: " . $row['Namn'];
                                $Admin = 1;
                                $AnvID = $row['ID'];
                                if ($row['Admin'] != 1){
                                    Header('Location:index.php');
                                }
                            }
                        }
                    }
                }
                if($Admin != 1){
                    Header('Location:index.php');
                }
                if (@$_POST['Tab'] == "Logga ut"){
                    Header('Location:index.php');
                }
            ?>
            <form method='post'>
                <input type='submit' name='Tab' value='Logga ut' id="logut">
            </form>
        </div>

        <div class="meny">
            <div id="Bok"> <br> ----- Bok -----
                <?php
                    if (isset($_POST['Bok'])) {
                        $sql = $conn->prepare("INSERT INTO bok (ISBN,Namn,LjudBok,ReferensBok) VALUES (?,?,?,?)");
                        $sql->bind_param("ssbb", $ISBN,$Namn,$Ljudbok,$Referensbok);

                        $ISBN = $_POST['ISBN'];
                        $Namn = $_POST['BokNamn'];
                        if (Isset($_POST['Ljudbok'])){
                            $Ljudbok = 1;
                        }
                        else{
                            $Ljudbok = 0;
                        }

                        if (Isset($_POST['Referensbok'])){
                            $Referensbok = 1;
                        }
                        else{
                            $Referensbok = 0;
                        }
                        $sql->execute();
                        $sql->close();

                        Header('Location:admin.php');
                    }
                ?>
                <?php
                    echo "<form method = 'post' class='Bok'>";
                        echo "<input type='hidden' name='Bok' class='Bok'>";
                        echo "Bok namn: <br><input type='text' name='BokNamn' required='require' class='Text'><br>";
                        echo "Ljudbok: <br><input type='checkbox' name='Ljudbok' class='Bok'><br>";
                        echo "Referensbok: <br><input type='checkbox' name='ReferensBok' class='Bok'><br>";
                        echo "ISBN: <br><input type='text' name='ISBN' required='require' class='Text'><br>";
                        echo "<input type='submit' value='Submit' class='Text'>";
                    echo "</form>";
                ?>

                <?php
                    echo " <br>";
                    $sql = "SELECT Namn,ISBN FROM bok";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<br>" . $row['Namn'];
                            $ISBN = $row['ISBN'];
                            echo "<form method='post' action='delete.php'>";
                                echo "<input type='hidden' name='Bok' value='$ISBN'>";
                                ?>
                                <input type='submit' value='Delete' class='Text' onclick='return confirm("Are you sure?")'>
                                <?php
                            echo "</form>";
                        }
                    }
                ?>
                <br>
            </div><br> <!-- ----- Bok ----- -->

            <div id="BokFor"> <br> ----- Bok + Författare -----
                <?php
                    if (isset($_POST['BokFor'])){
                        $Bok = $_POST['BokFor'];
                        echo $Bok . "<br>";
                        $values = $_POST['Forf'];
                        foreach ($values as $a){
                            echo $a . "<br>";

                            $sql = "INSERT INTO bokfor (ISBN, FID) VALUES ('$Bok', '$a')";

                            if ($conn->query($sql) === TRUE) {
                            } 
                            else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                            }
                            Header('Location:admin.php');
                        }
                    }
                ?>
                <?php
                    echo "<form method='post' class='BokFor'>";
                        echo "Bok: <br><input type='text' list='bok' name='ValdBok' required autocomplete='off' class='Text'>";
                            echo "<datalist id='bok'>";
                                if (isset($_POST['ValdBok'])){
                                    $ValdBok = $_POST['ValdBok'];
                                    $sql = "SELECT Namn,ISBN FROM bok WHERE bok.ISBN != $ValdBok";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            $ISBN = $row['ISBN'];
                                            $Namn = $row['Namn'];
                                            echo "<option value='$ISBN' >$Namn</option>";
                                        }
                                    }
                                    $sql = "SELECT Namn,ISBN FROM bok WHERE bok.ISBN = $ValdBok";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            $ISBN = $row['ISBN'];
                                            $NamnVal = $row['Namn'];
                                            echo "<option value='$ISBN'>$NamnVal</option>";
                                        }
                                    }
                                }
                                else{
                                    $sql = "SELECT Namn,ISBN FROM bok";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            $ISBN = $row['ISBN'];
                                            $Namn = $row['Namn'];
                                            echo "<option value='$ISBN'>$Namn</option>";
                                        }
                                    }
                                } 
                            echo "</datalist>";
                        echo "</input><br>";
                        echo "<input type='submit' value='Välj bok' class='Text'/>";
                    echo "</form>";

                    if (isset($_POST['ValdBok'])){
                        $ValdBok = $_POST['ValdBok'];
                        echo $NamnVal;
                        echo "<form method='post'>";
                            echo "<input type='hidden' name='BokFor' value='$ValdBok'>";
                            echo "Författare: <br><select name='Forf[]' id='test' multiple='multiple' required='require'>";
                                $sql = "SELECT forfattare.Namn,forfattare.ID,bokfor.ISBN FROM forfattare LEFT JOIN bokfor ON forfattare.ID = bokfor.FID AND bokfor.ISBN = $ValdBok";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        $ID = $row['ID'];
                                        $Namn = $row['Namn'];
                                        if ($row['ISBN'] == NULL){
                                            echo "<option value='$ID'>$Namn</option>";
                                        }
                                    } 
                                } 
                            echo "</select><br>";
                            echo "<input type='submit' value='Submit the form' class='Text'/>";
                        echo "</form>";
                    } 

                ?>
                <?php
                    echo " <br>";

                    $sql = "SELECT bok.Namn AS BokNamn, forfattare.Namn AS ForNamn FROM bok,forfattare,bokfor WHERE bok.ISBN = bokfor.ISBN AND forfattare.ID = bokfor.FID";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            if (@$BokNamn == $row['BokNamn']){
                            }
                            else{
                                echo "<br>". $row['BokNamn']."<br>";
                            }
                            $BokNamn = $row['BokNamn'];
                            echo $row['ForNamn']."<br>";
                        }
                    }
                ?>
                <br>
            </div><br> <!-- ----- Bok + Författare ----- -->

            <div id="Författare"> <br> ----- Författare -----
                <?php
                    if (isset($_POST['Forfattare'])) {
                        $sql = $conn->prepare("INSERT INTO forfattare (Namn) VALUE (?)");
                        $sql->bind_param("s", $Namn);


                        $Namn = $_POST['ForfattarNamn'];
                        $sql->execute();
                        $sql->close();

                        Header('Location:admin.php');
                    }
                ?>
                <?php
                    echo "<form method = 'post' class='Författare'>";
                        echo "<input type='hidden' name='Forfattare' class='Text'>";
                        echo "<input type='text' name='ForfattarNamn' required='require' class='Text'><br>";
                        echo "<input type='submit' value='Submit' class='Text'>";
                    echo "</form>";
                ?>
                <?php
                    echo "<br>";
                    $sql = "SELECT Namn FROM forfattare";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo $row['Namn']."<br>";
                        }
                    }
                ?>
                <br>
            </div> <!-- ----- Författare ----- -->

            <div id="Film"> <br> ----- Film -----
                <?php
                    if (isset($_POST['Film'])) {
                        $sql = $conn->prepare("INSERT INTO film (Titel,Langd) VALUES (?,?)");
                        $sql->bind_param("ss", $Titel,$Langd);

                        $Titel = $_POST['FilmTitel'];
                        $Langd = $_POST['Langd'];

                        $sql->execute();
                        $sql->close();

                        Header('Location:admin.php');
                    }
                ?>
                <?php
                    echo "<form method = 'post' class='Film'>";
                        echo "<input type='hidden' name='Film' class='Film'>";
                        echo "Film namn: <br><input type='text' name='FilmTitel' required='require' class='Text'><br>";
                        echo "Längd: <br><input type='time' name='Langd' required='require' class='Text'><br>";
                        echo "<input type='submit' value='Submit' class='Text'>";
                    echo "</form>";
                ?>

                <?php
                    echo " <br>";
                    $sql = "SELECT Titel FROM film";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo $row['Titel']."<br>";
                        }
                    }
                ?>
                <br>
            </div><br> <!-- ----- Film ----- -->

            <div id="FilmReg"> <br> ----- Film + Regissör -----
                <?php
                    if (isset($_POST['FilmReg'])){
                        $Film = $_POST['FilmReg'];
                        echo $Film . "<br>";
                        $values = $_POST['Reg'];
                        foreach ($values as $a){
                            echo $a . "<br>";

                            $sql = "INSERT INTO bokfor (ISBN, FID) VALUES ('$Film', '$a')";

                            if ($conn->query($sql) === TRUE) {
                            } 
                            else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                            }
                            Header('Location:admin.php');
                        }
                    }
                ?>
                <?php
                    echo "<form method='post' class='FilmReg'>";
                        echo "Film: <br><input type='text' list='film' name='ValdFilm' required autocomplete='off' class='Text'>";
                            echo "<datalist id='film'>";
                                if (isset($_POST['ValdFilm'])){
                                    $ValdFilm = $_POST['ValdFilm'];
                                    $sql = "SELECT Titel,ID FROM film WHERE film.ID != $ValdFilm";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            $ID = $row['ID'];
                                            $Titel = $row['Titel'];
                                            echo "<option value='$ID' >$Titel</option>";
                                        }
                                    }
                                    $sql = "SELECT Titel,ID FROM film WHERE film.ID = $ValdFilm";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            $ID = $row['ID'];
                                            $NamnVal = $row['Titel'];
                                            echo "<option value='$ID'>$NamnVal</option>";
                                        }
                                    }
                                }
                                else{
                                    $sql = "SELECT Titel,ID FROM film";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            $ID = $row['ID'];
                                            $Titel = $row['Titel'];
                                            echo "<option value='$ID'>$Titel</option>";
                                        }
                                    }
                                } 
                            echo "</datalist>";
                        echo "</input><br>";
                        echo "<input type='submit' value='Välj film' class='Text'/>";
                    echo "</form>";

                    if (isset($_POST['ValdFilm'])){
                        $ValdFilm = $_POST['ValdFilm'];
                        echo $NamnVal;
                        echo "<form method='post'>";
                            echo "<input type='hidden' name='FilmReg' value='$ValdFilm'>";
                            echo "Regissör: <br><select name='Reg[]' id='test' multiple='multiple' required='require'>";
                                $sql = "SELECT regissor.Namn,regissor.ID,filmreg.FID FROM regissor LEFT JOIN filmreg ON regissor.ID = filmreg.RID AND film.FID = $ValdBok";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        $ID = $row['FID'];
                                        $Namn = $row['Namn'];
                                        if ($row['ID'] == NULL){
                                            echo "<option value='$ID'>$Namn</option>";
                                        }
                                    } 
                                } 
                            echo "</select><br>";
                            echo "<input type='submit' value='Submit the form' class='Text'/>";
                        echo "</form>";
                    } 

                ?>
                <?php
                    echo " <br>";

                    $sql = "SELECT film.Titel AS FilmNamn, regissor.Namn AS RegNamn FROM film,regissor,filmreg WHERE film.ID = filmreg.FID AND regissor.ID = filmreg.RID";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            if (@$FilmNamn == $row['FilmNamn']){
                            }
                            else{
                                echo "<br>". $row['FilmNamn']."<br>";
                            }
                            $FilmNamn = $row['FilmNamn'];
                            echo $row['RegNamn']."<br>";
                        }
                    }
                ?>
                <br>
            </div><br> <!-- ----- Film + Regissör ----- -->

            <div id="Regissör"> <br> ----- Regissör -----
                <?php
                    if (isset($_POST['Regissor'])) {
                        $sql = $conn->prepare("INSERT INTO regissor (Namn) VALUE (?)");
                        $sql->bind_param("s", $Namn);

                        $Namn = $_POST['RegissorNamn'];
                        $sql->execute();
                        $sql->close();

                        Header('Location:admin.php');
                    }
                ?>
                <?php
                    echo "<form method = 'post' class='Regissör'>";
                        echo "<input type='hidden' name='Regissor' class='Regissör'>";
                        echo "<input type='text' name='RegissorNamn' required='require' class='Text'><br>";
                        echo "<input type='submit' value='Submit' class='Text'>";
                    echo "</form>";
                ?>
                <?php
                    echo "<br>";
                    $sql = "SELECT Namn FROM regissor";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo $row['Namn']."<br>";
                        }
                    }
                ?>
                <br>
            </div> <!-- ----- Regissör----- -->

            <div id="Lana"> <br> ----- Låna -----
                <?php
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
                        // Header('Location:admin.php',true);
                    }
                    echo "<form method='post'>";
                        echo "Böcker: <input type='radio' name='ExemplarVal' value='1' required/>";
                        echo "Filmer: <input type='radio' name='ExemplarVal' value='2' required/>";
                        echo "<br><input type='submit' value='Sortera' class='Text'>";
                    echo "</form>";

                    if (!isset($_POST['ExemplarVal'])){    
                        $_POST['ExemplarVal'] = 1;
                    }
                    $ExemplarVal = $_POST['ExemplarVal'];

                    echo "<form method='post' class='Bokfor2'>";
                        echo "<input type='hidden' value='$ExemplarVal' name='ExemplarVal'>";
                        echo "<input type='hidden' value='1' name='ExemplarVal2'>";
                        echo "Låna: <br><input type='text' list='exemplar' name='ValtExemplar' required autocomplete='off' class='Text'>";
                            echo "<datalist id='exemplar'>";
                                if($_POST['ExemplarVal'] == 1){
                                    $sql = "SELECT DISTINCT Namn,bok.ISBN FROM bok INNER JOIN exemplar ON bok.ISBN = exemplar.ISBN";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            $ISBN = $row['ISBN'];
                                            $Namn = $row['Namn'];
                                            echo "<option value='$ISBN' label='$Namn'></option>";
                                        }
                                    }
                                }
                                elseif ($_POST['ExemplarVal'] == 2){
                                    $sql = "SELECT Titel,ID FROM film";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            $ID = $row['ID'];
                                            $Titel = $row['Titel'];
                                            echo "<option value='$ID' >$Titel</option>";
                                        }
                                    }
                                }
                            echo "</datalist>";
                        echo "</input><br>";
                        echo "<input type='submit' value='Välj bok' class='Text'/>";
                    echo "</form>";
                    if(($_POST['ExemplarVal'] == 1) && (@$_POST['ExemplarVal2'] == 1)){
                        $ValtExemplar = $_POST['ValtExemplar'];
                        $sql = "SELECT bok.Namn AS Namn,exemplar.ID FROM `exemplar` INNER JOIN `bok` ON $ValtExemplar = exemplar.ISBN AND $ValtExemplar = bok.ISBN ORDER BY `exemplar`.`ID` ASC;";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo $row['ID']." ".$row['Namn']."<br>";
                                $EID = $row['ID'];
                                $sql2 = "SELECT lan.Inlamnad FROM `lan`,`exemplar` WHERE $EID = lan.EID ORDER BY lan.Inlamnad ASC;";
                                $result2 = $conn->query($sql2);
                                if ($result2->num_rows > 0) {
                                    while($row2 = $result2->fetch_assoc()) {
                                        if ($row2['Inlamnad'] != 1){
                                                echo "<button>Utånad</button><br>";
                                            break;
                                        }
                                        else{
                                            echo "<form method='post'>";
                                                echo "<input type='hidden' name='ExemplarVal' value='$ExemplarVal'>";
                                                echo "<input type='hidden' name='ValtExemplar' value='$ValtExemplar'>";
                                                echo "<input type='hidden' value='1' name='ExemplarVal2'>";
                                                echo "<input type='hidden' value='$EID' name='GörLån'>";
                                                echo "<input type='submit' value='Låna'>";
                                            echo "</form>";
                                            break;
                                        }
                                    }
                                }
                                else {
                                    echo "<form method='post'>";
                                        echo "<input type='hidden' name='ExemplarVal' value='$ExemplarVal'>";
                                        echo "<input type='hidden' name='ValtExemplar' value='$ValtExemplar'>";
                                        echo "<input type='hidden' value='1' name='ExemplarVal2'>";
                                        echo "<input type='hidden' value='$EID' name='GörLån'>";
                                        echo "<input type='submit' value='Låna'>";
                                    echo "</form>";
                                }
                            }
                        }
                    }
                    if(($_POST['ExemplarVal'] == 2) && (@$_POST['ExemplarVal2'] == 1)){
                        $ValtExemplar = $_POST['ValtExemplar'];
                        $sql = "SELECT film.Titel AS Titel,exemplar.ID FROM `exemplar` INNER JOIN `film` ON $ValtExemplar = exemplar.FID AND $ValtExemplar = film.ID ORDER BY `exemplar`.`ID` ASC;";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo $row['ID']." ".$row['Titel']."<br>";
                                $EID = $row['ID'];
                                $sql2 = "SELECT lan.Inlamnad FROM `lan`,`exemplar` WHERE $EID = lan.EID ORDER BY lan.Inlamnad ASC;";
                                $result2 = $conn->query($sql2);
                                if ($result2->num_rows > 0) {
                                    while($row2 = $result2->fetch_assoc()) {
                                        if ($row2['Inlamnad'] != 1){
                                                echo "<button>Utånad</button><br>";
                                            break;
                                        }
                                        else{
                                            echo "<form method='post'>";
                                                echo "<input type='hidden' name='ExemplarVal' value='$ExemplarVal'>";
                                                echo "<input type='hidden' name='ValtExemplar' value='$ValtExemplar'>";
                                                echo "<input type='hidden' value='1' name='ExemplarVal2'>";
                                                echo "<input type='hidden' value='$EID' name='GörLån'>";
                                                echo "<input type='submit' value='Låna'>";
                                            echo "</form>";
                                            break;
                                        }
                                    }
                                }
                                else {
                                    echo "<form method='post'>";
                                        echo "<input type='hidden' name='ExemplarVal' value='$ExemplarVal'>";
                                        echo "<input type='hidden' name='ValtExemplar' value='$ValtExemplar'>";
                                        echo "<input type='hidden' value='1' name='ExemplarVal2'>";
                                        echo "<input type='hidden' value='$EID' name='GörLån'>";
                                        echo "<input type='submit' value='Låna'>";
                                    echo "</form>";
                                }
                            }
                        }
                    }
                ?>
                <br>
            </div><br> <!-- ----- Låna ----- -->

            <div id="Exemplar"> <br> ---- Exemplar ----
                <?php
                    echo "<form method='post'>";
                        echo "<input type='hidden' name='Tab', value='Bok'>";
                        echo "<input type='text' name='VisaBok' class='Text'>";
                        echo "<input type='submit' value='Sök' class='Text'>";
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
                            echo "<br>" . $row['Namn'];
                            echo "<form method='post' action='exemplar.php'>";
                                echo "<input type='hidden' name='ISBN', value='$ISBN'>";
                                echo "<input type='submit' value='Lägg till nytt exemplar' class='Text'>";
                            echo "</form>";
                            echo " ----------- <br>";

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
                                                            echo "<br>";
                                                            $Din = 1;
                                                            break;
                                                        }
                                                    }
                                                }
                                                if ($Din == 1){
                                                    echo "<br>";
                                                    break;
                                                }
                                                echo "<br>";
                                                break;
                                            }
                                            else{
                                                echo "<br>";
                                                break;
                                            }
                                        }
                                    }
                                    else{
                                        echo "<br>";
                                    }
                                }
                            }
                            else{
                                echo "Inga exemplar <br>";
                            }
                        }
                    }
        
                ?>
            </div><br> <!-- Exemplar -->
        </div>
    </body>
</html>

