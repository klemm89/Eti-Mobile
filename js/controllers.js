angular.module('etiMobile.controllers', [])

    .controller('TopicListCtrl', function ($scope, $stateParams, Topics) {
        $scope.tagName = $stateParams.tag;

        Topics.getTopics($scope.tagName).then(function (topics) {
            $scope.topics = topics;
            console.log('topics', topics);
        });
    })

    .controller('NewTopicCtrl', function ($scope, $stateParams, $location, Topics) {
        $scope.tagName = $stateParams.tag;
        $scope.newTopic = {
            title: '',
            message: ''
        };

        $scope.submit = function () {
            console.log($scope);
            Topics.createNewTopic({
                tag: $scope.tagName,
                title: $scope.newTopic.title,
                message: $scope.newTopic.message
            }).then(function (newTopicId) {
                $location.path('messagelist/' + newTopicId);
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