<?php
session_start();
?>

<?php
$email = $password = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = test_input($_POST['email']);
    $password = test_input($_POST['password']);

    if (!empty($email) && !empty($password)) {
        authenticateUser($email, $password);
    } else {
        echo "Please fill in all the fields";
    }
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function authenticateUser($email, $password)
{
    require 'DbConnect.php';

    try {
        require 'DbConnect.php';
        $sql = "SELECT * FROM `Users` WHERE `Email` = '$email' AND `Password` = '$password' ;";
        if (!empty($conn)) {
            $stmt = $conn->query($sql);
            $user = $stmt->fetch();

            if (!empty($user)) {
                $_SESSION['userEmail'] = $user['Email'];
                $_SESSION['userId'] = $user['UserId'];
                $_SESSION['loggedIn'] = true;
                $_SESSION['userType'] = $user['UserTypeId'];
                $_SESSION['userFirstName'] = $user['Firstname'];
                $_SESSION['userLastName'] = $user['Lastname'];

                if ($user['UserTypeId'] == 1) {
                    header("Location:adminDashboard.php");
                } elseif ($user['UserTypeId'] == 3) {
                    header("Location:customerDashboard.php");
                }
            } else {
                changeTextField();
            }

        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

}

function changeTextField() {
     echo '<script type="text/javascript"> document.getElementById("errorMsg").innerText = "Wrong credentials" </script>';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="../../../../favicon.ico">

        <title>Login</title>

        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
              integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
              crossorigin="anonymous">

        <!-- Custom styles for this template -->
        <link href="signin.css" rel="stylesheet">
    </head>
</head>

<style>
    body {
        background-image: url('food.jpg');
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: cover;
    }
</style>
<body>



<div class="card">
    <article class="card-body">
        <h4 class="card-title text-center mb-4 mt-1">Sign in</h4>
        <hr>
        <p class="text-success text-center" id="errorMsg">Welcome </p>
        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                    </div>
                    <input name="email" class="form-control" placeholder="Email" type="email" value="<?php echo $_POST['email']; ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                    </div>
                    <input name="password" class="form-control" placeholder="******" type="password">
                </div>
            </div>
            <div class="form-group">
                <button type="submit" name="login" class="btn btn-primary btn-block"> Login  </button>
            </div>
            <p class="text-center"><a href="register.php" class="btn">Don't have an account? Register here</a></p>
        </form>
    </article>
</div>


</body>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
</html>


