var vendors = [];
var dictionary = {};
var attributesLoaded = false;

$(document).ready(function() {
    if (localStorage['dictionary'] != undefined) {
        dictionary = JSON.parse(localStorage.getItem('dictionary'));
        vendors = JSON.parse(localStorage.getItem('vendors'));
        attributesLoaded = true;
    }

    if (!attributesLoaded) {
        attributesLoaded = true;

        $.ajax({
            url: '/api/vendorAttributes'
        }).done(function(data) {
            dictionary = data.dictionary;
            vendors = data.vendors;
            localStorage.setItem('dictionary', JSON.stringify(data.dictionary));
            localStorage.setItem('vendors', JSON.stringify(data.vendors));
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
        $scope.valuesCount = 0;

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

        $scope.handleAttributeSelection = function() {
            $scope.valuesCount = $scope.attribute.values.length;
            console.log($scope.valuesCount);
        };

        $scope.addAttribute = function() {
            var value = $scope.value;
            if ($scope.valuesCount > 0) {
                value = value.value;
            }

            $scope.attributes.push({
                id: 0,
                attribute: $scope.attribute.attribute,
                op: $scope.op,
                value: value
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