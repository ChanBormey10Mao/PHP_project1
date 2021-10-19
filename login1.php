<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <?php
    include_once("DBconnect.php");

    session_start();
    // echo session_id();
    ?>
    <?php
    if (isset($_POST["loginBtn"])) {
        //Email:
        //get all existed emails
        $existed_email_arr = getUsernameRecords($conn);
        if (isset($_POST["username"]) && trim($_POST["username"]) != "") {
            $_SESSION["username"] =   stripslashes($_POST["username"]);
        } else {
            $username_err = "<p>Please input a username</p>";
        }
        //Passwords:
        if (isset($_POST["password"])  && trim($_POST["password"]) != "") {
            $employee = array();
            $_SESSION["pwd_matched"] = stripslashes($_POST["password"]);
            echo strcmp($_SESSION["pwd_matched"], getPasswordRecords($conn, $_SESSION["username"])) . "<br>";
            echo  $_SESSION["pwd_matched"] . "____" . getPasswordRecords($conn, $_SESSION["username"]) . "<br>";
            if (strcmp($_SESSION["pwd_matched"], getPasswordRecords($conn, $_SESSION["username"])) == 0) {
                $employee = getEmployeeRecords($conn, $_SESSION["username"], $_SESSION["pwd_matched"]);
                if ($employee["username"] == "admin") {
                    header("location:AdminMenu.php");
                }
                if ($employee["username"] == "worker") {
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
    <form action="login1.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username">
        <?php if (isset($username_err)) { ?>
            <?php echo  $username_err; ?>
        <?php } ?>
        <label for="password">Password</label>
        <input type="password" name="password">
        <?php if (isset($password_err)) { ?>
            <?php echo  $password_err; ?>
        <?php } ?>
        <input type="submit" name="loginBtn" value="Log in">
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
                array_push($arr, $row["password"]);
            }
        }
        $result->free_result();

        return $arr[0]["password"];
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

        return $arr[0];
    }
    ?>
</body>

</html>