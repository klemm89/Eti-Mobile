<?php
/**
 * Created by PhpStorm.
 * User: william mcmillian
 * Date: 7/12/15
 * Time: 8:49 pm
 */

include('./header.php');

$newMessageUrl = 'http://boards.endoftheinter.net/postmsg.php?topic=' . $request->topicId;
$newTopicPage = HTTP_Get($newMessageUrl, $_SESSION['eticookie']);
if(is_local()) {
    $newTopicPage = file_get_contents('../test_data/test_new_message.html');
}

$doc = new DOMDocument();
@$doc->loadHTML($newTopicPage);
$signature = $doc->getElementsByTagName('textarea')->item(0)->nodeValue;
$hiddenValue = $doc->getElementsByTagName('input')->item(1)->getAttribute('value');
$messageBody = $request->message;

$fields = array(
    'topic' => urlencode($request->topicId),
    'message' => urlencode($messageBody . "\n" . $signature),
    'h' => urlencode($hiddenValue),
    'submit' => urlencode('Post Message')
);

$newMessageUrl = 'http://boards.endoftheinter.net/postmsg.php';
$submittedForm = HTTP_Post($newMessageUrl, $fields);
if(is_local()) {
    $submittedForm = 'Location://boards.endoftheinter.net/showmessages.php?topic=9186749&page=99#m160066122';
}
preg_match('~Location:.*?topic=(\d+)(&page=(\d+))?#(m\d+)~su', $submittedForm, $locationMatch);
$newTopicId = $locationMatch[1];
$page = $locationMatch[3];
$messageId = $locationMatch[4];


$result = array();
$result['topicId'] = $newTopicId;
$result['page'] = $page;
$result['messageId'] = $messageId;
echo json_encode($result);