<?php
/**
 * Created by PhpStorm.
 * User: william mcmillian
 * Date: 12/7/14
 * Time: 3:46 PM
 */
include('../scripts/header.php');

date_default_timezone_set('MST');


$topics = array();

function message ($tid, $user, $title, $tags, $time, $count, $new) {
    $topic = array();
    $topic['id'] = $tid;
    $topic['username'] = $user;
    $topic['title'] = $title;
    $topic['tags'] = $tags;
    $topic['time'] = $time;
    $topic['count'] = $count;
    $topic['new'] = $new;
    return $topic;
}

function showMatches($matchArr) {
    foreach($matchArr as $key => $val) {
        foreach($val as $k => $v) {
            echo $key .'.'. $k . ': ' . $v . '<br>';
        }
        echo '<br>';
    }
}
$tag = $_GET['tag'];
$lue = 'http://boards.endoftheinter.net/topics/' . $tag;
//$cookie = 'evt=0; PHPSESSID=ic1vn4mculnqliqa997l3cuu92; userid=13800; session=672da5f6ae12753202e2ecf14d15a314; __utma=13742947.1128807782.1405582647.1406932156.1406949521.9; __utmb=13742947.1.10.1406949521; __utmc=13742947; __utmz=13742947.1405622965.3.2.utmcsr=the402.net|utmccn=(referral)|utmcmd=referral|utmcct=/whitescreen/';

$cookie = $_SESSION['eticookie'];
$topicList = HTTP_Get($lue, $cookie);

$pattern = '|<tr>.*?'.
    '</tr>|su';

preg_match_all($pattern, $topicList, $rowMatch, PREG_SET_ORDER);

$pattern = '~'.
    '<td.*?>.*?' .
    '<div.*?>.*?' .
    '<a.*?topic=(.*?)">(.*?)</a>.*?' .
    '</div>.*?' .
    '<div.*?>(.*?)' .
    '</div>.*?' .
    '.*?</td>.*?' .
    '<td.*?>(.*?<a.*?>)?(.*?)(</a>.*?)?</td>.*?' .
    '<td.*?>.*?(\d+).*?</td>.*?' .
    '<td.*?>(.*?)</td>.*?' .
    '~su';

$topicMatch = array();

foreach($rowMatch as $key => $row) {
    if($key == 0)
        continue;
    preg_match_all($pattern, $row[0], $topicMatcher, PREG_SET_ORDER) . ', ';
    //echo '<br>' . $key . ', ';
    //showMatches($topicMatcher);
    $topicMatch[] = $topicMatcher[0];
}
//echo count($topicMatch) . ', ';
//showMatches($topicMatch);


/* test data */
if(is_local()) {
    $topics[] = message(123, 'S Otaku', 'Test one', array('LUE', 'Strong Homo'), '4m', '154 (+2)', true);
    $topics[] = message(23, 'John Kerry', 'Test one two three four five six seven eight nine ten eleven twelve thirteen fourteen fifteen sixteen', array('LUE', 'Strong Homo'), '4m', '170', false);
    $topics[] = message(545454, 'George Bush Jr.', 'Hey Come Fuck my Butthole', array('LUE', 'Strong Homo'), '4m', '154 (+2)', true);
    $topics[] = message(45445545, 'Obummer', 'hahaha im gonna take away all ur guns', array('LUE', 'Strong Homo'), '4m', '1', false);
    $topics[] = message(45434323, 'WWWWWWWWWWWWWWWWWWWW', 'An Extra Super Really Long Topic Title Test Test Test', array('LUE', 'Strong Homo', 'Hyperdimensional Galactic Neptunia Or Whatever', 'I Know Im Not Popular or Whatever'), 'Dec 31', '5000 (+4999)', true);
}

foreach ($topicMatch as $key => $topic) {

    preg_match_all('|<a.+?>(.+?)</a>|', $topic[3], $tagMatch, PREG_SET_ORDER);
    $tags = array();
    foreach($tagMatch as $tagName) {
        $tags[] = $tagName[1];
    }
    $date = explode('/', explode(' ', $topic[8])[0]);
    $time = explode(' ', $topic[8])[1];
    $datetime = implode('-', array($date[2], $date[0], $date[1])) . $time . ':00';

    $topics[] = message(
        $topic[1], $topic[5], $topic[2], $tags, timeAgo($datetime), $topic[7], false
    );
}


function lastPage($topicId, $numPosts) {
    return 'http://boards.endoftheinter.net/showmessages.php?topic='. $topicId . '&page='. ceil($numPosts / 50);
}

$result = array();
$result['topics'] = $topics;
echo json_encode($result);