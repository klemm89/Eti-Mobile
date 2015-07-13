angular.module('etiMobile.controllers', [])

    .controller('TopicListCtrl', function ($scope, $stateParams, Topics) {
        $scope.tagName = $stateParams.tag;

        Topics.getTopics($scope.tagName).then(function (topics) {
            $scope.topics = topics;
        });
    })

    .controller('NewTopicCtrl', function ($scope, $stateParams, $location, Topics) {
        $scope.tagName = $stateParams.tag;
        $scope.newTopic = {
            title: '',
            message: ''
        };

        $scope.submit = function () {
            Topics.createNewTopic({
                tag: $scope.tagName,
                title: $scope.newTopic.title,
                message: $scope.newTopic.message
            }).then(function (newTopicId) {
                $location.path('messagelist/' + newTopicId);
            });
        };
    })

    .controller('NewMsgCtrl', function ($scope, $stateParams, $location, Topics) {
        $scope.message = {
            body: ''
        };

        $scope.postMessage = function () {
            Topics.postNewMessage({
                topicId: $stateParams.topicId,
                message: $scope.message.body
            }).then(function (newPost) {
                $location.path('messagelist/' + newPost.topicId + '/' + newPost.page);
            });
        };
    })

    .controller('MsgListCtrl', function ($scope, $stateParams, Topics) {
        $scope.topicId = $stateParams.topicId;
        $scope.page = $stateParams.page || 1;
        $scope.topic = {
            title: '',
            messages: []
        };
        $scope.getMoreMessages = true;

        $scope.getMessages = function () {
            Topics.getMessageList($scope.topicId, $scope.page).then(function (messageList) {
                var messages = messageList.messages;
                if(messageList.title) {
                    $scope.topic.title = messageList.title;
                }
                
                if(messages.length === 0) {
                    $scope.getMoreMessages = false;
                }
                else {
                    $scope.page++;
                }

                messages.map(function (message) {
                    $scope.topic.messages.push(message);
                });
                $scope.$broadcast('scroll.infiniteScrollComplete');
            });
        };
    });