<?php
$dbserver = "localhost";
$dbusername = "root";
$dbpassword = "";
$database = "janken";
$port = 3307;

$conn = mysqli_connect($dbserver, $dbusername, $dbpassword, $database, $port);

if ($conn) {
    //echo "Success";
} else {
    die("error" . mysqli_connect_error());
}

$usernameErr = $passwordErr = "";
$username = $password = "";
$usernameCorr = false;
$passCorr = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
    } else if (!preg_match("/^[a-zA-Z-' ]*$/", $_POST["username"])) {
        $usernameErr = "Only letters and white space allowed";
    } else {
        $username = test_input($_POST["username"]);
        $usernameCorr = true;
    }

    $password = $_POST["password"];

    //     $username = test_input($_POST["username"]);
    //     // check if username only contains letters and whitespace
    //     if (!preg_match("/^[a-zA-Z-' ]*$/", $username)) {
    //         $usernameErr = "Only letters and white space allowed";
    //     }
    // }

    // if (!empty($_POST["password"]) && ($_POST["password"] == $_POST["cpassword"])) {
    //     $password = test_input($_POST["password"]);
    //     $cpassword = test_input($_POST["cpassword"]);
    //     $passCorr = true;
    //     if (strlen($_POST["password"]) <= '8') {
    //         $passwordErr = "Your Password Must Contain At Least 8 Characters!";
    //         $passCorr = false;
    //     } elseif (!preg_match("#[0-9]+#", $password)) {
    //         $passwordErr = "Your Password Must Contain At Least 1 Number!";
    //         $passCorr = false;
    //     } elseif (!preg_match("#[A-Z]+#", $password)) {
    //         $passwordErr = "Your Password Must Contain At Least 1 Capital Letter!";
    //         $passCorr = false;
    //     } elseif (!preg_match("#[a-z]+#", $password)) {
    //         $passwordErr = "Your Password Must Contain At Least 1 Lowercase Letter!";
    //         $passCorr = false;
    //     }
    // } elseif (!empty($_POST["password"])) {
    //     $cpasswordErr = "Please Check You've Entered Or Confirmed Your Password!";
    //     $passCorr = false;
    // } else {
    //     $passwordErr = "Please enter password   ";
    //     $passCorr = false;
    // }

    if ($usernameCorr) {
        //$hash = password_hash($password, PASSWORD_DEFAULT);
        //$sql = "INSERT INTO `users` (`username`, `password`) VALUES ('$username', '$hash');";
        //$sql = "select * from users where username='$username' and password='$password'";
        $sql = "select * from users where username='$username'";
        $result  = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);
        if ($num == 1) {
            while($row=mysqli_fetch_assoc($result)){
                if (password_verify($password, $row['password'])){
                    $login = true;
                    session_start();
                    $_SESSION['loggedin'] = true;
                    $_SESSION['username'] = $username;
                    header("location: game.php");
                }
            }
            
            //echo "account created";
        } else {
            //echo "account NOT created";
        }
    }
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
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="#">Profile</a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" href="#">Leaderboard</a>
                    </li>
                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Dropdown
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>-->
                    <!-- <li class="nav-item">
                        <a class="nav-link disabled">Disabled</a>
                    </li>  -->
                </ul>
                <!-- <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form> -->
            </div>
        </div>
    </nav>
    <br>
    <div class="container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" class="form-control" id="username" aria-describedby="username">
                <?php
                if (!empty($usernameErr)) {
                    echo "$usernameErr";
                }
                ?>
                <!-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> -->
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password">
                <?php
                if (!empty($passwordErr)) {
                    echo "$passwordErr";
                }
                ?>
            </div>
            <!-- <div class="mb-3">
                <label for="cpassword" class="form-label">Confirm Password</label>
                <input type="password" name="cpassword" class="form-control" id="cpassword">
                
                // if (!empty($cpasswordErr)) {
                //     echo "$cpasswordErr";
                // }
                
            </div> -->
            <!-- <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Check me out</label>
            </div> -->
            <button type="submit" class="btn btn-primary">Login</button>
            <div id="signup message" class="form-text">Don't have an account ? <a href="/janken/signup.php">Signup</a></div>
        </form>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>