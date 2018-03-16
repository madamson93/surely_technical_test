var app = angular.module('todoApp', []);

app.controller('TodoListController', function($scope, $http, $window) {
    $scope.taskdetails = '';
    $scope.taskID = undefined;
    $scope.tasks = [];

    //GET request to populate table of to-do list items
    $scope.initialiseData = function() {
        $http.get("/api/tasks")
            .then(function(response) {
                $scope.tasks = response.data;
                return $scope.tasks;
            });
    };

    $scope.initialiseData();

    //POST request to create a new task in the backend
    //PUT request to update an existing task in the backend
    $scope.submitTaskForm = function(){
        //if the task ID is not undefined it already exists
        if($scope.taskID != undefined) {
            var editData = {
                task_details: $scope.taskdetails
            };

            $http.put("/api/tasks/" + $scope.taskID, editData)
                .then(function(response) {
                    if(response.data.error) {
                        $window.alert(response.data.error);
                    }

                    $scope.taskdetails = "";
                });
        } else {
            var postData = {
                task_details: $scope.taskdetails
            };

            $http.post("api/tasks", postData)
                .then(function() {
                    $scope.taskdetails = "";
                });
        }

        $scope.initialiseData();
        $scope.taskID = undefined;
    };

    //DELETE request to delete a task
    $scope.deleteTask = function(taskID){
        $http.delete("/api/tasks/" + taskID)
            .then(function (response) {
                if(response.data.error) {
                    $window.alert(response.data.error);
                }

                $scope.initialiseData();
        });

        $scope.initialiseData();
    };

    //GET request to load existing task into form
    $scope.editTask = function(taskID) {
        $scope.taskFound = [];
        $scope.taskID = taskID;

        //retrieve the task using the API
        $http.get("/api/tasks/" + taskID)
            .then(function(response) {
                if(response.data.error) {
                    $window.alert(response.data.error);
                }

                $scope.taskdetails = response.data.task_details;
        });
    };

    //Watch scope attributes for updates, reload the task list on the front end
    $scope.$watch('taskdetails',function(){
        $scope.initialiseData();
    });


});
