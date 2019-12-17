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
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <!-- Custom styles for this template -->
        <link href="signin.css" rel="stylesheet">
    </head>
</head>
<body>

<form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

    Email:<br>
    <input type="email" name="email"><br>
    Password:<br>
    <input type="password" name="password"><br>
    <input type="submit" value="Login">

</form>

</body>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</html>


<?php
$email = $password = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email =  test_input($_POST['email']);
    $password =  test_input($_POST['password']);

    if (!empty($email) && !empty($password)) {
            authenticateUser($email,$password);
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

function authenticateUser($email,$password) {
    require 'DbConnect.php';

    try {
        require 'DbConnect.php';
        $sql = "SELECT * FROM `Users` WHERE `Email` = '$email' AND `Password` = '$password' ;";
        if (!empty($conn)) {
            $stmt = $conn->query($sql);
            $user = $stmt->fetch();

            if (!empty($user)) {
                echo 'Succesfull login user: ' . $user['Firstname'] . ' ' . $user['Lastname'];
            } else {
                echo 'Wrong credentials';
            }

        }
    }catch (PDOException $e) {
        echo $e->getMessage();
    }

}

?>
