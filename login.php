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

    $password = $_POST[password];

    //check if name exists
    $namecheckquery = "SELECT username, password, experience, email, avatarname FROM players WHERE username='" . $username . "';";

    $namecheck = mysqli_query($con, $namecheckquery) or die("2: Name check query failed");    //error code #2 = name check query failed

    if (mysqli_num_rows($namecheck) != 1) {
    	echo "5: Either no user with name, or more than one.";  //error code #5 = number of names matching does not equal 1
    	exit();
    }

    //get login info from query
    $existinginfo = mysqli_fetch_assoc($namecheck);
    $hashed_password = $existinginfo["password"];

    if (password_verify($password, $hashed_password)) {
    	echo "0\t" . $existinginfo["experience"] . "\t" . $existinginfo["avatarname"] . "\t" . $existinginfo["email"];
       	exit();
    }

    echo "6: Incorrect password."; //error code #6 = password does not hash to table

?>