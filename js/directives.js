angular.module('etiMobile.directives', [])

    .directive('expandOnClick', function () {
        return {
            restrict: 'EA',
            link: function (scope, element, attrs) {
                element.on('click', function () { element.toggleClass('item-text-wrap'); });
            }
        }
    });