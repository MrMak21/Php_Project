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

<h1> Select date to find reservations!</h1>

<form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

    Date:<br>
    <input type="date" name="date"><br>
    <input type="submit" value="Find">

</form>


</body>
</html>


<?php
if (!isset($_SESSION['userId'])) {
    header("Location:login.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    showReservations($date);

}


function showReservations($date) {
    try {
        require 'DbConnect.php';
        $sql = "SELECT * FROM Reservation inner join Users on Reservation.UserId = Users.UserId WHERE Reservation.Date >= '" . $date . " 00:00:00' and Reservation.Date <= '" . $date ." 23:59:59'";

        if (!empty($conn)) {
            $data = $conn->query($sql)->fetchAll();

            foreach ($data as $row) {
                echo $row['Date']. " " . $row['Firstname'] . " " . $row['Lastname'] ."<br />\n";
            }

        }
    }catch (PDOException $e) {
        echo $e->getMessage();
    }
}
?>

