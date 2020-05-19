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
    	echo "11: Forbidden characters used in username input";
    	exit();
    }

    $dirtyemail = mysqli_real_escape_string($con, $_POST[email]);
    $email = filter_var($dirtyemail, FILTER_SANITIZE_EMAIL);
    if ($dirtyemail != $email) {
    	echo "12: Forbidden characters used in email input";
    	exit();
    }

    $avatarnamedirty = mysqli_real_escape_string($con, $_POST[avatarname]);
    $avatarname = filter_var($avatarnamedirty, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
    if ($avatarnamedirty != $avatarname) {
        echo "13: Forbidden characters used in avatarname";
        exit();
    }

    $password = $_POST[password];
    

    //check if valid email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    	echo "8: Invalid registration e-mail";  //error code #8  = an invalid email address was provided
    	exit();
    }

    //check if name exists
    $namecheckquery = "SELECT username FROM players WHERE username='" . $username . "';";
    $namecheck = mysqli_query($con, $namecheckquery) or die("2: Name check query failed");    //error code #2 = name check query failed

    if (mysqli_num_rows($namecheck) > 0) {
    	echo "3: Name already exists"; //error code #3 = name already exists, cannot register
    	exit();
    }

    //check if email is already used
    $emailcheckquery = "SELECT email FROM players WHERE email='" . $email . "';";
    $emailcheck = mysqli_query($con, $emailcheckquery) or die("9: Email check query failed");   //error code #9 = email check query failed

    if (mysqli_num_rows($emailcheck) > 0) {
    	echo "10: Email is already being used"; //error code #10 = provided email is already used
    	exit();
    }

    //add user to the table
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $insertuserquery = "INSERT INTO players (username, email, password, avatarname) VALUES ('" . $username . "', '" . $email . "', '" . $hashed_password . "', '" . $avatarname . "');";
    mysqli_query($con, $insertuserquery) or die("4: Insert player query failed.");  //error code #4 = insert query error failed

    echo ("0");


?>