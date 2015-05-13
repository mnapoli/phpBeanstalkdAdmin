var beanstalkdApp = angular.module('beanstalkdAdmin', []);

beanstalkdApp.config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
});

beanstalkdApp.controller('mainCtrl', function($scope) {
    $scope.refresh = false;
    $scope.refreshInterval = 5;
    $scope.server = 'localhost:10000';
});
