<?php
/**
 * Created by PhpStorm.
 * User: kritarth
 * Date: 3/6/16
 * Time: 6:46 PM
 */
require 'dbcon.php';

$dbcon = new dbcon();
$link = $dbcon -> connect();
// Check login button pushed
if ( isset($_POST['login']) ) {
    // Get posted data
    $username = $_POST['id'];
    $password = $_POST['pass'];

    // Start Checking
    $error = '';

    if(!isset($username) || !isset($password)) {
        $error .= 'A required field was left blank.<br />';
    } else {
        //$password = md5($password);

        // Query db
        $query = "SELECT * FROM userlogin WHERE id='$username' AND password='$password' LIMIT 1";
        $result = mysqli_query($link, $query) or die("Could not get data");
        $result = mysqli_fetch_row($result);
        // Check Valid login
        $valid_login = sizeof($result);

        if(($valid_login == 0 && !is_object($result))) {
            $error .= 'The supplied id and/or password was incorrect.<br />';
        }

    }

    // If Valid login, set session and cookies
    if ($error == '') {

        //start new session
        session_start();

        $id = $_POST['uid'];

        // Set Session
        $_SESSION['uid'] = $id;
        $_SESSION['id'] = $username;
        $_SESSION['logged'] = true;

        //header("Location: studentindex.php");
        echo 'success';
    } else {
        echo 'failure';
    }
}

// Close the db connection
mysqli_close($link);

?>

