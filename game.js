// TODO: add a check that makes sure player names match the number of players


// start the game and generate the board
document.getElementById('gameStart').addEventListener('submit', function (event) {
  event.preventDefault();
  var formData = new FormData(document.getElementById('gameStart'));
  console.log([...formData]);

// [...formData]
// 0: (2) ['numPlayers', '2']
// 1: (2) ['playerNames', 'No, Nope']
// 2: (2) ['boardSize', '3']

// fetch('mechanics.php', {
//   method: 'POST',
//   body: `functionname=playerInitiation&arg1=${formData.get('numPlayers')}&arg2=${formData.get('playerNames')}`
//   })

//   .then(response => response.json())
//   .then(data => {
//     console.log('data:'+ data);
//   })
//   .catch(error => console.error('Error:', error));

  $.ajax({
    url: 'mechanics.php',
    type: 'POST',
    data: { functionname: 'playerGeneration', players: formData.get('players'), names: formData.get('playerNames'), boardSize: formData.get('boardSize') },
    success: function (response) {
      let players = JSON.parse(response);
      for (let i = 0; i < players.length; i++) {
        let child = document.createElement('div');
        child.classList.add('playerScore');
        child.innerHTML = players[i].name + ' : ' + players[i].score;
        document.getElementById('scoreBoard').appendChild(child);
      };
    },
    error: function (error) {
      console.log(error);
    }
  });
});

  // startGame();

// function startGame() {
//   fetch('mechanics.php?action=boardGeneration')
//     .then(response => response.json())
//     .then(data => {
//       console.log($board)//create divs for the players
//     })
//     .catch(error => console.error('Error:', error));
// }

// take the guesses
// score the round
// game over

// regenerate the board
