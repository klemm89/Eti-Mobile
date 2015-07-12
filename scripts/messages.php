<?php
/**
 * Created by PhpStorm.
 * User: william mcmillian
 * Date: 12/7/14
 * Time: 3:46 PM
 */
include('../scripts/header.php');


$etibody = '';
date_default_timezone_set('MST');


$messages = array();
$tag = 'LUE';

function message ($mid, $img, $user, $body, $time) {
    $message = array();
    $message['id'] = $mid;
    $message['username'] = $user;
    $message['body'] = $body;
    $message['img'] = $img;
    $message['time'] = $time;
    return $message;
}

function showMatches($matchArr) {
    foreach($matchArr as $key => $val) {
        foreach($val as $k => $v) {
            echo $key .'.'. $k . ': ' . $v . '<br>';
        }
        echo '<br>';
    }
}

$message = 'http://boards.endoftheinter.net/showmessages.php?topic=' . $_GET['topic'] . '&page=' . $_GET['page'];
//$cookie = 'evt=0; PHPSESSID=ic1vn4mculnqliqa997l3cuu92; userid=13800; session=672da5f6ae12753202e2ecf14d15a314; __utma=13742947.1128807782.1405582647.1406932156.1406949521.9; __utmb=13742947.1.10.1406949521; __utmc=13742947; __utmz=13742947.1405622965.3.2.utmcsr=the402.net|utmccn=(referral)|utmcmd=referral|utmcct=/whitescreen/';

$cookie = $_SESSION['eticookie'];
$messageList = HTTP_Get($message, $cookie);

$pattern = '|<div class="message-container.*?>.*?'.
    '<table.*?>.*?</table.*?>.*?'.
    '</div.*?>|su';

$titlePattern = '~' .
    '<h1>(.*?)</h1>' .
    '~su';


if(is_local()) {
    $messageList = file_get_contents('../test_data/test_topic.html');
    if($_GET['page'] > 1) {
        $messageList = '';
    }
}
preg_match_all($pattern, $messageList, $rowMatch, PREG_SET_ORDER);
preg_match($titlePattern, $messageList, $titleMatch);
$topicTitle = $titleMatch[1];

$pattern = '~'.
    '<div.*?class="message-top".*?>.*?' .
    'From:.+?user=.+?>(.*?)</a>.*?' .
    'Posted:.*? (.*? AM|PM).*?' .
    '</div.*?>.*?' .
    'class="message">(.*?)' .
    '(---<br />(.{1,500}))?</td>.*?' .
    'class="userpic">(.*?".*?(\w{2,4}\.dealtwith\.it.+?)")?.*?</td>' .
    '~su';

$topicMatch = array();

//print_r($rowMatch);

foreach($rowMatch as $key => $row) {
    preg_match_all($pattern, $row[0], $topicMatcher, PREG_SET_ORDER);
    $topicMatch[] = $topicMatcher[0];
    //print_r($topicMatch);
}

//print_r($topicMatch);

function stripBody ($msg) {
    $msg = str_replace('href=', 'removed-href=', $msg); //strip links
    $msg = str_replace('<br>', '<br />', $msg);
    $msg = preg_replace('|(<br ?/?>\s+)+|su', '<br />', $msg);
    $msg = preg_replace('|(<br ?/?>\s*)$|su', '', $msg);
    return $msg;
}

foreach ($topicMatch as $key => $message) {
    $date = explode('/', explode(' ', $message[2])[0]);
    $time = explode(' ', $message[2])[1];
    $datetime = implode('-', array($date[2], $date[0], $date[1])) . ' ' . $time . ':00';

    $newMessage = array();
    $newMessage['id'] = null;
    $newMessage['username'] = $message[1];
    $newMessage['time'] = $message[2];
    $newMessage['body'] = stripBody($message[3]);
    $newMessage['sig'] = $message[5];
    $newMessage['avatar'] = str_replace('\\', '', $message[7]);

    $messages[] = $newMessage;
    //print_r($message);
}

//print_r($messages);

$result = array();
$result['messages'] = $messages;
$result['topicTitle'] = $topicTitle;
echo json_encode($result);