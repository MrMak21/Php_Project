<?php
session_start();
?>


<?php

if (!isset($_SESSION['userId'])) {
    header("Location:login.php");
}

//Handle form click buttons
if (isset($_POST['checkTime'])) {
    checkTime();
} else if (isset($_POST['submit'])) {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $people = $_POST['people'];

    $dateTime = $date . " " . $time . ":00";
    validateReservetion();
} else if (isset($_POST['logout'])) {
    doLogout();
}

function makeReservation($date, $people, $askingTables)
{
    try {
        require 'DbConnect.php';
        $userType = $_SESSION['userType'];
        $userId = $_SESSION['userId'];

        $sql = "INSERT INTO `Reservation` (`ReservationId`, `TableNo`, `Date`, `NumOfPeople`, `UserId`, `AdminId`) VALUES (NULL, $askingTables, '$date', '$people', '$userId',NULL);";

        if (!empty($conn)) {
            $conn->exec($sql);
            header("Location:customerDashboard.php");
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function checkTime()
{

    $checkTime = $_POST['time'];
    $checkDate = $_POST['date'];
    $tables = getSumTables();
    $seats = getSeats();

    $ftimestamp = strtotime($checkTime) + 60 * 60 * 2;
    $ptimestamp = strtotime($checkTime) - 60 * 60 * 2;

    $forwardTime = date('H:i', $ftimestamp);
    $previousTime = date('H:i', $ptimestamp);
    $dayName = date("l", strtotime($checkDate));

    $sql = "SELECT COUNT(*) as `total` FROM `Reservation` WHERE (Reservation.Date >= '" . $checkDate . " " . $previousTime . ":00')" . " and (Reservation.Date <= '" . $checkDate . " " . $forwardTime . ":00')";

    $avalaibleTables = getAvailableTables($sql);


    $msg = "";
    if (checkWorkingDay($dayName) == 1) {
        $msg = "We have " . ($tables - $avalaibleTables) . " tables (x" . $seats . " person) available for " . $dayName . " " . $checkDate . " at  " . $checkTime;
    } else {
        $msg = "We don't work on " . $dayName . "s. Please try another day";
    }

    phpAlert($msg);
}

function validateReservetion()
{

    try {
        $rTime = $_POST['time'];
        $rDate = $_POST['date'];
        $rPeople = $_POST['people'];
        $totalTables = getSumTables();
        $totalSeats = getSeats();


        $rAskingTables = getAskingTablesNum($rPeople, $totalSeats);

        $ftimestamp = strtotime($rTime) + 60 * 60 * 2;
        $ptimestamp = strtotime($rTime) - 60 * 60 * 2;

        $forwardTime = date('H:i', $ftimestamp);
        $previousTime = date('H:i', $ptimestamp);
        $dayName = date("l", strtotime($rDate));

        $availableTablesSql = "SELECT SUM(Reservation.TableNo) as `total` FROM `Reservation` WHERE (Reservation.Date >= '" . $rDate . " " . $previousTime . ":00')" . " and (Reservation.Date <= '" . $rDate . " " . $forwardTime . ":00')";

        $reservedTables = getAvailableTables($availableTablesSql);
        $avalaibleTables = $totalTables - $reservedTables;

        $isWorkingDate = checkWorkingDay($dayName);

        $isWorkingHours = checkWorkingHours($rTime);

        $msg = "";
        if ($rDate != NULL) {
            if ($rTime != NULL) {
                if ($rPeople != NULL && $rPeople > 0) {
                    if ($isWorkingDate == 1) {
                        if ($isWorkingHours == true) {
                            if ($avalaibleTables >= $rAskingTables) {
                                //Do the reservation
                                $dateTime = $rDate . " " . $rTime . ":00";
                                makeReservation($dateTime, $rPeople, $rAskingTables);
                            } else {
                                $msg = "Sorry we don't have " . $rAskingTables . " available tables at that time! Please try another time";
                                phpAlert($msg);
                            }
                        } else {
                            $msg = "We don't work at that time. Please select a valid time";
                            phpAlert($msg);
                        }
                    } else {
                        $msg = "We don't work on " . $dayName . "s. Please try another day";
                        phpAlert($msg);
                    }
                } else {
                    $msg = "Please add people to make reservation";
                    phpAlert($msg);
                }
            } else {
                $msg = "Please select time to make reservation";
                phpAlert($msg);
            }
        } else {
            $msg = "Please select a date to make reservation";
            phpAlert($msg);
        }
    } catch (Exception $e) {
        echo $e;
    }
}

function checkWorkingHours($time)
{

    $sql = "Select * from Config";
    $open = "";
    $close = "";
    try {
        require 'DbConnect.php';


        if (!empty($conn)) {
            $data = $conn->query($sql)->fetchAll();
            foreach ($data as $row) {
                $open = $row['OpenHour'];
                $close = $row['CloseHour'];

            }

            if (strtotime($time) >= strtotime($open) && strtotime($time) <= strtotime($close)) {
                return true;
            } else {
                return false;
            }

        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}


function getAskingTablesNum($people, $seats)
{
    if ($people % $seats > 0) {
        return (int)($people / $seats) + 1;
    } else {
        return ($people / $seats);
    }
}

function checkWorkingDay($day)
{
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
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $res;

}


function getAvailableTables($sql)
{

    $total = 0;
    try {
        require 'DbConnect.php';

        if (!empty($conn)) {
            $data = $conn->query($sql)->fetchAll();
            foreach ($data as $row) {
                $total = $row['total'];
            }

        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    return $total;
}

function getSumTables()
{
    $tables = 0;
    try {
        require 'DbConnect.php';
        $sql = "SELECT * FROM Config";

        if (!empty($conn)) {
            $data = $conn->query($sql)->fetchAll();

            foreach ($data as $row) {
                $tables = $row['NumOfTables'];
            }

        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    return $tables;
}

function getSeats()
{
    $seats = 0;
    try {
        require 'DbConnect.php';
        $sql = "SELECT * FROM Config";

        if (!empty($conn)) {
            $data = $conn->query($sql)->fetchAll();

            foreach ($data as $row) {
                $seats = $row['TableSeats'];
            }

        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    return $seats;
}

function doLogout()
{
    session_destroy();
    header("Location:login.php");
    exit();
}

function phpAlert($msg)
{
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}

?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>New Reservation</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="admin_dashboard.css" rel="stylesheet">
</head>

<body>
<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0"
       href="customerDashboard.php"> <?php echo $_SESSION['userFirstName'] . " " . $_SESSION['userLastName'] ?> </a>
    <!--    <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">-->
    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            <a class="nav-link" href="customerDashboard.php?action=logout">Sign out</a>
        </li>
    </ul>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="customerDashboard.php">
                            <span data-feather="home"></span>
                            Dashboard <span class="sr-only">(current)</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="customerReservation.php">
                            <span data-feather="file"></span>
                            New Reservation
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
            <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="date">Date:</label>
                    <input type="date" name="date" class="form-control" value="<?php echo $_POST['date']; ?>">
                </div>

                <div class="form-group">
                    <label for="time">Time:</label>
                    <input type="time" id="time" class="form-control" name="time" value="<?php echo $_POST['time']; ?>">
                </div>

                <div class="form-group">
                    <input type="submit" name="checkTime" class="btn btn-primary" value="Check">
                </div>

                <div class="form-group">
                    <p>Please check time before fill in the fields</p>
                </div>

                <div class="form-group">
                    <label for="people">Number of people:</label>
                    <input type="number" name="people" class="form-control" value="<?php echo $_POST['people']; ?>">
                </div>

                <div class="form-group">
                    <input type="submit" name="submit" class="btn btn-primary" value="Reserve">
                </div>

            </form>
        </main>
    </div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="../../../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
<script src="../../../../assets/js/vendor/popper.min.js"></script>
<script src="../../../../dist/js/bootstrap.min.js"></script>

<!-- Icons -->
<script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
<script>
    feather.replace()
</script>
</body>
</html>
