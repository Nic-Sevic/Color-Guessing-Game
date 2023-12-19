<?php
echo('mechanics.php started');
/*
* 
* 
*/

$numPlayers;
$playerNames = array();
$players = array();
$boardSize;
$boardColors = array();

// accept requests from game.js
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
	
	// $boardColors = json_decode(boardGeneration());
	// $colorAssignment = json_decode(colorAssignment());
	// $turn = turnAssignment();
	// $clue = json_decode(clueSubmission());
	// $guess = json_decode(guessSubmission());
	// $gameOver = gameOver();
	$functionname = $_POST['function'];
	$arg1 = $_POST['arg1'];
	$arg2 = $_POST['arg2'];

	call_user_func($functionname, $arg1, $arg2);
}

// initiation of players, takes from form submission or defaults to 2 players
class Player {
	private $name;
	private $score;
	private $guess;

	public function setName($name) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}	

	public function setScore($score) {
		$this->score = $score;
	}

	public function getScore() {
		return $this->score;
	}

	public function setGuess($guess) {
		$this->guess = $guess;
	}

	public function getGuess() {
		return $this->guess;
	}
}

 function playerInitiation($numPlayers, $playerNames) {
	echo 'playerInitiation started';
// 	global $numPlayers;
// 	global $playerNames;

	// commmented out while trying a different fetch method
	// if (isset($_POST['playerNames']) && isset($_POST['numPlayers'])) {
	// 	$playerNames = $_POST['playerNames'];
	// 	$numPlayers = $_POST['numPlayers'];
	// } else {
	// 	$numPlayers = 2;
	// 	$playerNames = array('Player 1', 'Player 2');
	// }
	return playerGeneration($numPlayers, $playerNames);
}

function playerGeneration($numPlayers, $playerNames) {
	echo 'playerGeneration started';
	global $players;
	// global $playerNames;
	// global $numPlayers;

	for ($i = 0; $i < $numPlayers; $i++) {
		$players[$i] = new Player();
		$players[$i]->setName($playerNames[$i]);
		$players[$i]->setScore(0);
		$players[$i]->setGuess('');
	}
	return json_encode($players);
}


// generation of game board
function boardGeneration() {
	global $boardSize;
	$board = array();

	if (isset($_POST['boardSize'])) {
		$boardSize = $_POST['boardSize'];
	} else {
		$boardSize = 2;
	}

	// create board object
	$boardColumns = sqrt($boardSize);
	$boardSquares = array();
	for ($i = 0; $i < $boardColumns; $i++) {
		for ($j = 0; $j < $boardColumns; $j++){
			$key = $i . $j;
			array_push($boardSquares, $key);
		}
	}

	return colorGeneration($boardSquares);
}

// generation of colors for board
function colorGeneration($boardSquares) {
	$colors = array();
	$hexValues = array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f');

	while (count($colors) < $boardSquares) {
		$color = array_rand($hexValues, 6);
		array_push($colors, '#' . $color);
		array_unique($colors);
	}

	sort($colors);
	$board = array_combine($boardSquares, $colors);
	
	return json_encode($board);
}

// assignment of color 
function colorAssignment() {
	global $boardColors;

	$colorAssignment = array_rand($boardColors, 1);
	return json_encode($colorAssignment);
}

// turn assignment
$turn = 0;
function turnAssignment() {
	global $turn;
	global $numPlayers;

	if ($turn < $numPlayers) {
		$turn++;
	} else {
		$turn = 0;
	}
}

// submission of clue
function clueSubmission() {
	$clue = $_POST['clue'];
	return json_encode($clue);
}

// guessing of color; all guesses submitted at same time
// TODO: need to make so multiple players can guess and scoring only happens after all guesses submitted
// right now set up for only one player to guess
function guessSubmission() {
	global $players;
	global $turn;
	$guesses = array();

	if (isset($_POST['guess'])) {
		// TODO skip clue giver?
		$guess = $_POST['guess'];
		// really wonky way to get guess into player object
		// TODO absolutely need to fix this
		foreach ($players as $player) {
			$player->setGuess($guess);
		}
		$players[$turn]->setGuess('');
		// trigger comparison after all guesses submitted
		return pointScoring();
	}
}

// scoring of points
function pointScoring(){
	global $numPlayers;
	global $turn;
	global $colorAssignment;
	global $players;

	for ($i = 0; $i < $numPlayers; $i++) {
		// skip clue giver
		if ($i == $turn) {
			if ($i != $numPlayers - 1){
				break;
			} else {
				$i++;
			}
		}
		// player and clue giver earn pt for matching
		if ($players[$i]->getGuess() == $colorAssignment) {
			$players[$i]->setScore($players[$i]->getScore() + 1);
			// increment for clue giver
			$players[$turn]->setScore($players[$turn]->getScore() + 1);
		} else {
			// player loses a pt for guessing wrong
			if ($players[$i]->getScore() > 0) {
				$players[$i]->setScore($players[$i]->getScore() - 1);
			}
		}
	}
	return gameOver();
}

// definition of game over
function gameOver() {
	global $players;
	$winner = array();
	
	foreach ($players as $player) {
		if ($player->getScore() == 10) {
			return array_push($winner, $player->getName());
		}
	}
	if (count($winner) == 0) {
		return false;
	} else {
		return json_encode($winner);
	}
}
