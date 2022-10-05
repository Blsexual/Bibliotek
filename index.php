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
        require_once('db.php');
        session_start();
    ?>


    <div> <br> ----- Bok -----
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

                Header('Location:index.php');
            }
        ?>
        <?php
            echo "<form method = 'post'>";
                echo "<input type='hidden' name='Bok'>";
                echo "Bok namn: <br><input type='text' name='BokNamn' required='require'><br>";
                echo "Ljudbok: <br><input type='checkbox' name='Ljudbok'><br>";
                echo "Referensbok: <br><input type='checkbox' name='ReferensBok'><br>";
                echo "ISBN: <br><input type='text' name='ISBN' required='require'><br>";
                echo "<input type='submit' value='Submit'>";
            echo "</form>";
        ?>

        <?php
            echo "-----<br>";
            $sql = "SELECT Namn FROM bok";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo $row['Namn']."<br>";
                }
            }
        ?>
    </div> ----- Bok -----

    <div> <br> ----- Bok + Författare -----
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
                }
                Header('Location:index.php');
            }
        ?>
        <?php
            echo "<form method='post'>";
                echo "Bok: <br><input type='text' list='Bok' name='ValdBok' required='require' autocomplete='off'>";
                    echo "<datalist id=Bok>";
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
                echo "<input type='submit' value='Välj bok'/>";
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
                    echo "<input type='submit' value='Submit the form'/>";
                echo "</form>";
            } 

        ?>

        <?php
            echo "------<br>";
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
    </div> ----- Bok + Författare -----


    <div id="bruh"> <br> ----- Forfattare -----
        <?php
            if (isset($_POST['Forfattare'])) {
                $sql = $conn->prepare("INSERT INTO forfattare (Namn) VALUE (?)");
                $sql->bind_param("s", $Namn);

                $Namn = $_POST['ForfattarNamn'];
                $sql->execute();
                $sql->close();

                Header('Location:index.php');
            }
        ?>
        <?php
            echo "<form method = 'post'>";
                echo "<input type='hidden' name='Forfattare'>";
                echo "<input type='text' name='ForfattarNamn' required='require'><br>";
                echo "<input type='submit' value='Submit'>";
            echo "</form>";
        ?>
        <?php
            echo "-----<br>";
            $sql = "SELECT Namn FROM forfattare";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo $row['Namn']."<br>";
                }
            }
        ?>
    </div> ----- Forfattare -----
  </body>
</html>

