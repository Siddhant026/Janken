<?php

include '/xampp/htdocs/janken/includes/auth.php';
include '/xampp/htdocs/janken/includes/dbh.php';
include '/xampp/htdocs/janken/models/signup.mod.php';
include '/xampp/htdocs/janken/controllers/signup.contr.php';

$usernameErr = $passwordErr = $cpasswordErr = "";
$username = $password = $cpassword = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = test_input($_POST["username"]);
    $password = test_input($_POST["password"]);
    $cpassword = test_input($_POST["cpassword"]);

    $signup = new SignupContr($username, $password, $cpassword);

    $signup->signupUser();

    $usernameErr = $signup->usernameErr;
    $passwordErr = $signup->passwordErr;
    $cpasswordErr = $signup->cpasswordErr;
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Janken</title>
    <style>
        body {
            background-image: url('/janken/assets/background.jpg');
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Janken</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/janken/game.php">Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <br>
    <br>
    <br>
    <h1 style="text-align: center;">Sign Up</h1>
    <div class="container d-flex justify-content-center align-items-center">
        <div class="card" style="width: 380px;
    border: none;
    border-radius: 15px;
    padding: 8px;
    background-color: #fff;
    position: relative;
    min-height: 100px;
overflow: hidden;">
            <form name="signup" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" id="username" aria-describedby="username" pattern="[a-zA-Z ]*" title="Only letters and white space allowed" required>
                    <?php
                    if (!empty($usernameErr)) {
                        echo "$usernameErr";
                    }
                    ?>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
                    <?php
                    if (!empty($passwordErr)) {
                        echo "$passwordErr";
                    }
                    ?>
                </div>
                <div class="mb-3">
                    <label for="cpassword" class="form-label">Confirm Password</label>
                    <input type="password" name="cpassword" class="form-control" id="cpassword" title="Must match the password" required>
                    <?php
                    if (!empty($cpasswordErr)) {
                        echo "$cpasswordErr";
                    }
                    ?>
                </div>
                <button type="submit" class="btn btn-primary">Signup</button>
                <div id="login message" class="form-text">Already have an account ? <a href="/janken/login.php">Login</a></div>
            </form>
        </div>
    </div>
</body>

</html>