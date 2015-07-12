angular.module('etiMobile.controllers', [])

    .controller('TopicListCtrl', function ($scope, $stateParams, Topics) {
        $scope.tagName = $stateParams.tag;

        Topics.getTopics($scope.tagName).then(function (topics) {
            $scope.topics = topics;
            console.log('topics', topics);
        });
    })

    .controller('NewTopicCtrl', function ($scope, $stateParams, Topics) {
        $scope.tagName = $stateParams.tag;

        $scope.submit = function () {
            Topics.createNewTopic({
                tag: $scope.tagName,
                title: $scope.title,
                message: $scope.message
            }).then(function (newTopicId) {
               console.log('this should be the new topic id:', newTopicId);
            });
        };
    })

    .controller('MsgListCtrl', function ($scope, $stateParams, Topics) {
        $scope.topicId = $stateParams.topicId;
        $scope.topicTitle = '';
        $scope.messages = [];
        $scope.page = 1;
        $scope.getMoreMessages = true;

        $scope.getMessages = function () {
            Topics.getMessageList($scope.topicId, $scope.page).then(function (messageList) {
                var messages = messageList.messages;
                $scope.topicTitle = messageList.title;
                if(messages.length === 0) {
                    $scope.getMoreMessages = false;
                }
                else {
                    $scope.page++;
                }

                messages.map(function (message) {
                    $scope.messages.push(message);
                });
                $scope.$broadcast('scroll.infiniteScrollComplete');
            });
        };
    });