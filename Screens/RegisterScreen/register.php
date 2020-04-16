<?php
$firstname = $lastname = $email = $password = $password2 = $userType = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = test_input($_POST['firstname']);
    $lastname =  test_input($_POST['lastname']);
    $email =  test_input($_POST['email']);
    $password =  test_input($_POST['password']);
    $password2 =  test_input($_POST['password2']);
    $userType =  test_input($_POST['userType']);

    if (!empty($firstname) && !empty($lastname) && !empty($email) && !empty($password) && !empty($password2) && !empty($userType)) {
        if ($password == $password2) {
            registerUser($firstname,$lastname,$email,$password,$userType);
        } else {
            echo "Passwords does not much!";
        }
    } else {
        echo "Please fill in all the fields";
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function registerUser($firstname,$lastname,$email,$password,$userType) {
    try {
        require 'DbConnect.php';
        $sql = "INSERT INTO `Users` (`UserId`, `Firstname`, `Lastname`, `Email`, `Password`, `UserTypeId`) VALUES (NULL, '$firstname', '$lastname', '$email', '$password', '$userType');";
        if (!empty($conn)) {
            $conn->exec($sql);
            header("Location:login.php");
        }
    }catch (PDOException $e) {
        echo $e->getMessage();
    }


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

        <title>Register</title>

        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <!-- Custom styles for this template -->
        <link href="signin.css" rel="stylesheet">
    </head>
</head>

<style>
    body {
        background-image: url('food2.jpg');
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: cover;
    }
</style>
<body>

<div class="card">
    <article class="card-body">
        <h4 class="card-title text-center mb-4 mt-1">Sign up</h4>
        <hr>
        <p class="text-success text-center" id="errorMsg">Makris Restaurant </p>
        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                    </div>
                    <input type="text" name="firstname" placeholder="Firstname" class="form-control" value="<?php echo $_POST['firstname']; ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                    </div>
                    <input type="text" name="lastname" placeholder="Lastname" class="form-control" value="<?php echo $_POST['lastname']; ?>">
                </div>
            </div>

            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                    </div>
                    <input type="email" name="email" placeholder="Email" class="form-control" value="<?php echo $_POST['email']; ?>">
                </div>
            </div>

            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                    </div>
                    <input type="password" name="password" placeholder="*****" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                    </div>
                    <input type="password" name="password2" placeholder="*****" class="form-control">
                </div>
            </div>


            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                    </div>
                    <input type="radio" name="userType" value="1" class="form-control" > <label for="userType">Admin   </label>
                    <input type="radio" name="userType" value="3" class="form-control" > <label for="userType">Customer</label>
                </div>
            </div>

<!--            <div class="form-group">-->
<!--                <div class="input-group">-->
<!--                    <div class="input-group-prepend">-->
<!--                        <span class="input-group-text"> <i class="fa fa-lock"></i> </span>-->
<!--                    </div>-->
<!--                    <input type="radio" name="userType" value="3" class="form-control" > <label for="userType">Customer</label>-->
<!--                </div>-->
<!--            </div>-->

            <div class="form-group">
                <button type="submit" name="register" class="btn btn-primary btn-block"> Register  </button>
            </div>
            <p class="text-center"><a href="login.php" class="btn">Already have an account? Login here</a></p>
        </form>
    </article>
</div>
</body>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register User</title>
</head>
<body>
