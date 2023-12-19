
// start the game and generate the board
document.getElementById('gameStart').addEventListener('submit', function (event) {
  event.preventDefault();
  var formData = new FormData(event.target);

// [...formData]
// 0: (2) ['numPlayers', '2']
// 1: (2) ['playerNames', 'No, Nope']
// 2: (2) ['boardSize', '3']

fetch('mechanics.php', {
  method: 'POST',
  body: `functionname=playerInitiation&arg1=${formData.get('numPlayers')}&arg2=${formData.get('playerNames')}`
  })

  .then(response => response.json())
  .then(data => {
    console.log('data:'+data);
  })
  //.catch(error => console.error('Error:', error));
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
