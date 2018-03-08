var app = angular.module('todoApp', []);

app.controller('TodoListController', function($scope, $http) {
    //GET request to populate table of to-do list items
    $http.get("../api/read.php")
        .then(function(response) {
            $scope.tasks = response.data;
    });

    $scope.taskdetails = '';

    $scope.submitTaskForm = function(){

        var postData = {
            task_details: $scope.taskdetails
        };

        $http.post('../api/create.php', postData)
            .then(function() {
               console.log($scope);
        })
    };
});
