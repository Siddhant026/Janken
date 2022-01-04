const game = () => {
    var playerScore = 0;
    var computerScore = 0;

    const match = () => {
        const options = document.querySelectorAll(".options button");
        const playerHand = document.querySelector(".player-hand");
        const computerHand = document.querySelector(".computer-hand");

        const computerOptions = ["rock", "paper", "scissors"];

        options.forEach(option => {
            option.addEventListener("click", function () {
                const computerChoice = computerOptions[randomIntFromInterval(0, 2)];
                console.log(this.textContent);
                console.log(computerChoice);
                compare(this.textContent, computerChoice);
                playerHand.src = './assets/${this.textContent}.jpeg';
                computerHand.src = './assets/${computerChoice}.jpeg';
            });
        });
    }

    function randomIntFromInterval(min, max) { // min and max included 
        return Math.floor(Math.random() * (max - min + 1) + min)
    }

    const compare = (playerChoice, computerChoice) => {
        const result = document.querySelector(".result");


        if (playerChoice === computerChoice) {
            result.textContent = "Its a tie";
            return;
        }
        if (playerChoice === "rock") {
            if (computerChoice === "scissors") {
                result.textContent = "Player Wins";
                playerScore++;
                //updateScore();
                return;
            } else {
                result.textContent = "Computer Wins";
                computerScore++;
                //updateScore();
                return;
            }
        }
        if (playerChoice === "paper") {
            if (computerChoice === "rock") {
                result.textContent = "Player Wins";
                playerScore++;
                //updateScore();
                return;
            } else {
                result.textContent = "Computer Wins";
                computerScore++;
                //updateScore();
                return;
            }
        }
        if (playerChoice === "scissors") {
            if (computerChoice === "paper") {
                result.textContent = "Player Wins";
                playerScore++;
                //updateScore();
                return;
            } else {
                result.textContent = "Computer Wins";
                computerScore++;
                //updateScore();
                return;
            }
        }
    }

    match();
}

game();
