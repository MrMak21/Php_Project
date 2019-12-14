<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register User</title>
</head>
<body>

<form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    First name:<br>
    <input type="text" name="firstname"><br>
    Last name:<br>
    <input type="text" name="lastname"><br>
    Email:<br>
    <input type="email" name="email"><br>
    Password:<br>
    <input type="password" name="password"><br>
    Retype Password:<br>
    <input type="password" name="password2"><br>
    <input type="radio" name="userType" value="1" checked> Admin
    <input type="radio" name="userType" value="3"> Customer<br>
    <input type="submit" value="Register">

</form>

</body>
</html>



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