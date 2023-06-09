<?php
session_start();

// $_SESSION['test'] = 666;
// var_dump($_SESSION['test']);

include 'mysql.php';

$notice = "Please input your username and password";

if($_POST && isset($_POST['login'])) {
    // 😋Tip: 预处理防sqli，期待更好的解决方案
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM `users` WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if($row['password'] == $password) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['loggedin'] = true;

            $notice = "login success";
            header("Location: index.php?username=$username");
            // exit();
        } else {
            $notice =  "Failed to login as $username, please try again.";
        }
    } else {
        $notice = "Username $username don't exist.";
    }
    $stmt -> close();
}
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="./public/src/css/login.css">
        <title>Login Page</title>
    </head>
    <body>
        <main>
            <h1>Wirror</h1>
            <h2>Log in to your account</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <label id="username-label" class="label">Username</label>
                <input id="username" class="input" type="text" name="username" required>
                <label id="password-label" class="label">Password</label>
                <a id="forget" class="tip" href="javascript:alert('Not supported yet.');">Forget your password?</a>
                <input id="password" class="input" type="password" name="password" required>
                <span id="notice" class="tip"><?php echo $notice;?></span>
                <input id="login" type="submit" name="login" value="Login">
            </form>
            <span id="register-tip">
                Don't have an account? <a id="link" href="register.php">Sign up</a>
            </span>
        </main>
    </body>
</html>