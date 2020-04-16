<?php
session_start();
?>


<?php
if (!isset($_SESSION['userId'])) {
    try {
        header("Location: login.php");
        exit();
    }catch (Exception $e) {
        echo $e;
    }
}

function phpAlert($msg)
{
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}




function showReservations($date) {
    try {
        require 'DbConnect.php';
        $sql = "SELECT * FROM Reservation inner join Users on Reservation.UserId = Users.UserId WHERE Reservation.Date >= '" . $date . " 00:00:00' and Reservation.Date <= '" . $date ." 23:59:59'";


        if (!empty($conn)) {
            $data = $conn->query($sql)->fetchAll();
            $i = 1;
            foreach ($data as $row) {

                echo "<tr>";
                echo "<th scope='row'>$i</th>";
                echo "<td>" . $row['Firstname'] . "</td>";
                echo "<td>" . $row['Lastname'] . "</td>";
                echo "<td>" . $row['Date'] . "</td>";
                echo "<td>" . $row['NumOfPeople'] . "</td>";
                echo "<td>" . $row['TableNo'] . "</td>";
                echo "</tr>";
                $i++;
            }
        }
    }catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function showAll()
{
    try {
        require 'DbConnect.php';
        $sql = "SELECT * FROM Reservation inner join Users on Reservation.UserId = Users.UserId";


        if (!empty($conn)) {
            $data = $conn->query($sql)->fetchAll();
            $i = 1;
            foreach ($data as $row) {

                echo "<tr>";
                echo "<th scope='row'>$i</th>";
                echo "<td>" . $row['Firstname'] . "</td>";
                echo "<td>" . $row['Lastname'] . "</td>";
                echo "<td>" . $row['Date'] . "</td>";
                echo "<td>" . $row['NumOfPeople'] . "</td>";
                echo "<td>" . $row['TableNo'] . "</td>";
                echo "</tr>";
                $i++;
            }
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

    <title>Reservations</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
          crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="admin_dashboard.css" rel="stylesheet">
</head>

<body>
<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0"
       href="adminDashboard.php"> <?php echo $_SESSION['userFirstName'] . " " . $_SESSION['userLastName'] ?> </a>
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
                        <a class="nav-link" href="adminReservation.php">
                            <span data-feather="home"></span>
                            New Reservation <span class="sr-only">(current)</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="adminReservations.php">
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
            <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="date">Date:</label>
                    <input type="date" name="date" class="form-control" value="<?php echo $_POST['date']; ?>">
                </div>

                <div class="form-group">
                    <input type="submit" name="show" class="btn btn-primary" value="Show">
                </div>

                <div class="form-group">
                    <input type="submit" name="showAll" class="btn btn-primary" value="Show All">
                </div>
            </form>

            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Firstname</th>
                    <th scope="col">Lastname</th>
                    <th scope="col">Date</th>
                    <th scope="col">People</th>
                    <th scope="col">Tables</th>
                </tr>
                </thead>
                <tbody>

                <?php  if (isset($_POST['show'])) {
                    $date = $_POST['date'];
                    if ($date == NULL) {
                        $msg = "Please select date";
                        phpAlert($msg);
                    } else {
                        showReservations($date);
                    }
                } else if (isset($_POST['showAll'])) {
                    $date = $_POST['date'];
                    showAll();
                }
                ?>
                </tbody>
            </table>
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

