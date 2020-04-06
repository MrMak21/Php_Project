<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin dashboard</title>
</head>
<body>

<h1> New Reservation!</h1>

<form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

    <label for="customer">Customer:</label>
    <select name="customer" size="1">
        <?php
            fillCustomerList();
        ?>

    </select>


    <br>
    <br>
    <label for="date">Date:</label>
    <input type="date" name="date" value="<?php echo $_POST['date'];?>">
    <br>

    <label for="time">Time:</label>
    <input type="time" id="time" name="time" value="<?php echo $_POST['time'];?>">
    &nbsp;
    <input type="submit" name="checkTime" value="Check">
    &nbsp;
    <p>Please check time before fill in the fields</p>
    <br>
    <br>
    <label for="people">Number of people:</label>
    <input type="number" name="people" value="<?php echo $_POST['people'];?>">
    <br>

    <input type="submit" name="submit" value="Reserve">

</form>


</body>
</html>

<?php

if (!isset($_SESSION['userId'])) {
    header("Location:login.php");
}

if (isset($_POST['checkTime'])) {
    checkTime();

} else if (isset($_POST['submit']) ) {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $people = $_POST['people'];
    $selectedUserId = $_POST['customer'];

    $dateTime = $date . " " . $time . ":00";

    makeReservation($dateTime,$people,$selectedUserId);

}

function checkTime() {

    $checkTime = $_POST['time'];
    $checkDate = $_POST['date'];
    $tables = getSumTables();
    $seats = getSeats();

    $ftimestamp = strtotime($checkTime) + 60*60*2;
    $ptimestamp = strtotime($checkTime) - 60*60*2;

    $forwardTime = date('H:i',$ftimestamp);
    $previousTime = date('H:i',$ptimestamp);
    $dayName = date("l",strtotime($checkDate));

    $sql = "SELECT COUNT(*) as `total` FROM `Reservation` WHERE (Reservation.Date >= '" . $checkDate . " " . $previousTime . ":00')" . " and (Reservation.Date <= '" . $checkDate . " " . $forwardTime .":00')";

    $avalaibleTables = getAvailableTables($sql);


    if (checkWorkingDay($dayName) == 1) {
        echo "We have " . ($tables - $avalaibleTables) . " tables (x" . $seats . " person) available for " . $dayName . " " . $checkDate . " at  " . $checkTime;
    } else {
        echo "We don't work on " . $dayName . "s. Please try another day";
    }



}

function checkWorkingDay($day) {
    $sql = "Select $day from Config";
    $res = 0;

    try {
        require 'DbConnect.php';

        if (!empty($conn)) {
            $data = $conn->query($sql)->fetchAll();
            foreach ($data as $row) {
                $res = $row[$day];

            }

        }
    }catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $res;

}


function getAvailableTables($sql) {

    $total = 0;
    try {
        require 'DbConnect.php';

        if (!empty($conn)) {
            $data = $conn->query($sql)->fetchAll();
            foreach ($data as $row) {
                $total = $row['total'];
            }

        }
    }catch (PDOException $e) {
        echo $e->getMessage();
    }

    return $total;
}

function getSumTables() {
    $tables = 0;
    try {
        require 'DbConnect.php';
        $sql = "SELECT * FROM Config";

        if (!empty($conn)) {
            $data = $conn->query($sql)->fetchAll();

            foreach ($data as $row) {
                $tables =  $row['NumOfTables'];
            }

        }
    }catch (PDOException $e) {
        echo $e->getMessage();
    }

    return $tables;
}

function getSeats() {
    $seats = 0;
    try {
        require 'DbConnect.php';
        $sql = "SELECT * FROM Config";

        if (!empty($conn)) {
            $data = $conn->query($sql)->fetchAll();

            foreach ($data as $row) {
                $seats =  $row['TableSeats'];
            }

        }
    }catch (PDOException $e) {
        echo $e->getMessage();
    }

    return $seats;
}

function makeReservation($date,$people,$selectedUserId) {
    try {
        require 'DbConnect.php';
        $userType = $_SESSION['userType'];
        $userId = $_SESSION['userId'];

        if ($userType == 1) {
            $sql = "INSERT INTO `Reservation` (`ReservationId`, `TableNo`, `Date`, `NumOfPeople`, `UserId`, `AdminId`) VALUES (NULL, '1', '$date', '$people', '$selectedUserId', '$userId');";
        } else {
            $sql = "INSERT INTO `Reservation` (`ReservationId`, `TableNo`, `Date`, `NumOfPeople`, `UserId`, `AdminId`) VALUES (NULL, '1', '$date', '$people', '$selectedUserId', NULL);";
        }
        if (!empty($conn)) {
            $conn->exec($sql);
            header("Location:adminDashboard.php");
        }
    }catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function fillCustomerList() {
    try {
        require 'DbConnect.php';
        $sql = "SELECT * FROM Users";

        if (!empty($conn)) {
            $data = $conn->query($sql)->fetchAll();

            foreach ($data as $row) {
                echo "<option value='" . $row['UserId'] . "'>" . $row['Firstname'] . " " . $row['Lastname'] . " (" . $row['Email'] . ")" .  "</option>";
            }

        }
    }catch (PDOException $e) {
        echo $e->getMessage();
    }
}

?>