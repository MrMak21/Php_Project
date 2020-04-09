<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer dashboard</title>
</head>
<body>

<h1> Welcome <?php echo $_SESSION['userFirstName']; echo " "; echo $_SESSION['userLastName']; ?></h1>

<a href="customerReservation.php">New reservation request </a>
<br>
<br>
<a href="customerDashboard.php?action=logout">Logout</a>



</body>
</html>

<?php

if(isset($_GET['action']) && $_GET['action'] == 'logout'){
    doLogout();
}

showPastReservations();
showFutureReservations();


function showPastReservations() {
    $userId = $_SESSION['userId'];
    $dateNow = date("Y-m-d h:i:s");


    try {
        require 'DbConnect.php';
        $sql = "SELECT * FROM Reservation inner join Users on Reservation.UserId = Users.UserId WHERE Users.UserId = '$userId' AND Reservation.Date <= '$dateNow'";

        if (!empty($conn)) {
            $data = $conn->query($sql)->fetchAll();

            echo "<br>";
            //check if the user has past reservations
            if (count($data) == 0) {
                echo "No reservations found recently";
            } else {
                echo "Past reservations: <br>";
                foreach ($data as $row) {
                    echo $row['Date'] . " " . $row['Firstname'] . " " . $row['Lastname'] . " " . $row['NumOfPeople'] . "<br />\n";
                }
            }

        }
    }catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function showFutureReservations() {
    $userId = $_SESSION['userId'];
    $dateNow = date("Y-m-d h:i:s");

    try {
        require 'DbConnect.php';
        $sql = "SELECT * FROM Reservation inner join Users on Reservation.UserId = Users.UserId WHERE Users.UserId = '$userId' AND Reservation.Date > '$dateNow' ";

        if (!empty($conn)) {
            $data = $conn->query($sql)->fetchAll();

            echo "<br>";
            //check if the user has past reservations
            if (count($data) == 0) {
                echo "No reservations found recently";
            } else {
                echo "Future reservations: <br>";
                foreach ($data as $row) {
                    echo $row['Date'] . " " . $row['Firstname'] . " " . $row['Lastname'] . " " . $row['NumOfPeople'] . "<br />\n";
                }
            }

        }
    }catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function doLogout() {
    session_destroy();
    header("Location:login.php");
    exit();
}

?>

