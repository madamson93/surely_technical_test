var app = angular.module('todoApp', []);

app.controller('FormController', function ($scope, $http) {
    $scope.taskdetails = '';

    $scope.submitTaskForm = function(){

        var postData = {
            task_details: $scope.taskdetails
        };

        $http.post('../api/create.php', postData)
           .then(function() {

        })
    };
});


app.controller('TodoListController', function($scope, $http) {
    //GET request to populate table of to-do list items
    $http.get("../api/read.php")
        .then(function(response) {
            $scope.tasks = response.data;
    });
});
