<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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
                $_SESSION['userEmail'] = $user['Email'];
                $_SESSION['userId'] = $user['UserId'];
                echo 'Succesfull login user: ' . $user['Firstname'] . ' ' . $user['Lastname'];
                if ($user['UserTypeId'] == 1) {
                    header("Location:adminDashboard.php");
                }elseif ($user['UserTypeId'] == 3) {
                    header("Location:customerDashboard.php");
                }
            } else {
                echo 'Wrong credentials';
            }

        }
    }catch (PDOException $e) {
        echo $e->getMessage();
    }

}

?>
