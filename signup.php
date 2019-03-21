<html>

<head>
    <title>Sign Up</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="css" type="text/css" />
</head>

<body>
    <?php

    include("connection.php");

    $nameErr = $unameErr = $emailErr = $passErr = $genderErr =  "";
    $name = $username = $email = $gender = $password = "";
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (empty($_POST["name"])) {
                $nameErr = "Name is required";
            } else {
                $name = test_input($_POST["name"]);
                if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
                    $nameErr = "Only letters and white space allowed";
                }
            }

            if (empty($_POST["username"])) {
                $unameErr = "Username is required";
            } else {
                $username = test_input($_POST["username"]);
                if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
                    $unameErr = "Only letters and numbers allowed";
                }
            }
            $dob = $_POST["dob"];
            if (empty($_POST["email"])) {
                $emailErr = "Email is required";
            } else {
                $email = test_input($_POST["email"]);
                // check if e-mail address is well-formed
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "Invalid email format";
                }
            }

            if (empty($_POST["password"])) {
                $nameErr = "Password is required";
            } else {
                $password = test_input($_POST["password"]);
                if (!preg_match("/(?=.*\d)(?=.*[A-Z])(?=.*\W).{8,8}/", $password)) {
                    $passErr = "Must contain at least one digit, one uppercase letter, one special symbol
                  and 8 characters";
                }
            }

            if (empty($_POST["gender"])) {
                $genderErr = "Gender is required";
            } else {
                $gender = test_input($_POST["gender"]);
            }

            $country = test_input($_POST["country"]);
            try {
                $sql = "SELECT COUNT(email) AS num FROM users WHERE email = :email";
                $s = $pdo->prepare($sql);
                $s->bindValue(':email', $email);
                $s->execute();

                $row = $s->fetch(PDO::FETCH_ASSOC);

                if ($row['num'] > 0) {
                    die('That email already exists!');
                }
                $hash = password_hash($password, PASSWORD_BCRYPT);

                $sql = "INSERT INTO users (name, dob, email, username, password, gender, country)
             VALUES (:name, :dob, :email, :username, :password, :gender, :country)";
                $s = $pdo->prepare($sql);

                $s->bindValue(':name', $name);
                $s->bindValue(':dob', $dob);
                $s->bindValue(':email', $email);
                $s->bindValue(':username', $username);
                $s->bindValue(':password', $hash);
                $s->bindValue(':gender', $gender);
                $s->bindValue(':country', $country);
                $result = $s->execute();
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            if ($result) {

                echo "Welcome" . $name . "Your registration is successful.";
            }
        } else {
        ?>
    <h1>Sign Up</h1>
    <p><span class="error"></span></p>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="group">
            <label for="user" class="label">Name: </label>
            <input type="text" name="name" value="<?php echo $name; ?>">
            <span class="error"> <?php echo $nameErr; ?></span><br><br>
        </div>
        <div class="group">
            <label for="dob" class="label">Date of Birth: </label>
            <input type="date" name="dob" value="dob"><br><br>
        </div>

        <div class="group">
            <label for="username" class="label">Username: </label>
            <input type="text" name="username" value="<?php echo $username; ?>"><br><br>
            <span class="error"> <?php echo $unameErr; ?></span><br><br>
        </div>
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

        <div class="group">
            <label for="gender" class="label">Gender: </label>
            <input type="radio" name="gender" <?php if (isset($gender) && $gender == "female")
                                                    echo "checked"; ?> value="female">Female
            <input type="radio" name="gender" <?php if (isset($gender) && $gender == "male")
                                                    echo "checked"; ?> value="male">Male
            <input type="radio" name="gender" <?php if (isset($gender) && $gender == "other")
                                                    echo "checked"; ?> value="other">Other
            <span class="error"> <?php echo $genderErr; ?></span>
            <br><br>

        </div>

        <div class="group">
            <label for="country" class="label">Country</label>
            <?php
            $sql = "SELECT countryname,countrycode FROM countries";
            $s = $pdo->prepare($sql);
            $s->execute();
            $con = $s->fetchAll();
            ?>
            <select name="country">
                <?php foreach ($con as $user) : ?>

                <option value='<?= $user['countrycode']; ?>'><?= $user['countryname']; ?></option>
                <?php endforeach; ?>
            </select>
            <br><br>
        </div>

        </div>
        <div class="group">
            <button type="submit" name="submit" value="Submit">Sign Up</button>
        </div>
        <br><br>

        <p>Aready a member? <a href='signin.php'>Sign In</a></p>
        <p>Wanna go back? <a href='index.php'>Home</a></p>

    </form>
    <?php

}
?>

</body>

</html> 