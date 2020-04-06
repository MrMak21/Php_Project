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
    <input type="date" name="date">
    <br>

    <label for="time">Time:</label>
    <input type="time" id="time" name="time">
    <br>
    <br>
    <label for="people">Number of people:</label>
    <input type="number" name="people">
    <br>

    <input type="submit" value="Reserve">

</form>


</body>
</html>

<?php

if (!isset($_SESSION['userId'])) {
    header("Location:login.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $people = $_POST['people'];
    $selectedUserId = $_POST['customer'];

    $dateTime = $date . " " . $time . ":00";

    makeReservation($dateTime,$people,$selectedUserId);

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