<?php session_start();
include("connection.php"); ?>
<html>

<head>
    <title>Sign In</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="s_up.css" rel="stylesheet" type="text/css">
</head>

<body>

    <?php
    if (isset($_GET['success']) && $_GET['success'] == 0) {
        echo 'You need to sign in';
    }

    $emailErr = $passErr = "";
    $email = $password = "";

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (empty($_POST["email"])) {
            $emailErr = "Email is required";
        } else {
            $email = test_input($_POST["email"]);
        }

        if (empty($_POST["password"])) {
            $nameErr = "Password is required";
        } else {
            $password = test_input($_POST["password"]);
        }

        $sql = "SELECT id,username,email, password FROM users WHERE email = :email";
        $s = $pdo->prepare($sql);
        $s->bindValue(':email', $email);
        $s->execute();
        $user = $s->fetch(PDO::FETCH_ASSOC);


        if ($user === false) {
            die('Incorrect email / password combination!');
        } else {
            $valid = password_verify($password, $user['password']);
            if ($valid) {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['logged_in'] = time();
                echo "Login successful";
                header('Location: viewus.php?logged=true');
                exit;
            } else
                die('Incorrect email / password combination!');
        }
    } else { ?>
    <h1>Sign In</h1>
    <p><span class="error"></span></p>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="group">
            <label for="email" class="label">Email: </label>
            <input type="email" name="email" value="<?php echo $email; ?>">
            <span class="error"> <?php echo $emailErr; ?></span><br><br>
        </div>

        <div class="group">
            <label for="password" class="label">Password: </label>
            <input type="password" name="password" value="<?php echo $password; ?>">
            <span class="error"> <?php echo $passErr; ?></span><br><br>
        </div>
        </div>
        <div class="group">
            <button type="submit" name="submit" value="Submit">Sign In</button>
        </div>
        <br><br>

        <p>Don't have an account? <a href='signup.php'>Sign Up</a></p>
        <p>Back to Home <a href="index.php">Home</a></p>
    </form>
    <?php 
} ?>
</body>

</html> 