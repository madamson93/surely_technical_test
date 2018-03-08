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

    $scope.loadData();

    //POST request to create a new task in the backend
    $scope.submitTaskForm = function(){
        if($scope.taskID != undefined) {
            var editData = {
                task_id: $scope.taskID,
                task_details: $scope.taskdetails
            };

            $http.post('../api/update.php', editData)
                .then(function() {
                    $scope.taskdetails = null;
                });

        } else {
            var postData = {
                task_details: $scope.taskdetails
            };

            $http.post('../api/create.php', postData)
                .then(function() {
                    $scope.taskdetails = null;
                })
        }

        $scope.loadData();
    };

    //DELETE request to delete a task
    $scope.deleteTask = function(taskID){
        var deleteData = {
          task_id: taskID
        };

        $http.post('../api/delete.php', deleteData)
            .then(function() {
                $scope.loadData();
        })
    };

    //GET request to load existing task into form
    $scope.editTask = function(taskID) {
        $scope.taskFound = [];
        $scope.taskID = taskID;

        //retrieve the task using the API
        $http.get("../api/readbyid.php", {
            params: {
                task_id: taskID
            }
        }).then(function(response) {
            $scope.taskdetails = response.data[0].task_details;
        });
    }
});
