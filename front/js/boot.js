/*启动angular*/
var app=angular.module('ylz', ['ngSanitize','regou.ajaxNormalizer','ajoslin.mobile-navigate','hmTouchEvents','ngStorage']);


app.config(function($httpProvider,$compileProvider) {
    $compileProvider.urlSanitizationWhitelist(/^\s*(https?|ftp|mailto|file|tel|sms|mqq|weixin):/);

});

app.controller('MainCtrl', function($scope, $navigate) {
    $scope.$navigate = $navigate;
})

/*导航动画
* 用法：添加"nav-to"属性 "ani"属性（动画）ani属性值前加符号为反向
* */
app.directive("navTo",['$navigate', function($navigate){
    return {
        restrict: 'A',
        link: function (scope,element,attrs) {
            window.Hammer(element[0]).on("tap",function(){
                var path=attrs['navTo'];
                var animate=attrs.ani;
                if(path=='back'){
                    scope.$apply(function(){$navigate.back()});
                }else{
                    if(animate && animate.substr(0, 1) == "-"){
                        animate=animate.substr(1);
                        scope.$apply(function(){$navigate.go(path,animate,true)});
                    }else{
                        scope.$apply(function(){$navigate.go(path,animate)});
                    }

                }
            });
        }
    }
}]);

app.directive("touchact",function(){
    return {
        restrict: 'A',
        link: function (scope,element,attrs) {
            var classname=attrs['touchact'] || 'navfocus';
            element.bind("touchstart",function(){
                try{this.classList.add(classname);}catch(e){}
            });
            element.bind("touchend",function(){
                try{this.classList.remove(classname);}catch(e){}
            })
        }
    }
});

app.directive("togglereplybtn",function(){
    return {
        restrict: 'A',
        controller: function ($scope) {
            $scope.toggleing = false;
            $scope.toggleAct= function() {
                $scope.toggleing = !$scope.toggleing;
            };
        },
        link: function (scope,element,attrs) {
            element.bind("click",function(){
                scope.$apply(function(){scope.toggleAct()});
            });
        }
    }
});


