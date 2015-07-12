<?php
/**
 * Created by PhpStorm.
 * User: william mcmillian
 * Date: 12/7/14
 * Time: 2:50 PM
 */
//application vars
session_start();
$errors = array();
$username = '';


function HTTP_Get($page, $cookies)
{
    $ch2 = curl_init();

    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch2, CURLOPT_HEADER, 0);
    curl_setopt($ch2, CURLOPT_URL, $page);
    curl_setopt($ch2, CURLOPT_HTTPGET, 1);
    curl_setopt($ch2, CURLOPT_COOKIE, $cookies);

    ob_start();
    $return = curl_exec($ch2);
    ob_end_clean();
    curl_close($ch2);

    if (!$return)
        return FALSE;
    else {
        $return = str_replace('<head>',
            '<head><meta http-equiv="content-type" content="text/html; charset=utf-8"/>',
            $return);
        return $return;
    }
}

function fields_string($fields) {
    $fields_string = '';
    foreach ($fields as $key => $value) {
        $fields_string .= $key . '=' . $value . '&';
    }
    return rtrim($fields_string, '&');
}

function HTTP_Post ($url, $fields) {
    $ch = curl_init();

//set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, fields_string($fields));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_AUTOREFERER, false);

    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

function getCredentials($user, $pass)
{
//set POST variables
    $url = 'https://endoftheinter.net/';
    $fields = array(
        'b' => urlencode($user),
        'p' => urlencode($pass),
        'r' => ''
    );

//url-ify the data for the POST
    $fields_string = '';
    foreach ($fields as $key => $value) {
        $fields_string .= $key . '=' . $value . '&';
    }
    $fields_string = rtrim($fields_string, '&');
//echo $fields_string . '<br>';

//open connection
    $ch = curl_init();

//set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_AUTOREFERER, false);

//execute post
    $result = curl_exec($ch);

//get cookies
    preg_match_all('/^Set-Cookie:\s*([^\r\n]*)/mi', $result, $ms);

    $cookies = implode($ms[1], ';');
    $valid = strpos($cookies, 'userid') !== false;
//echo $cookies;
    if ($valid) {
        $_SESSION['eticookie'] = $cookies;
    }
    if (is_local()) {
        $_SESSION['eticookie'] = 'asdf';
        $valid = true;
    }

//close connection
    curl_close($ch);
    return $valid;
}

//ETI validation
session_start();
if (!isset($_SESSION['eticookie'])) {
    $loggedIn = false;
    if (isset($_POST['username']) && isset($_POST['password'])) {

        $valid = getCredentials($_POST['username'], $_POST['password']);
        if ($valid) {
            $_SESSION['username'] = $username = $_POST['username'];
            $loggedIn = true;
        } else {
            $errors[] = 1; /* error codes: 1 = not logged in on ETI */
        }
    }
} else {
    //inefficient login check. todo: redirect to login page on topics.php/messages.php failure instead of this
    $loggedIn = strpos(HTTP_Get('http://boards.endoftheinter.net/topics/LUE', $_SESSION['eticookie']), 'LUE');
    $username = $_SESSION['username'];
}


/* functions */
function plural($var)
{
    return $var == 1 ? '' : 's';
}


function is_local()
{ //this is just a stupid way for me to get mock data when running the app locally
    $whitelist = array('127.0.0.1', '::1');
    if (in_array($_SERVER['REMOTE_ADDR'], $whitelist))
        return true;
}

if (is_local()) {
    $loggedIn = true;
}

function validateLogin($loggedIn)
{
    if (!$loggedIn) {
        unset($_SESSION['username']);
        unset($_SESSION['eticookie']);
        header("Location:login.php");
    }
}


function timeAgo($time_ago)
{
    $time_ago = strtotime($time_ago);
    $cur_time = time();
    $time_elapsed = $cur_time - $time_ago;
    $seconds = $time_elapsed;
    $minutes = round($time_elapsed / 60);
    $hours = round($time_elapsed / 3600);
    $days = round($time_elapsed / 86400);
    $weeks = round($time_elapsed / 604800);
    $months = round($time_elapsed / 2600640);
    $years = round($time_elapsed / 31207680);
    // Seconds
    if ($seconds <= 60) {
        return ""; //dont show time for this minute
    } //Minutes
    else if ($minutes <= 60) {
        if ($minutes == 1) {
            return "1m";
        } else {
            return "$minutes" . "m";
        }
    } //Hours
    else if ($hours <= 24) {
        if ($hours == 1) {
            return "1h";
        } else {
            return "$hours" . "h";
        }
    } //Days
    else if ($days <= 7) {
        if ($days == 1) {
            return "1d";
        } else {
            return "$days" . "d";
        }
    } //Weeks
    else if ($weeks <= 4.3) {
        if ($weeks == 1) {
            return "1w";
        } else {
            return "$weeks" . "w";
        }
    } //Months
    else if ($months <= 12) {
        if ($months == 1) {
            return "1m";
        } else {
            return "$months" . "m";
        }
    } //Years
    else {
        if ($years == 1) {
            return "1y";
        } else {
            return "$years" . "y";
        }
    }
}

function flatten($array)
{

    $return = array();
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $return = array_merge($return, flatten($value));
        } else {
            $return[$key] = $value;
        }
    }
    return $return;

}


$postdata = file_get_contents("php://input");
$request = json_decode($postdata);