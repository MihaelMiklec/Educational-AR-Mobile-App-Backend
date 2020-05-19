<?php
    
    $leaderboards = array(
    	// leaderboard identifier => leaderboard name in database
    	"leaderboard1" => "leaderboard1",
    	"leaderboard2" => "leaderboard2",
    	"leaderboard3" => "leaderboard3",
    	"leaderboard4" => "leaderboard4",
    	// more can be added, but there must be corresponding tables in database too
    );

    if (!array_key_exists(($_POST[leaderboardID]), $leaderboards)) {
    	echo "15: No leaderboard with such identifier";
    	exit();
    }

    $leaderboardID = $leaderboards[$_POST[leaderboardID]];

    $configs = include('dbconfig.php');
    $con = mysqli_connect($configs['host'], $configs['username'], $configs['passwd'], $configs['dbname']);

    //check that connection happened
    if (mysqli_connect_errno()) {
    	echo "1: Connection failed"; //error code #1 = connection failed
    	exit();
    }

    $dirtyusername = mysqli_real_escape_string($con, $_POST[name]);
    $username = filter_var($dirtyusername, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
    if ($dirtyusername != $username) {
    	echo "11: Forbidden characters used in username input";
    	exit();
    }

    $newscore = $_POST[score];
   	if (!is_numeric($newscore)) {
   		echo "16: Score must be a number";
   		exit();
   	}

   	$idquery = "SELECT  id FROM players WHERE username='" . $username . "';";
   	$idquerysend = mysqli_query($con, $idquery) or die("17: Id fetch query failed");
   	$idresult = mysqli_fetch_assoc($idquerysend);
    $id = $idresult["id"];

    $leaderboardresquery = "SELECT score FROM " . $leaderboardID . " WHERE id='" . $id . "';";
    $leaderboardres = mysqli_query($con, $leaderboardresquery) or die("18: Fetching score from leaderboards failed");

    //check if a user with that id was never recorded on the leaderboard. If he was not, add him to the leaderboard. 
    if (mysqli_num_rows($leaderboardres) == 0) {
    	$addscorequery = "INSERT INTO " . $leaderboardID . " (id, score) VALUES ('" . $id . "', '" . $newscore . "');";
    	mysqli_query($con, $addscorequery) or die("19: Adding a new user to the leaderboard failed");
    	echo "0";
    	exit();
    }

    $leaderboardresresult = mysqli_fetch_assoc($leaderboardres);
    $oldscore = $leaderboardresresult["score"];

    if ($oldscore >= $newscore) {
    	echo "0";
    	exit();
    }

    $updatescorequery = "UPDATE " . $leaderboardID . " SET score = " . $newscore . " WHERE id = '" . $id . "';";
    mysqli_query($con, $updatescorequery) or die("20: Updating score on leaderboard " . $leaderboardID . " failed");

    echo "0";

?>