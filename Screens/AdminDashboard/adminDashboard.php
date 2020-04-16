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
    $thursday = 0;
    $friday = 0;
    $saturday = 0;
    $sunday = 0;

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
}

if(isset($_GET['action']) && $_GET['action'] == 'logout'){
    doLogout();
}

function doLogout()
{
    session_destroy();
    header("Location:login.php");
    exit();
}

function changeConfigs()
{

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

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
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

    <title>Admin dashboard</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="admin_dashboard.css" rel="stylesheet">
</head>

<body>
<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="adminDashboard.php"> <?php echo $_SESSION['userFirstName'] . " " . $_SESSION['userLastName'] ?> </a>
<!--    <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">-->
    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            <a class="nav-link" href="adminDashboard.php?action=logout">Sign out</a>
        </li>
    </ul>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="adminReservation.php">
                            <span data-feather="home"></span>
                            Create new Reservation <span class="sr-only">(current)</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="adminReservations.php">
                            <span data-feather="file"></span>
                            Reservations
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="adminDashboard.php">
                            <span data-feather="users"></span>
                            Configuration
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
            <form  action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="tables">Tables:</label>
                    <input type="number" class="form-control" name="tables" value="<?php echo $tables ?>">
                </div>

                <div class="form-group">
                    <label for="seats">Seats:</label>
                    <input type="number" class="form-control" id="time" name="seats" value="<?php echo $seats ?>">
                </div>

                <div class="form-group">
                    <label for="monday">Monday:</label>
                    <input type="checkbox" class="form-control" name="monday" <?php if ($monday) {
                        echo "checked=\"true\"";
                    } ?> value="1">
                </div>

                <div class="form-group">
                    <label for="tuesday">Tuesday:</label>
                    <input type="checkbox" class="form-control" name="tuesday" <?php if ($tuesday) {
                        echo "checked=\"true\"";
                    } ?> value="1">
                </div>

                <div class="form-group">
                    <label for="wednesday">Wednesday:</label>
                    <input type="checkbox" class="form-control" name="wednesday" <?php if ($wednesday) {
                        echo "checked=\"true\"";
                    } ?> value="1">
                </div>

                <div class="form-group">
                    <label for="thursday">Thursday:</label>
                    <input type="checkbox" class="form-control" name="thursday" <?php if ($thursday) {
                        echo "checked=\"true\"";
                    } ?> value="1">
                </div>

                <div class="form-group">
                    <label for="friday">Friday:</label>
                    <input type="checkbox" class="form-control" name="friday" <?php if ($friday) {
                        echo "checked=\"true\"";
                    } ?> value="1">
                </div>

                <div class="form-group">
                    <label for="saturday">Saturday:</label>
                    <input type="checkbox" class="form-control" name="saturday" <?php if ($saturday) {
                        echo "checked=\"true\"";
                    } ?> value="1">
                </div>

                <div class="form-group">
                    <label for="sunday">Sunday:</label>
                    <input type="checkbox" class="form-control" name="sunday" <?php if ($sunday) {
                        echo "checked=\"true\"";
                    } ?> value="1">
                </div>

                <div class="form-group">
                    <label for="open">Open hour:</label>
                    <input type="time" class="form-control" name="open" value="<?php echo $openHour ?>">
                </div>

                <div class="form-group">
                    <label for="close">Close hour:</label>
                    <input type="time" class="form-control" name="close" value="<?php echo $closeHour ?>">
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" name="submit" value="Change">
                </div>

<!--                <button type="submit" class="btn btn-primary">Submit</button>-->
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






