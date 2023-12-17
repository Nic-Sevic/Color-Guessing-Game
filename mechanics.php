<?php

/*
* 
* 
*/
$numPlayers;
$playerNames = array();
$players = array();
$boardSize;
$boardColors = array();

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

function playerInitiation() {
	global $numPlayers;
	global $playerNames;

	if (isset($_POST['playerNames']) && isset($_POST['numPlayers'])) {
		$playerNames = $_POST['playerNames'];
		$numPlayers = $_POST['numPlayers'];
	} else {
		$numPlayers = 2;
		$playerNames = array('Player 1', 'Player 2');
	}
	return playerGeneration();
}

function playerGeneration() {
	global $players;
	global $playerNames;
	global $numPlayers;

	for ($i = 0; $i < $numPlayers; $i++) {
		$players[$i] = new Player();
		$players[$i]->setName($playerNames[$i]);
		$players[$i]->setScore(0);
		$players[$i]->setGuess('');
	}
	return $players;
}


// generation of game board
function boardGeneration() {
	global $boardSize;

	if (isset($_POST['boardSize'])) {
		if ($boardSize % $boardSize != $boardSize) {
			echo "Board size must be a square number";
		} else {
			$boardSize = $_POST['boardSize'];
		}
	} else {
		$boardSize = 4;
	}
	return colorGeneration();
}

// generation of colors for board
function colorGeneration() {
	global $boardSize;
	global $boardColors;

	$hexTable = array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f');
	for ($i = 0; $i < $boardSize; $i++) {
		$color = array_rand($hexTable, 6);
		array_push($boardColors, $color);
	}
}

// regenerates the colors
if (isset($_GET['regenerate'])) {
	colorGeneration();
}

// assignment of color 
function colorAssignment() {
	global $boardColors;
	$colorAssignment = array_rand($boardColors, 1);
	return $colorAssignment;
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
// TODO: do we really need to keep this?, yes if played in different locations because need to submit to other player
function clueSubmission() {
	$clue = $_POST['clue'];
	return $clue;
	// TODO trigger guess prompts ?
}

// guessing of color; all guesses submitted at same time
// TODO: need to make so multiple players can guess and scoring only happens after all guesses submitted
function guessSubmission() {
	global $players;

	if (isset($_POST['guess'])) {
		// TODO skip clue giver?
		$guess = $_POST['guess'];
		$players[i]->setGuess($guess);
		// trigger comparison after all guesses submitted
		pointScoring();
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
			continue;
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
	gameOver();
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
		return $winner;
	}
}
