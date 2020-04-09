<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location:login.php");
}

try {
    require 'DbConnect.php';
    $sql = "SELECT * FROM Config";

    $monday = 0;
    $tuesday = 0;
    $wednesday = 0;
    $thursday  = 0;
    $friday = 0;
    $saturday = 0;
    $sunday  = 0;

    $openHour = "";
    $closeHour = "";

    $tables = 0;
    $seats = 0;



    if (!empty($conn)) {
        $data = $conn->query($sql)->fetchAll();

        foreach ($data as $row) {
            $monday = $row['Monday'];
            $tuesday = $row['Tuesday'];
            $wednesday = $row['Wednesday'];
            $thursday = $row['Thursday'];
            $friday = $row['Friday'];
            $saturday = $row['Saturday'];
            $sunday = $row['Sunday'];

            $openHour = $row['OpenHour'];
            $closeHour = $row['CloseHour'];

            $tables = $row['NumOfTables'];
            $seats = $row['TableSeats'];

            if ($closeHour == "24:00:00") {
                $closeHour = "23:59:00";
            }
        }

    }
} catch (PDOException $e) {
    echo $e->getMessage();
}

//if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//    changeConfigs();
//}

if (isset($_POST['submit'])) {
    changeConfigs();
} else if (isset($_POST['logout'])) {
    doLogout();
}

function doLogout() {
    session_destroy();
    header("Location:login.php");
    exit();
}

function changeConfigs() {

    $mTables = $_POST['tables'];
    $mSeats = $_POST['seats'];
    $mOpen = $_POST['open'];
    $mClose = $_POST['close'];

    $monday = 0;
    if ($_POST['monday'] == 1) {
        $monday = 1;
    } else {
        $monday = 0;
    }
    $tuesday = 0;
    if ($_POST['tuesday'] == 1) {
        $tuesday = 1;
    } else {
        $tuesday = 0;
    }
    $wednesday = 0;
    if ($_POST['wednesday'] == 1) {
        $wednesday = 1;
    } else {
        $wednesday = 0;
    }
    $thursday = 0;
    if ($_POST['thursday'] == 1) {
        $thursday = 1;
    } else {
        $thursday = 0;
    }
    $friday = 0;
    if ($_POST['friday'] == 1) {
        $friday = 1;
    } else {
        $friday = 0;
    }
    $saturday = 0;
    if ($_POST['saturday'] == 1) {
        $saturday = 1;
    } else {
        $saturday = 0;
    }
    $sunday = 0;
    if ($_POST['sunday'] == 1) {
        $sunday = 1;
    } else {
        $sunday = 0;
    }




    try {
        require 'DbConnect.php';
        $sql = "UPDATE `Config` SET `NumOfTables`= $mTables,`TableSeats`= $mSeats,`Monday`= $monday" .
                ",`Tuesday`= $tuesday,`Wednesday`= $wednesday,`Thursday`= $thursday,`Friday`= $friday,`Saturday`= $saturday" .
                ",`Sunday`= $sunday,`OpenHour`= '$mOpen',`CloseHour`= '$mClose'" .
                "Where `ConfigId` = 1";

        if (!empty($conn)) {
            $conn->exec($sql);
//            header("Refresh:0");
            header("Location:adminDashboard.php");
            die;
        }

    }catch (PDOException $e) {
        echo $e->getMessage();
    }
}


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin dashboard</title>
</head>
<body>

<h1> Welcome Admin!</h1>
<a href="adminReservation.php">Create new Reservation</a>
<br>
<a href="adminReservations.php">See Reservations</a>


<form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

    <br>
    <h2> Restaurant settings</h2>
    <br>
    <label for="tables">Tables:</label>
    <input type="number" name="tables" value="<?php echo $tables ?>">
    <br>

    <label for="seats">Seats:</label>
    <input type="number" id="time" name="seats"  value="<?php echo $seats ?>">
    <br>
    <br>

    <label for="monday">Monday:</label>
    <input type="checkbox" name="monday" <?php if ($monday) { echo "checked=\"true\""; } ?> value="1">
    <br>

    <label for="tuesday">Tuesday:</label>
    <input type="checkbox" name="tuesday" <?php if ($tuesday) { echo "checked=\"true\""; } ?> value="1">
    <br>

    <label for="wednesday">Wednesday:</label>
    <input type="checkbox" name="wednesday" <?php if ($wednesday) { echo "checked=\"true\""; } ?> value="1">
    <br>

    <label for="thursday">Thursday:</label>
    <input type="checkbox" name="thursday" <?php if ($thursday) { echo "checked=\"true\""; } ?> value="1">
    <br>

    <label for="friday">Friday:</label>
    <input type="checkbox" name="friday" <?php if ($friday) { echo "checked=\"true\""; } ?> value="1">
    <br>

    <label for="saturday">Saturday:</label>
    <input type="checkbox" name="saturday" <?php if ($saturday) { echo "checked=\"true\""; } ?> value="1">
    <br>

    <label for="sunday">Sunday:</label>
    <input type="checkbox" name="sunday" <?php if ($sunday) { echo "checked=\"true\""; } ?> value="1">
    <br>

    <label for="open">Open hour:</label>
    <input type="time" name="open" value="<?php echo $openHour?>">
    <br>

    <label for="close">Close hour:</label>
    <input type="time" name="close" value="<?php echo $closeHour?>">
    <br>

    <input type="submit" name="submit" value="Change">

    <br>
    <br>
    <input type="submit" class="button" name="logout" value="logout" />

</form>


</body>
</html>





