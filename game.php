<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
} else {
    $username = $_SESSION['username'];
    $sessionTime = time() - 1800;
    if ($sessionTime > $_SESSION["time"]) { //subtract new timestamp from the old one
        echo "<script>alert('You have been inactive for too long!');</script>";
        header("location: logout.php");
        exit;
    }
}

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

$sql = "select * from users where username='$username'";
$result  = mysqli_query($conn, $sql);
$num = mysqli_num_rows($result);
if ($num >= 1) {
    while ($row = mysqli_fetch_assoc($result)) {
        $uid = $row['uid'];
        //echo $uid;
    }
} else {
    echo "no output";
}

$sql = "select * from leaderboard where uid='$uid'";
$result  = mysqli_query($conn, $sql);
$num = mysqli_num_rows($result);
if ($num >= 1) {
    while ($row = mysqli_fetch_assoc($result)) {
        $wins = $row['wins'];
        $losses = $row['losses'];
    }
} else {
    echo "no output";
}

$sql = "select * from choices where uid='$uid'";
$result  = mysqli_query($conn, $sql);
$num = mysqli_num_rows($result);
if ($num >= 1) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rock = $row['rock'];
        $paper = $row['paper'];
        $scissors = $row['scissors'];
    }
} else {
    echo "no output";
}

// $scores = $_POST['score'];
// $wins = $scores["wins"];
// $losses = $scores["losses"];

// $sql = "UPDATE leaderboard SET wins = '$wins', losses= '$losses' where uid='$uid'";
// $result  = mysqli_query($conn, $sql);


// $scores = $_POST['score'];
// echo $scores["losses"];

// echo $scores["wins"];
// foreach($scores as $score){
//     echo $score;
// }

// if (!empty($_POST['wins'])) {
//     $playerWins = $_POST['wins'];
//     echo $playerWins;
// } else {
//     echo "not found";
// }



// $result = "";

// function compare($playerChoice, $computerChoice)
// {
//     if ($playerChoice === $computerChoice) {
//         $GLOBALS["result"] = "Its a tie";
//     }
//     if ($playerChoice === "rock") {
//         if ($computerChoice === "scissors") {
//             $GLOBALS["result"] = "Player Wins";
//             $GLOBALS["wins"] = $GLOBALS["wins"] + 1;
//         }
//         if ($computerChoice === "paper") {
//             $GLOBALS["result"] = "Computer Wins";
//             $GLOBALS["losses"] = $GLOBALS["losses"] + 1;
//         }
//     }
//     if ($playerChoice === "paper") {
//         if ($computerChoice === "rock") {
//             $GLOBALS["result"] = "Player Wins";
//             $GLOBALS["wins"] = $GLOBALS["wins"] + 1;
//         }
//         if ($computerChoice === "scissors") {
//             $GLOBALS["result"] = "Computer Wins";
//             $GLOBALS["losses"] = $GLOBALS["losses"] + 1;
//         }
//     }
//     if ($playerChoice === "scissors") {
//         if ($computerChoice === "paper") {
//             $GLOBALS["result"] = "Player Wins";
//             $GLOBALS["wins"] = $GLOBALS["wins"] + 1;
//         }
//         if ($computerChoice === "rock") {
//             $GLOBALS["result"] = "Computer Wins";
//             $GLOBALS["losses"] = $GLOBALS["losses"] + 1;
//         }
//     }
// }

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     if ($_POST["rock"] == true) {
//         $playerChoice = "rock";
//     }
//     if ($_POST["paper"] == true) {
//         $playerChoice = "paper";
//     }
//     if ($_POST["scissors"] == true) {
//         $playerChoice = "scissors";
//     }
//     $computerNumber = rand(0, 2);
//     $choice = ["rock", "paper", "scissors"];
//     $computerChoice = $choice[$computerNumber];
//     compare($playerChoice, $computerChoice);
//     $_POST["rock"] = false;
//     $_POST["paper"] = false;
//     $_POST["scissors"] = false;
// }

// $wins = $_POST["wins"];

//echo "$wins";


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
    <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'>
    </script>
    <style>
        body {
            /* background-image: url('/janken/assets/background.jpg'); */
            background-color: rgba(240, 242, 230, 1);
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        @keyframes fadeinFadeout {
            0% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        @keyframes shakePlayer {
            0% {
                transform: rotate(270deg) rotateX(180deg) translateY(0px);
            }

            15% {
                transform: rotate(270deg) rotateX(180deg) translateY(-50px);
            }

            25% {
                transform: rotate(270deg) rotateX(180deg) translateY(0px);
            }

            35% {
                transform: rotate(270deg) rotateX(180deg) translateY(-50px);
            }

            50% {
                transform: rotate(270deg) rotateX(180deg) translateY(0px);
            }

            65% {
                transform: rotate(270deg) rotateX(180deg) translateY(-50px);
            }

            75% {
                transform: rotate(270deg) rotateX(180deg) translateY(0px);
            }

            85% {
                transform: rotate(270deg) rotateX(180deg) translateY(-50px);
            }

            100% {
                transform: rotate(270deg) rotateX(180deg) translateY(0px);
            }
        }

        @keyframes shakeComputer {
            0% {
                transform: rotate(270deg) translateY(0px);
            }

            15% {
                transform: rotate(270deg) translateY(-50px);
            }

            25% {
                transform: rotate(270deg) translateY(0px);
            }

            35% {
                transform: rotate(270deg) translateY(-50px);
            }

            50% {
                transform: rotate(270deg) translateY(0px);
            }

            65% {
                transform: rotate(270deg) translateY(-50px);
            }

            75% {
                transform: rotate(270deg) translateY(0px);
            }

            85% {
                transform: rotate(270deg) translateY(-50px);
            }

            100% {
                transform: rotate(270deg) translateY(0px);
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="position: fixed;
    top: 0px;
    width: 100%;
    opacity: 1
    ">
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
                        <a class="nav-link" href="/janken/leaderboard.php">Leaderboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/janken/profile.php"><?php echo "$username"; ?></a>
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
                <ul class="navbar-nav text-right">
                    <li class="nav-item">
                        <a class="nav-link" href="/janken/logout.php">Logout</a>
                    </li>
                </ul>
                <!-- <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form> -->
            </div>
        </div>
    </nav>
    <br>
    <br>
    <br>
    <section class="game">
        <div class="score" style="height: 20vh;
                                display: flex;
                                justify-content: space-around;
                                align-items: center;">
            <div class="player-score">
                <h2>Player</h2>
                <p style="text-align: center;
                            padding: 10px;
                            font-size: 25px;">0</p>
            </div>
            <div class="computer-score">
                <h2>Computer</h2>
                <p style="text-align: center;
                            padding: 10px;
                            font-size: 25px;">0</p>
            </div>
        </div>
        <div class="chants">
            <div class="jan" style="opacity: 0;">
                <h1 style="text-align: center;">Jan</h1>
            </div>
            <div class="ken" style="transform: translate(0px, -56px); opacity: 0; animation-delay: 1s">
                <h1 style="text-align: center;">Ken</h1>
            </div>
            <div class="sho" style="transform: translate(0px, -112px); opacity: 0;">
                <h1 style="text-align: center;">Sho!</h1>
            </div>
        </div>
        <div class="match" style="transform: translate(0px, -112px);">
            <div class="hands" style="display: flex; align-items:center; justify-content: space-around">
                <img class="player-hand" src="./assets/rock.jpeg" alt="" style="width: 200px; height: 200px;transform: rotate(270deg) rotateX(180deg);" />
                <span class="result" style="opacity: 1;"></span>
                <img class="computer-hand" src="./assets/rock.jpeg" alt="" style="width: 200px; height: 200px; transform: rotate(270deg)" />
            </div>
            <div class="options" style="display: flex;
                                        justify-content: space-around;
                                        align-items: center;">
                <span></span>
                <span></span>
                <!-- <div>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <input type="hidden" id="rock" name="rock" value="true">
                        <input type="submit" value="Rock" style="background-color: black; 
                                            border: none;
                                            color: white;
                                            padding: 15px 32px;
                                            text-align: center;
                                            text-decoration: none;
                                            display: inline-block;
                                            font-size: 16px;
                                            border-radius: 8px">
                </div>
                <div>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <input type="hidden" id="paper" name="paper" value="true">
                        <input type="submit" value="Paper" style="background-color: black; 
                                            border: none;
                                            color: white;
                                            padding: 15px 32px;
                                            text-align: center;
                                            text-decoration: none;
                                            display: inline-block;
                                            font-size: 16px;
                                            border-radius: 8px">
                </div>
                <div>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <input type="hidden" id="scissors" name="scissors" value="true">
                        <input type="submit" value="Scissors" style="background-color: black; 
                                            border: none;
                                            color: white;
                                            padding: 15px 32px;
                                            text-align: center;
                                            text-decoration: none;
                                            display: inline-block;
                                            font-size: 16px;
                                            border-radius: 8px">
                </div> -->
                <button class="rock" style="background-color: black; 
                                            border: none;
                                            color: white;
                                            padding: 15px 32px;
                                            text-align: center;
                                            text-decoration: none;
                                            display: inline-block;
                                            font-size: 16px;
                                            border-radius: 8px">rock</button>
                <button class="paper" style="background-color: black; 
                                            border: none;
                                            color: white;
                                            padding: 15px 32px;
                                            text-align: center;
                                            text-decoration: none;
                                            display: inline-block;
                                            font-size: 16px;
                                            border-radius: 8px">paper</button>
                <button class="scissors" style="background-color: black; 
                                            border: none;
                                            color: white;
                                            padding: 15px 32px;
                                            text-align: center;
                                            text-decoration: none;
                                            display: inline-block;
                                            font-size: 16px;
                                            border-radius: 8px">scissors</button>
                <span></span>
                <span></span>
            </div>
        </div>
        <div></div>
    </section>
    <!-- <section class="profile-leaderboard" style="display: flex; justify-content: center; align-items: flex-start">
        <section style="width: 10%;"></section>
        <section class="profile" style="width:30%; display: flex; flex-direction: column; justify-content: flex-start; align-items: flex-start">
            <h2>
                Profile
            </h2>
            <div style="display: flex;">
                <div style="width: 100px;"></div>
                <div style="display: flex; flex-direction: column; align-items: center;">
                    <h4>Siddhant</h4>
                    <img src="/janken/assets/profile.png" alt="" style="width: 150px;">
                    <div style="display: flex;">
                        <div>
                            Wins: 100
                        </div>
                        <div style="width: 20px;"></div>
                        <div>Losses: 0</div>
                    </div>
                </div>
            </div>

        </section>
        <section style="width: 10%;"></section>
        <section class="leaderboard" style="width: 30%;">
            <h2>LeaderBoard</h2>
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Username</th>
                        <th>K/D</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tr>
                    <td>1</td>
                    <td>Siddhant</td>
                    <td>2</td>
                    <td>Online</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Siddhant</td>
                    <td>2</td>
                    <td>Online</td>
                </tr>
            </table>
        </section>
        <section style="width: 10%;"></section>
    </section> -->
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> -->
    <!-- <script src="/janken/app.js"></script> -->
    <script>
        $(document).ready(function() {

            // var sessionC = window.setInterval(sessionCheck, 60000);

            // function sessionCheck() {
            //     // $.ajax({
            //     //     url: "time.php",
            //     //     type: "POST",
            //     //     success: function(data, status) {
            //     //         console.log(status);
            //     //     }
            //     // });
            //     var x = "<?php
                            //                 $sessionTime = time() - 55;
                            //                 if ($sessionTime > $_SESSION["time"]) { //subtract new timestamp from the old one
                            //                     echo "<script>alert('15 Minutes over!');</script>";
                            //                     header("location: logout.php");
                            //                     exit;
                            //                 }

                            //                 echo "";
                            //                 
                            ?>";
            // }

            $.ajax({
                url: "time.php",
                type: "POST",
                success: function(data, status) {
                    console.log(status);
                }
            });

            var intervalID = window.setInterval(sendSignal, 5000);

            function sendSignal() {
                $.ajax({
                    url: "time.php",
                    type: "POST",
                    success: function(data, status) {
                        console.log(status);
                    }
                });
            }

            const game = () => {
                var playerScore = <?php echo $wins; ?>;
                var computerScore = <?php echo $losses; ?>;
                var playerChoice = "";
                var computerChoice = "";

                const match = () => {
                    const options = document.querySelectorAll(".options button");
                    const playerHand = document.querySelector(".player-hand");
                    const computerHand = document.querySelector(".computer-hand");
                    const jan = document.querySelector(".jan");
                    const ken = document.querySelector(".ken");
                    const sho = document.querySelector(".sho");
                    const chants = document.querySelector(".chants");
                    const result = document.querySelector(".result");

                    // chants.forEach(chant => {
                    //     chant.addEventListener("animationend", function() {
                    //         this.style.animation = "";
                    //     });
                    // });
                    jan.addEventListener("animationend", function() {
                        this.style.animation = "";
                    });
                    ken.addEventListener("animationend", function() {
                        this.style.animation = "";
                    });
                    playerHand.addEventListener("animationend", function() {
                        this.style.animation = "";
                    });
                    computerHand.addEventListener("animationend", function() {
                        this.style.animation = "";
                    });

                    const computerOptions = ["rock", "paper", "scissors"];

                    setTimeout(function() {
                        alert("You have not played game for 30 minutes");
                        location.replace("/janken/logout.php")
                    }, 1800000);

                    options.forEach(option => {
                        option.addEventListener("click", function() {
                            playerHand.src = `./assets/rock.jpeg`;
                            computerHand.src = `./assets/rock.jpeg`;
                            //const computerChoice = computerOptions[randomIntFromInterval(0, 2)];
                            playerChoice = this.textContent;
                            computerChoice = computerLogic();
                            //console.log(this.textContent);
                            //console.log(computerChoice);
                            //result.style.opacity = 0;
                            jan.style.animation = "fadeinFadeout 1s";
                            playerHand.style.animation = "shakePlayer 2s";
                            computerHand.style.animation = "shakeComputer 2s";
                            setTimeout(function() {
                                ken.style.animation = "fadeinFadeout 1s";
                            }, 1000);
                            setTimeout(function() {
                                sho.style.opacity = 1;
                            }, 2000);

                            setTimeout(function() {
                                compare(playerChoice, computerChoice, result);
                                // playerHand.src = './assets/${this.textContent}.jpeg';
                                playerHand.src = `./assets/${playerChoice}.jpeg`;
                                computerHand.src = `./assets/${computerChoice}.jpeg`;
                            }, 2000);
                            sho.style.opacity = 0;
                            result.textContent = "";
                            setTimeout(function() {
                                alert("You have not played game for 30 minutes");
                                location.replace("/janken/logout.php")
                            }, 1800000);
                            var x = "<?php
                                        $_SESSION["time"] = time();
                                        echo "php running";
                                        ?>";
                            //alert(x);
                        });
                    });
                }

                const computerLogic = () => {
                    var rock = "<?php echo $rock; ?>";
                    var paper = "<?php echo $paper; ?>";
                    var scissors = "<?php echo $scissors; ?>";
                    var computerC = "";

                    if ((rock > paper) && (rock > scissors)) {
                        //alert('no. of rock is more');
                        const computerOptions = ["rock", "paper"];
                        computerC = computerOptions[randomIntFromInterval(0, 1)];
                    } else if ((paper > rock) && (paper > scissors)) {
                        //alert('no. of paper is more');
                        const computerOptions = ["scissors", "paper"];
                        computerC = computerOptions[randomIntFromInterval(0, 1)];
                    } else if ((scissors > rock) && (scissors > paper)) {
                        //alert('no. of scissors is more');
                        const computerOptions = ["scissors", "rock"];
                        computerC = computerOptions[randomIntFromInterval(0, 1)];
                    } else {
                        const computerOptions = ["rock", "paper", "scissors"];
                        computerC = computerOptions[randomIntFromInterval(0, 2)];
                    }
                    return computerC;
                }

                function randomIntFromInterval(min, max) { // min and max included 
                    return Math.floor(Math.random() * (max - min + 1) + min)
                }

                const compare = (playerChoice, computerChoice, result) => {
                    //const result = document.querySelector(".result");


                    if (playerChoice === computerChoice) {
                        result.textContent = "Its a tie";
                        return;
                    }
                    if (playerChoice === "rock") {
                        if (computerChoice === "scissors") {
                            result.textContent = "Player Wins";
                            playerScore++;
                            updateScore();
                            return;
                        } else {
                            result.textContent = "Computer Wins";
                            computerScore++;
                            updateScore();
                            return;
                        }
                    }
                    if (playerChoice === "paper") {
                        if (computerChoice === "rock") {
                            result.textContent = "Player Wins";
                            playerScore++;
                            updateScore();
                            return;
                        } else {
                            result.textContent = "Computer Wins";
                            computerScore++;
                            updateScore();
                            return;
                        }
                    }
                    if (playerChoice === "scissors") {
                        if (computerChoice === "paper") {
                            result.textContent = "Player Wins";
                            playerScore++;
                            updateScore();
                            return;
                        } else {
                            result.textContent = "Computer Wins";
                            computerScore++;
                            updateScore();
                            return;
                        }
                    }
                }

                function passVal() {
                    //console.log(playerScore);
                    var score = {
                        wins: playerScore,
                        losses: computerScore,
                        choice: playerChoice
                    };
                    //console.log(playerScore);
                    $.ajax({
                        url: "update.php",
                        type: "POST",
                        //dataType: JSON,
                        //async: false,
                        data: {
                            'score': score
                        },

                        success: function(data, status) {
                            console.log("update status "+status);
                            //window.location="update.php";
                        }
                    })
                    // $.post("game.php", "hello=hi", function(data, status) {
                    //     alert(data);
                    // });
                }

                function setCookie(cname, cvalue, exdays) {
                    const d = new Date();
                    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
                    let expires = "expires=" + d.toUTCString();
                    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
                }

                const updateScore = () => {
                    const playerS = document.querySelector(".player-score p");
                    const computerS = document.querySelector(".computer-score p");
                    playerS.textContent = playerScore;
                    computerS.textContent = computerScore;
                    passVal();
                    //setCookie("wins", playerScore.toString(), "1");
                    //setCookie("losses", computerScore.toString(), "1");
                }

                updateScore();

                match();
            }

            game();
        });
    </script>
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>