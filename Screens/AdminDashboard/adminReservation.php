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

    Date:<br>
    <input type="datetime-local" name="date"><br>
    Number of people:<br>
    <input type="number" name="people"><br>
    <input type="submit" value="Reserve">

</form>


</body>
</html>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $people = $_POST['people'];

    makeReservation($date,$people);

}

function makeReservation($date,$people) {
    try {
        require 'DbConnect.php';
        $userId = $_SESSION['userId'];
        $sql = "INSERT INTO `Reservation` (`ReservationId`, `TableNo`, `Date`, `NumOfPeople`, `UserId`, `AdminId`) VALUES (NULL, '1', '$date', '$people', '$userId', NULL);";
        if (!empty($conn)) {
            $conn->exec($sql);
            header("Location:adminDashboard.php");
        }
    }catch (PDOException $e) {
        echo $e->getMessage();
    }
}

?>