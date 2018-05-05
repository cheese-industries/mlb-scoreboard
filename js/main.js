/* global $, angular */
'use strict';
 
var mlbApp = angular.module('mlbApp', ['scoreboardService']);
 
mlbApp.controller('abbreviationController', ['$scope', function($scope) {
    
    
/*
 * Use custom abbreviations for select teams:
 * 
 * AAA  Algodoneros Union Laguna   VAQ      LAG
 * A+   Carolina Mudcats           CMC      CAR
 * A    Charleston RiverDogs       CRD      CHA
 * AAA  Durham Bulls               DUB      DUR
 * AA   Erie SeaWolves             ESW      ERI
 * A    Great Lakes Loons          SWM      GLK
 * A    Greenville Drive           CAP      GRE
 * AAA  Memphis Redbirds           MRB      MEM
 * AA   Midland RockHounds         MRO      MID
 * AA   Mississippi Braves         MSB      MIS
 * AAA  New Orleans Baby Cakes     NOZ      NO
 * AAA  Sacramento River Cats      SRC      SAC
 * A+   Winston-Salem Dash         WSW      WS
 */
    
    $scope.convertAbbreviation = function(orig) {
        var u = orig.toUpperCase();
        switch (u)
        {
        case "VAQ": return "LAG";
        case "CMC": return "CAR";
        case "CRD": return "CHA";
        case "DUB": return "DUR";
        case "ESW": return "ERI";
        case "SWM": return "GLK";
        case "CAP": return "GRE";
        case "MRB": return "MEM";
        case "MRO": return "MID";
        case "MSB": return "MIS";
        case "NOZ": return "NO";
        case "SRC": return "SAC";
        case "WSW": return "WS";
        }
        // else not a special case...
        return u;
    };
  }]);

mlbApp.controller('scoreboardController', ['$scope', 'getScore', '$interval',
 
    function($scope, getScore, $interval) {
        $scope.parseInt = parseInt;
        /* The function that updates the scoreboards */
        var updateScore = function(response) {
            var gameData = [];
            if (Array.isArray(response.data.games.game)) {
                for (var i = 0; i < response.data.games.game.length; i++) {
                    gameData.push(response.data.games.game[i]);
                }
            } else {
                gameData = [response.data.games.game];
            }
            return gameData;
        };
 
        /* initialize the jQuery calender plugin */
        $("#datepicker").datepicker({
            /* Update the scoreboard whenever a new date is selected */
            onSelect() {
                // var currentDay = new
                // Date($("#datepicker").datepicker("getDate"));
                getScore.get({
                    yearNum: $("#datepicker").datepicker("getDate").getFullYear(),
                    monthNum: ("0" + ($("#datepicker").datepicker("getDate").getMonth() + 1)).slice(-2),
                    dayNum: ("0" + $("#datepicker").datepicker("getDate").getDate()).slice(-2)
                }, function(res) {
                    $scope.scoreboards = updateScore(res);
                }, function() {
                    $('.server-message').attr("style", "display: block");
                });
            }
        });
 
        /* set the default to today */
        $("#datepicker").datepicker("setDate", (new Date()).toLocaleDateString('en-US'));
        var today = new Date();
        getScore.get({
            yearNum: today.getFullYear(),
            monthNum: ("0" + (today.getMonth() + 1)).slice(-2),
            dayNum: ("0" + today.getDate()).slice(-2)
        }, function(res) {
            $scope.scoreboards = updateScore(res);
        }, function() {
            $('.server-message').attr("style", "display: block");
        });
 
 
        /* event handler for choosing previous day */
        $scope.prevDate = function() {
            var prevDay = (new Date($("#datepicker").datepicker("getDate").getTime() - 86400000)).toLocaleDateString('en-US');
            $("#datepicker").datepicker("setDate", prevDay);
            getScore.get({
                yearNum: $("#datepicker").datepicker("getDate").getFullYear(),
                monthNum: ("0" + ($("#datepicker").datepicker("getDate").getMonth() + 1)).slice(-2),
                dayNum: ("0" + $("#datepicker").datepicker("getDate").getDate()).slice(-2)
            }, function(res) {
                $scope.scoreboards = updateScore(res);
            }, function() {
                $('.server-message').attr("style", "display: block");
            });
        };

        /* event handler for choosing next day */
        $scope.nextDate = function() {
            var nextDay = (new Date($("#datepicker").datepicker("getDate").getTime() + 86400000)).toLocaleDateString('en-US');
            $("#datepicker").datepicker("setDate", nextDay);
            getScore.get({
                yearNum: $("#datepicker").datepicker("getDate").getFullYear(),
                monthNum: ("0" + ($("#datepicker").datepicker("getDate").getMonth() + 1)).slice(-2),
                dayNum: ("0" + $("#datepicker").datepicker("getDate").getDate()).slice(-2)
            }, function(res) {
                $scope.scoreboards = updateScore(res);
            }, function() {
                $('.server-message').attr("style", "display: block");
            });
        };
       
        var loadData = function(date) {
            console.debug("Loading " + date);
            getScore.get({
                yearNum: date.getFullYear(),
                monthNum: ("0" + (date.getMonth() + 1)).slice(-2),
                dayNum: ("0" + date.getDate()).slice(-2)
            }, function(res) {
                $scope.scoreboards = updateScore(res);
            }, function() {
                $('.server-message').attr("style", "display: block");
            });
        };

        $interval(function()    {
        var datetoload = $("#datepicker").datepicker("getDate");
        loadData (new Date(datetoload));
        }, 30000);
    }
]);