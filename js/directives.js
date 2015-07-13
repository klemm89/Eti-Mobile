angular.module('etiMobile.directives', [])

    .directive('expandOnClick', function () {
        return {
            restrict: 'EA',
            link: function (scope, element, attrs) {
                element.on('click', function () { element.toggleClass('item-text-wrap'); });
            }
        }
    })

    .directive('newMessage', function () {
        return {
            restrict: 'EA',
            scope: {
                model: '='
            },
            template: '' +
            '<label class="new-message item item-input item-stacked-label"> ' +
            '<span class="input-label">New Message</span> ' +
            '<textarea ng-model="messageModel"></textarea> ' +
            '</label>',
            link: function (scope, element, attrs) {
            }
        }
    });