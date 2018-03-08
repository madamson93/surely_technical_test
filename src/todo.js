var app = angular.module('todoApp', []);

app.controller('TodoListController', function($scope, $http) {
    $scope.taskdetails = '';
    $scope.tasks = [];

    //GET request to populate table of to-do list items
    $scope.loadData = function () {
        $http.get("../api/read.php")
            .then(function(response) {
                $scope.tasks = response.data;
            });
    };

    //load data on page load
    $scope.loadData();


    //POST request to create a new task in the backend
    $scope.submitTaskForm = function(){
        var postData = {
            task_details: $scope.taskdetails
        };

        $http.post('../api/create.php', postData)
            .then(function() {
                $scope.loadData();
        })
    };
});
