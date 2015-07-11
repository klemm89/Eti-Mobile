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


if(is_local()) {
    $messageList = '<div class="message-container" id="m138424192"><div class="message-top"><b>From:</b><a href="//endoftheinter.net/profile.php?user=23115">Kraken</a> | <b>Posted:</b> 1/26/2014 10:18:08 AM |<a href="//boards.endoftheinter.net/showmessages.php?topic=8759327&amp;u=23115">Filter</a> |<a href="//boards.endoftheinter.net/showmessages.php?topic=8759327&amp;thread=138424192">Replies (1)</a> |<a href="/message.php?id=138424192&amp;topic=8759327&amp;r=0">Message Detail</a> |<a href="/postmsg.php?topic=8759327&amp;quote=138424192" onclick="return QuickPost.publish(\'quote\', this);">Quote</a></div><table class="message-body"><tr><td msgid="t,8759327,138424192@0" class="message"><div class="imgs"><a target="_blank" imgsrc="http://i4.endoftheinter.net/i/n/b2ec12b575c9559e6450849ca228687b/Capricorn.jpg" href="//images.endoftheinter.net/imap/b2ec12b575c9559e6450849ca228687b/Capricorn.jpg"><span class="img-placeholder" style="width:700px;height:357px" id="u0_7"></span><script type="text/javascript">onDOMContentLoaded(function(){new ImageLoader($("u0_7"), "\/\/i4.dealtwith.it\/i\/n\/b2ec12b575c9559e6450849ca228687b\/Capricorn.jpg", 700, 357)})</script></a><div style="clear:both"></div></div>---<br />Former Kroin<br /><i>Dance. You gotta dance. As long as the music plays.</i></td><td class="userpic"><div class="userpic-holder"><a href="//images.endoftheinter.net/imap/00483d91ca17fd93efa6097a05969dae/tentaclekitty.jpg"><span class="img-placeholder" style="width:106px;height:150px" id="u0_8"></span><script type="text/javascript">onDOMContentLoaded(function(){new ImageLoader($("u0_8"), "\/\/i1.dealtwith.it\/i\/t\/00483d91ca17fd93efa6097a05969dae\/tentaclekitty.jpg", 106, 150)})</script></a></div></td></tr></table></div>';
}
preg_match_all($pattern, $messageList, $rowMatch, PREG_SET_ORDER);

/*
<div class="message-container" id="m138424192"><div class="message-top"><b>From:</b>
<a href="//endoftheinter.net/profile.php?user=23115">Kraken</a> | <b>Posted:</b> 1/26/2014 10:18:08 AM |
<a href="//boards.endoftheinter.net/showmessages.php?topic=8759327&amp;u=23115">Filter</a> |
<a href="//boards.endoftheinter.net/showmessages.php?topic=8759327&amp;thread=138424192">Replies (1)</a> |
<a href="/message.php?id=138424192&amp;topic=8759327&amp;r=0">Message Detail</a> |
<a href="/postmsg.php?topic=8759327&amp;quote=138424192" onclick="return QuickPost.publish('quote', this);">Quote</a>
</div>
<table class="message-body"><tr>
<td msgid="t,8759327,138424192@0" class="message">
<div class="imgs">
<a target="_blank" imgsrc="http://i4.endoftheinter.net/i/n/b2ec12b575c9559e6450849ca228687b/Capricorn.jpg" href="//images.endoftheinter.net/imap/b2ec12b575c9559e6450849ca228687b/Capricorn.jpg"><span class="img-placeholder" style="width:700px;height:357px" id="u0_7"></span><script type="text/javascript">onDOMContentLoaded(function(){new ImageLoader($("u0_7"), "\/\/i4.dealtwith.it\/i\/n\/b2ec12b575c9559e6450849ca228687b\/Capricorn.jpg", 700, 357)})</script></a>
<div style="clear:both"></div></div>---<br />
Former Kroin<br />
<i>Dance. You gotta dance. As long as the music plays.</i></td>
<td class="userpic"><div class="userpic-holder"><a href="//images.endoftheinter.net/imap/00483d91ca17fd93efa6097a05969dae/tentaclekitty.jpg"><span class="img-placeholder" style="width:106px;height:150px" id="u0_8"></span><script type="text/javascript">onDOMContentLoaded(function(){new ImageLoader($("u0_8"), "\/\/i1.dealtwith.it\/i\/t\/00483d91ca17fd93efa6097a05969dae\/tentaclekitty.jpg", 106, 150)})</script></a></div></td></tr></table></div>

*/
$pattern = '~'.
    '<div.*?class="message-top".*?>.*?' .
    'From:.+?user=.+?>(.*?)</a>.*?' .
    'Posted:.*? (.*? AM|PM).*?' .
    '</div.*?>.*?' .
    'class="message">(.*?)' .
    '<div style="clear:both"></div>.*?' .
    'class="userpic">.*?".*?(\w{2,4}\.dealtwith\.it.+?)".*?</td>' .
    '~su';

$topicMatch = array();

print_r($rowMatch);

foreach($rowMatch as $key => $row) {
    preg_match_all($pattern, $row[0], $topicMatcher, PREG_SET_ORDER);
    $topicMatch[] = $topicMatcher[0];
    //print_r($topicMatch);
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

foreach ($topicMatch as $key => $message) {
    $date = explode('/', explode(' ', $message[2])[0]);
    $time = explode(' ', $message[2])[1];
    $datetime = implode('-', array($date[2], $date[0], $date[1])) . ' ' . $time . ':00';

    $newMessage = array();
    $newMessage['id'] = null;
    $newMessage['username'] = $message[1];
    $newMessage['time'] = $message[2];
    $newMessage['body'] = $message[3];
    $newMessage['img'] = 'http://' . str_replace('\\', '', $message[4]);

    $messages[] = $newMessage;
}

//print_r($messages);

$result = array();
$result['messages'] = $messages;
echo json_encode($result);