var vendors = [];
var dictionary = {};
var attributesLoaded = false;

$(document).ready(function() {
    if (!attributesLoaded) {
        attributesLoaded = true;

        $.ajax({
            url: '/api/vendorAttributes'
        }).done(function(data) {
            console.log(data);
            dictionary = data.dictionary;
            vendors = data.vendors;
        });
    }
});

angular.module('attributeApp', [])
    .controller('AttributeEditorController', function($scope) {
        // global values
        $scope.type = '';
        $scope.vendors = [];
        $scope.dictionary = {};
        $scope.attributes = [];
        $scope.deleted = [];

        // new attribute options
        $scope.vendor = '';
        $scope.attribute = '';
        $scope.op = '';
        $scope.value = '';

        // @TODO: Find a better way to handle this to only load the dictionary once for both check/reply.
        $scope.timer = setInterval(function() {
            if (vendors.length > 0) {
                $scope.dictionary = dictionary;
                $scope.vendors = vendors;
                $scope.$apply();

                clearInterval($scope.timer);
            }
        }, 500);

        $scope.init = function(type, username, groupName) {
            $scope.type = type;
            if (username.length == 0 && groupName.length == 0) {
                console.error('Both username and group name are empty.');
                return;
            }

            $.ajax({
                url: '/api/attributes?type=' + type + '&username=' + username + "&groupName=" + groupName
            }).done(function(data) {
                if (data != 'missing_username_or_group_name') {
                    $scope.attributes = data;
                }
            });
        };

        $scope.addAttribute = function() {
            $scope.attributes.push({
                id: 0,
                attribute: $scope.attribute,
                op: $scope.op,
                value: $scope.value
            });

            $scope.vendor = '';
            $scope.attribute = '';
            $scope.op = '';
            $scope.value = '';
        };

        $scope.deleteAttribute = function(index) {
            if ($scope.attributes[index].id != 0) {
                console.log("Pushing " + $scope.attributes[index].id + " to deleted list");
                $scope.deleted.push($scope.attributes[index].id);
            }

            $scope.attributes.splice(index, 1);
        }
    });