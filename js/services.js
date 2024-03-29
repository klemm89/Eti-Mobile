angular.module('etiMobile.services', [])

    .factory('deserializer', function () {
        return {
            topics: function (topics) {
                return $.map(topics, function (topic) {
                    if ($.inArray("Pinned", topic.tags) === -1 && $.inArray("Anonymous", topic.tags) === -1) {
                        return topic;
                    }
                });
            },
            messages: function (messages) {
                return messages;
            }
        }
    })

    .factory('Topics', function ($http, deserializer) {
        return {
            getTopics: function (tag) {
                return $http.get('scripts/topics.php?tag=' + tag).then(function (response) {
                    return deserializer.topics(response.data.topics);
                });
            },
            getMessageList: function (topicId, page) {
                return $http.get('scripts/messages.php?topic=' + topicId + '&page=' + page).then(function (response) {
                    return {
                        title: response.data.topicTitle,
                        messages: deserializer.messages(response.data.messages)
                    }
                });
            },
            createNewTopic: function (topicData) {
                return $http.post('scripts/newtopic.php', topicData).then(function (response) {
                   return response.data.topicId;
                });
            },
            postNewMessage: function (messageData) {
                return $http.post('scripts/newmessage.php', messageData).then(function (response) {
                    return {
                        topicId: response.data.topicId,
                        page: response.data.page || 1,
                        mid: response.data.messageId
                    };
                })
            }
        };
    });
