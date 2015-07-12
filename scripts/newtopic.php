<?php
/**
 * Created by PhpStorm.
 * User: william mcmillian
 * Date: 7/12/15
 * Time: 8:49 AM
 */

include('./header.php');

$newTopicUrl = 'http://boards.endoftheinter.net/postmsg.php?tag=' . $request->tag;
$newTopicPage = HTTP_Get($newTopicUrl, $_SESSION['eticookie']);
if(is_local()) {
    $newTopicPage = file_get_contents('../test_data/test_new_topic.html');
}

$doc = new DOMDocument();
@$doc->loadHTML($newTopicPage);
$signature = $doc->getElementsByTagName('textarea')->item(1)->nodeValue;
$hiddenValue = $doc->getElementsByTagName('input')->item(2)->getAttribute('value');
$messageBody = $request->message;

$fields = array(
    'title' => urlencode($request->title),
    'message' => urlencode($messageBody . "\n" . $signature),
    'h' => urlencode($hiddenValue),
    'tag' => 'LUE' //implode('***', explode(',', $_GET['tag']))
);


$newTopic = HTTP_Post($newTopicUrl, $fields);

echo $newTopic;