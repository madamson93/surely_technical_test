var app = angular.module('todoApp', []);

app.controller('TodoListController', function($scope, $http) {
    $scope.taskdetails = '';
    $scope.taskID = undefined;
    $scope.tasks = [];

    //GET request to populate table of to-do list items
    $scope.initialiseData = function() {
        $http.get("../api/read.php")
            .then(function(response) {
                $scope.tasks = response.data;

                return $scope.tasks;
            });
    };

    $scope.initialiseData();

    //POST request to create a new task in the backend
    $scope.submitTaskForm = function(){
        //if the task ID is not undefined it already exists
        if($scope.taskID != undefined) {
            var editData = {
                task_id: $scope.taskID,
                task_details: $scope.taskdetails
            };

            $http.put('../api/update.php', editData)
                .then(function() {
                    $scope.taskdetails = "";
                });
        } else {
            var postData = {
                task_details: $scope.taskdetails
            };

            $http.post('../api/create.php', postData)
                .then(function() {
                    $scope.taskdetails = "";
                });
        }

        $scope.initialiseData();
        $scope.taskID = undefined;
    };

    //DELETE request to delete a task
    $scope.deleteTask = function(taskID){
        var deleteData = {
          task_id: taskID
        };

        $http.post('../api/delete.php', deleteData)
            .then(function () {
                $scope.initialiseData();
        });

        $scope.initialiseData();
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
    };

    //Watch scope attributes for updates, reload the task list on the front end
    $scope.$watch('taskdetails',function(){
        $scope.initialiseData();
    });


});
