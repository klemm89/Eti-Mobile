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

$message = 'http://boards.endoftheinter.net/showmessages.php?topic=' . $_GET['topic'];
//$cookie = 'evt=0; PHPSESSID=ic1vn4mculnqliqa997l3cuu92; userid=13800; session=672da5f6ae12753202e2ecf14d15a314; __utma=13742947.1128807782.1405582647.1406932156.1406949521.9; __utmb=13742947.1.10.1406949521; __utmc=13742947; __utmz=13742947.1405622965.3.2.utmcsr=the402.net|utmccn=(referral)|utmcmd=referral|utmcct=/whitescreen/';

$cookie = $_SESSION['eticookie'];
$messageList = HTTP_Get($message, $cookie);

$pattern = '|<div class="message-container.*?>.*?'.
    '<table.*?>.*?</table.*?>.*?'.
    '</div.*?>|su';

preg_match_all($pattern, $messageList, $rowMatch, PREG_SET_ORDER);

$pattern = '~'.
    '<td.*?>(.*?)' .
    '</td.*?>' .
    '~su';

$topicMatch = array();

foreach($rowMatch as $key => $row) {
    if($key == 0)
        continue;
    preg_match_all($pattern, $row[0], $topicMatcher, PREG_SET_ORDER);
    print_r($row);
    $topicMatch[] = $topicMatcher[0];
}

/* test data */
if(is_local()) {
    //$messages[] = message(123, 'S Otaku', 'Test one', array('LUE', 'Strong Homo'), '4m', '154 (+2)', true);
    $messages[] = message(4567,
        'http://i3.dealtwith.it/i/n/a97534935e3c601b3bdd7d300fb0e307/s%20otaku.jpg',
        'S Otaku',
        'Update: script now supports tagging. If you "Follow & Tag" you will be prompted for a tag. Later you can "Unfollow (by tag)" and you will again be prompted for a tag. Only the users matching the tag you enter will be unfollowed Update: script now supports tagging. If you "Follow & Tag" you will be prompted for a tag. Later you can "Unfollow (by tag)" and you will again be prompted for a tag. Only the users matching the tag you enter will be unfollowed ',
        '5m');
}

//print_r($topicMatch);
/*
foreach ($topicMatch as $key => $message) {
    $date = explode('/', explode(' ', $message[8])[0]);
    $time = explode(' ', $message[8])[1];
    $datetime = implode('-', array($date[2], $date[0], $date[1])) . $time . ':00';

    $messages[] = message(
        $message[1], $message[5], $message[2], $tags, timeAgo($datetime), $message[7], false
    );
}
*/


$result = array();
$result['messages'] = $messages;
echo json_encode($result);