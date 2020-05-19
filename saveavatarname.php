<?php

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
        echo "11: Forbidden characters used in username";
        exit();
    }

   	$avatarnamedirty = $_POST[avatarname];
    $avatar = filter_var($avatarnamedirty, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
    if ($avatarnamedirty != $avatarname) {
        echo "13: Forbidden characters used in avatarname";
        exit();
    }
        
    //double check there is only one user with this name
    $namecheckquery = "SELECT username FROM players WHERE username='" . $username . "';";

    $namecheck = mysqli_query($con, $namecheckquery) or die("2: Name check query failed");    //error code #2 = name check query failed

    if (mysqli_num_rows($namecheck) > 1) {
    	echo "5: Either no user with name, or more than one.";  //error code #5 = number of names matching does not equal 1
    	exit();
    }

    $updatequery = "UPDATE players SET avatarname = " . $avatarname . " WHERE username = '" . $username . "';";
    mysqli_query($con, $updatequery) or die("14: Save avatarname query failed"); //error code #7 = update query failed

    echo "0";

?>