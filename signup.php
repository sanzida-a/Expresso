<?php
include("connection.php");

$fnameErr = $lnameErr = $unameErr = $emailErr = $passErr = $genderErr =  "";
$fname = $lname = $username = $email = $gender = $password = "";
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$sql1 = "SELECT countryname,countrycode FROM countries";
$s1 = $pdo->prepare($sql1);
$s1->execute();
$con1 = $s1->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
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

        $sql = "INSERT INTO users (fname, lname dob, email, username, password, gender, country)
         VALUES (:fname, :lname, :dob, :email, :username, :password, :gender, :country)";
        $s = $pdo->prepare($sql);

        $s->bindValue(':fname', $name);
        $s->bindValue(':lname', $name);
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
    <!DOCTYPE html>
    <html>

    <head>
        <title>Sign Up</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <!-- Icons font CSS-->
        <link href="temp/vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
        <link href="temp/vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
        <!-- Font special for pages-->
        <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

        <!-- Vendor CSS-->
        <link href="temp/vendor/select2/select2.min.css" rel="stylesheet" media="all">
        <link href="temp/vendor/datepicker/daterangepicker.css" rel="stylesheet" media="all">

        <!-- Main CSS-->
        <link href="temp/css/main.css" rel="stylesheet" media="all">
    </head>

    <div class="page-wrapper bg-gra-02 p-t-130 p-b-100 font-poppins">
        <div class="wrapper wrapper--w680">
            <div class="card card-4">
                <div class="card-body">
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="myform">
                        <h2 class="title">Sign Up</h2>
                        <p><span class="error"></span></p>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">First name</label>
                                    <input class="input--style-4" type="text" id="fname" name="fname" placeholder="ex: Lindsey" required>
                                    <span class="error"> <?php echo $fnameErr; ?></span><br><br>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Last name</label>
                                    <input class="input--style-4" type="text" id="lname" name="lname" placeholder="ex: Wilson" required>
                                    <span class="error"> <?php echo $lnameErr; ?></span><br><br>
                                </div>
                            </div>
                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Birthday</label>
                                    <div class="input-group-icon">
                                        <input class="input--style-4 js-datepicker" type="text" id="dob" name="dob" required>
                                        <i class="zmdi zmdi-calendar-note input-icon js-btn-calendar"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Gender</label>
                                    <div class="p-t-10">
                                        <label class="radio-container m-r-45">Male
                                            <input type="radio" checked="checked" id="gender" name="gender" required>
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="radio-container">Female
                                            <input type="radio" name="gender">
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="radio-container">Other
                                            <input type="radio" name="gender">
                                            <span class="checkmark"></span>
                                        </label>
                                        <span class="error"> <?php echo $genderErr; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Email</label>
                                    <input class="input--style-4" type="email" id="email" name="email" placeholder="ex: lindseywison@gmail.com" required>
                                    <span class="error"> <?php echo $emailErr; ?></span>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Username</label>
                                    <input class="input--style-4" type="text" id="username" name="username" placeholder="ex: lindseywison" required>
                                    <span class="error"> <?php echo $unameErr; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Password: </label>
                                    <input class="input--style-4" type="password" name="password" id="password" required>
                                    <span class="error"> <?php echo $passErr; ?></span><br><br>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Confirm Password:</label>
                                    <input class="input--style-4" type="password" name="confirm_password" id="confirm_password" required>
                                </div>
                            </div>
                        </div>
                        <div class="input-group">
                            <label class="label">Country</label>
                            <div class="rs-select2 js-select-simple select--no-search">
                                <select class="input--style-4" name="country" required>
                                    <option disabled="disabled" selected="selected">Choose option</option>
                                    <?php foreach ($con1 as $user) : ?>
                                        <option value='<?= $user['countrycode']; ?>'><?= $user['countryname']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="select-dropdown"></div>
                            </div>
                        </div>
                        <div class="p-t-15">
                            <button class="btn btn--radius-2 btn--blue" type="submit" value="Submit">Submit</button>
                        </div>
                        <br><br>
                        <div class="input-group">
                            <p>Aready a member? <a href='signin.php'>Sign In</a></p>
                            <p>Wanna go back? <a href='index.php'>Home</a></p>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
}
?>
<script src="temp/vendor/jquery/jquery.min.js"></script>
<!-- Vendor JS-->
<script src="temp/vendor/select2/select2.min.js"></script>
<script src="temp/vendor/datepicker/moment.min.js"></script>
<script src="temp/vendor/datepicker/daterangepicker.js"></script>
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>
    // just for the demos, avoids form submit
    jQuery.validator.setDefaults({
        debug: true,
        success: function(label) {
            label.attr('id', 'valid');
        },
    });
    $("#myform").validate({
        rules: {
            password: "required",
            confirm_password: {
                equalTo: "#password"
            }
        },
        messages: {
            fname: {
                required: "Please provide your first name"
            },
            lname: {
                required: "Please provide your last name"
            },
            dob: {
                required: "Please provide your birthday"
            },
            gender: {
                required: "Please provide your gender"
            },
            email: {
                required: "Please provide your email"
            },
            username: {
                required: "Please provide an username"
            },
            password: {
                required: "Please provide a password"
            },
            confirm_password: {
                required: "Please provide a password",
                equalTo: "Wrong Password"
            }
        }
    });
</script>
</body>

</html>