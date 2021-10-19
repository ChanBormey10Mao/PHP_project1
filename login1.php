<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
	<link rel="stylesheet" href="login.css">
</head>

<body>
    <?php
    include_once("DBconnect.php");

    session_start();
    // echo session_id();
    ?>
    <?php
    if (isset($_POST["loginBtn"])) {
        $_SESSION["username"] = "";
        $_SESSION["pwd_matched"] = "";
        //username:
        //get all existed emails
        $existed_email_arr = getUsernameRecords($conn);
        if (isset($_POST["username"]) && trim($_POST["username"]) != "") {
            $_SESSION["username"] =   stripslashes($_POST["username"]);
        } else {
            $username_err = "<p>Please input a username</p>";
        }

        //Passwords:
        if (isset($_POST["password"])  && trim($_POST["password"]) != "") {
            $_SESSION["employee"] = array();
            $_SESSION["pwd_matched"] = stripslashes($_POST["password"]);
            // echo strcmp($_SESSION["pwd_matched"], getPasswordRecords($conn, $_SESSION["username"])) . "<br>";
            // echo  $_SESSION["pwd_matched"] . "____" . getPasswordRecords($conn, $_SESSION["username"]) . "<br>";
            if (strcmp($_SESSION["pwd_matched"], getPasswordRecords($conn, $_SESSION["username"])) == 0) {
                $_SESSION["employee"] = getEmployeeRecords($conn, $_SESSION["username"], $_SESSION["pwd_matched"]);

                if (strcmp($_SESSION["employee"]["username"], "admin") == 0) {
                    header("location:AdminMenu.php");
                }
                if ($_SESSION["employee"]["username"] == "worker") {
                    header("location:WorkerMenu.php");
                }
            } else {
                $password_err = "<p style=\"color: red;\">Password doesn't matched.</p>";
            }
        }
    } else {
        $password_err = "<p style=\"color: red;\">Please Enter Required Input.</p>";                       //display an error
    }
    ?>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="login1.php" method="POST">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>           
        <?php if (isset($username_err)) { ?>
            <?php echo  $username_err; ?>
        <?php } ?>
        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control">
            <span class="help-block"><?php echo $password_err; ?></span>
            </div>
                
            <?php if (isset($password_err)) { ?>
            <?php echo  $password_err; ?>
        <?php } ?>
        <div class="form-group">
        <input type="submit" name="loginBtn" value="Login">
        </div>
    </form>

    <?php
    function getUsernameRecords($conn)
    {
        $query = "SELECT username FROM users;";
        $result = mysqli_query($conn, $query);
        $arr = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($arr, $row["username"]);
            }
        }
        $result->free_result();

        return $arr;
    }
    function getPasswordRecords($conn, $username)
    {

        $query = "SELECT password FROM users WHERE username = '$username';";
        $result = mysqli_query($conn, $query);
        $arr = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // echo $row["password"] . "row passowrd" . "<br>";
                array_push($arr, $row["password"]);
            }
        } else {
            echo "0 Result - getPasswordRecords" . "<br>";
            return null;
        }

        $result->free_result();

        return $arr[0];
    }
    function getEmployeeRecords($conn, $username, $pwd)
    {
        $query = "SELECT id,username,password FROM users WHERE (username = '$username' AND password= '$pwd')";
        $result = mysqli_query($conn, $query);
        $arr = array();
        $temp = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $temp = array(
                    "id" => @$row["id"],
                    "username" => @$row["username"],
                    "password" => @$row["password"]
                );
                $arr = $arr + $temp;
            }
        }
        $arr = $arr + $temp;
        $result->free_result();
        return $arr;
    }
    ?>
</body>

</html>