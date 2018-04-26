<!doctype html>
<html lang="en" ng-app="mlbApp">
<head>
<meta charset="utf-8">
<?php
$level = $_GET['level'];
$service = $_GET['service'];

function boxScore($level)
{
    echo (($level == "MLB") ? "<a href=\"{{game.links.preview}}\">MLB.com Gameday</a>" : "<a href=\"{{game.links.box_link}}\">Box Score</a>");
}

function setBaseHref($level)
{
    echo (($level == "MLB") ? "<base href=\"http://mlb.mlb.com/\" target=\"_blank\">" : "<base href=\"http://www.milb.com/\" target=\"_blank\">");
}

?>
<title><?php echo($level); ?> Scoreboard</title>
<link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet"
    href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" src="scoreboard.css">
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script
    src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script
    src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.4/angular.min.js"></script>
<script src="js/angular-resource.min.js"></script>
<script>
    var serviceName = "<?php echo($service); ?>";
</script>
<script src="js/service.js"></script>
<script src="js/main.js"></script>
<?php setBaseHref($level) ?>
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
</head>
<body ng-controller="scoreboardController"
    style="text-align: left; font-family: 'Roboto', sans-serif;">
    <h3>
        <center><?php echo($level); ?> Scoreboard</center>
    </h3>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <span class="glyphicon glyphicon-chevron-left"
                    aria-hidden="true" ng-click="prevDate()"></span> <span>Date:
                    <input type="text" id="datepicker">
                </span> <span class="glyphicon glyphicon-chevron-right"
                    aria-hidden="true" ng-click="nextDate()"></span>
            </div>
        </div>
    </div>
    <div class="container.fluid" ng-repeat="game in scoreboards">
        <div class="row" style="background-color: #e6f2ff">
            <!--If the away team is winning, their name should be bold-->
            <div class="away"
                style="margin-left: 50px; text-align: left; font-weight: bold; width: 150px; align: left; float: left; font-size: 12px;"
                ng-if="parseInt(game.linescore.r.home) < parseInt(game.linescore.r.away)">{{game.away_team_city}}
                ({{game.away_win}}-{{game.away_loss}})</div>
            <div class="away"
                style="margin-left: 50px; text-align: left; width: 150px; float: left; align: left; font-size: 12px;"
                ng-if="parseInt(game.linescore.r.home) >= parseInt(game.linescore.r.away) || !game.linescore">{{game.away_team_city}}
                ({{game.away_win}}-{{game.away_loss}})</div>
            <!--If the game has not yet started, show the away team's probable starting pitcher and his stats-->
            <div class="away-score"
                style="text-align: left; width: 200px; float: left; align: left; font-size: 12px;"
                ng-if="game.status.status == 'Preview' || game.status.status == 'Pre\-Game' || game.status.status == 'Warmup'">{{game.away_probable_pitcher.first_name}}
                {{game.away_probable_pitcher.last}}
                ({{game.away_probable_pitcher.wins}}-{{game.away_probable_pitcher.losses}},
                {{game.away_probable_pitcher.era}})</div>
            <!--If the game has not yet started, show the start time. This div just contains the actual words 'start time'-->
            <div class="start"
                style="width: 75px; float: left; font-size: 12px; text-align: left;"
                ng-if="game.away_time != '3:33' && (game.status.status == 'Pre\-Game' || game.status.status == 'Preview' || game.status.status == 'Warmup')">Start
                time ET:</div>
            <!--If the game is in progress or it's over, show the number of runs that the away team has scored.-->
            <div class="away-score"
                style="width: 40px; float: left; align: left; font-size: 12px; text-align: left;"
                ng-if="game.status.status == 'In Progress' || game.status.status == 'Final' || game.status.status == 'Game Over' || game.status.status == 'Completed Early'">{{game.linescore.r.away}}</div>
            <div class="away-score"
                style="width: 40px; float: left; align: left; font-size: 12px; text-align: left;"
                ng-if="game.status.status == 'Postponed'"></div>
            <!--If the game isn't in preview or pregame, and it isn't over, it must be in progress. Show the inning.-->
            <div class="inning"
                style="width: 75px; float: left; text-align: left; font-size: 12px;"
                ng-if="game.status.status != 'Final' && game.status.status != 'Game Over' && game.status.status != 'Preview' && game.status.status != 'Pre\-Game' && game.status.status != 'Warmup' && game.status.status != 'Delayed Start' && game.status.status != 'Postponed' && game.status.status != 'Completed Early'">{{game.status.inning_state}}
                {{game.status.inning}}</div>
            <div class="boxscore"
                style="text-align: left; width: 175px; float: left; font-size: 12px;"
                ng-if="game.status.status != 'Final' && game.status.status != 'Game Over' && game.status.status != 'Preview' && game.status.status != 'Pre\-Game' && game.status.status != 'Warmup' && game.status.status != 'Delayed Start' && game.status.status != 'Postponed' && game.status.status != 'Completed Early' && game.status.status != 'Cancelled'">Balls:
                {{game.status.b}} Strikes: {{game.status.s}}</div>
            <!--Is there a no-hitter or a perfect game in progress?-->
            <div class="notes"
                ng-if="game.status.is_no_hitter == 'Y' && game.status.is_perfect_game != 'Y' && game.status.status != 'Final'">
                <b>NO-HITTER</B>
            </div>
            <div class="notes"
                ng-if="game.status.is_perfect_game == 'Y' && game.status.status != 'Final'">
                <b>PERFECT GAME</B>
            </div>
            <!--Is this a no-hitter or a perfect game that has been completed in exactly nine innings?-->
            <div class="notes"
                ng-if="game.status.is_no_hitter == 'Y' && game.status.status == 'Final' && game.status.inning == 9">
                <b>FINAL - NO-HITTER</B>
            </div>
            <div class="notes"
                ng-if="game.status.is_perfect_game == 'Y' && game.status.status == 'Final' && game.status.inning == 9">
                <b>FINAL - PERFECT GAME</B>
            </div>
            <!--If the game is postponed, display the word 'postponed'.-->
            <div class="final"
                style="width: 175px; float: left; text-align: left; font-size: 12px;"
                ng-if="game.status.status == 'Postponed' || game.status.status == 'Cancelled'">
                <b>Postponed</b>
            </div>
            <!--If the game has ended, wasn't a no-hitter or perfect game and it went the scheduled nine innings, show the word "Final" without the number of innings played.-->
            <div class="final"
                style="width: 175px; float: left; text-align: left; font-size: 12px;"
                ng-if="game.status.status == 'Final' && game.status.inning == 9 && game.status.is_no_hitter != 'Y' && game.status.is_perfect_game != 'Y'">
                <b>Final</b>
            </div>
            <!--If the game has ended, wasn't a no-hitter or perfect game and it didn't go exactly nine innings, show the word "Final" and the number of innings played.-->
            <div class="final"
                style="width: 175px; float: left; text-align: left; font-size: 12px;"
                ng-if="(game.status.status == 'Final' || game.status.status == 'Completed Early') && game.status.inning != 9 && game.status.is_no_hitter != 'Y' && game.status.is_perfect_game != 'Y'">
                <b>Final/{{game.status.inning}}</b>
            </div>
            <!--If the game has ended, was a no-hitter or perfect game and it didn't go exactly nine innings, show the word "Final" and the number of innings played.-->
            <div class="final"
                style="width: 175px; float: left; text-align: left; font-size: 12px;"
                ng-if="game.status.status == 'Final' && game.status.inning != 9 && game.status.is_no_hitter == 'Y' && game.status.is_perfect_game != 'Y'">
                <b>Final/{{game.status.inning}} - NO-HITTER</b>
            </div>
            <div class="final"
                style="width: 175px; float: left; text-align: left; font-size: 12px;"
                ng-if="game.status.status == 'Final' && game.status.inning != 9 && game.status.is_perfect_game == 'Y'">
                <b>Final/{{game.status.inning}} - PERFECT GAME</b>
            </div>
            <!--Sometimes MLB lists final scores as 'game over' instead of 'final'. These lines do the same as the two lines above, but adjust for this quirk.-->
            <div class="final"
                style="width: 175px; float: left; text-align: left; font-size: 12px;"
                ng-if="game.status.status == 'Game Over' && game.status.inning == 9 && game.status.is_no_hitter != 'Y' && game.status.is_perfect_game != 'Y'">
                <b>Final</b>
            </div>
            <div class="final"
                style="width: 175px; float: left; text-align: left; font-size: 12px;"
                ng-if="game.status.status == 'Game Over' && game.status.inning != 9 && game.status.is_no_hitter != 'Y' && game.status.is_perfect_game != 'Y'">
                <b>Final/{{game.status.inning}}</b>
            </div>
            <div class="notes"
                ng-if="game.status.is_no_hitter == 'Y' && game.status.status == 'Game Over' && game.status.inning == 9">
                <b>FINAL - NO-HITTER</B>
            </div>
            <div class="notes"
                ng-if="game.status.is_perfect_game == 'Y' && game.status.status == 'Game Over' && game.status.inning == 9">
                <b>FINAL - PERFECT GAME</B>
            </div>
            <div class="final"
                style="width: 175px; float: left; text-align: left; font-size: 12px;"
                ng-if="game.status.status == 'Game Over' && game.status.inning != 9 && game.status.is_no_hitter == 'Y' && game.status.is_perfect_game != 'Y'">
                <b>Final/{{game.status.inning}} - NO-HITTER</b>
            </div>
            <div class="final"
                style="width: 175px; float: left; text-align: left; font-size: 12px;"
                ng-if="game.status.status == 'Game Over' && game.status.inning != 9 && game.status.is_perfect_game == 'Y'">
                <b>Final/{{game.status.inning}} - PERFECT GAME</b>
            </div>
            <div class="start"
                style="width: 75px; float: left; font-size: 12px; text-align: left;"
                ng-if="game.away_time == '3:33'">Game 2</div>
        </div>
        <div class="row" style="background-color: #e6f2ff">
            <div class="home"
                style="margin-left: 50px; font-weight: bold; font-size: 12px; width: 150px; float: left"
                ng-if="parseInt(game.linescore.r.home) > parseInt(game.linescore.r.away)">{{game.home_team_city}}
                ({{game.home_win}}-{{game.home_loss}})</div>
            <div class="home"
                style="margin-left: 50px; width: 150px; font-size: 12px; float: left"
                ng-if="parseInt(game.linescore.r.home) <= parseInt(game.linescore.r.away) || !game.linescore">{{game.home_team_city}}
                ({{game.home_win}}-{{game.home_loss}})</div>
            <div class="home-score"
                style="width: 200px; float: left; font-size: 12px;"
                ng-if="game.status.status == 'Preview' || game.status.status == 'Pre\-Game' || game.status.status == 'Warmup'">{{game.home_probable_pitcher.first_name}}
                {{game.home_probable_pitcher.last}}
                ({{game.home_probable_pitcher.wins}}-{{game.home_probable_pitcher.losses}},
                {{game.home_probable_pitcher.era}})</div>
            <div class="start"
                style="width: 75px; float: left; font-size: 12px;"
                ng-if="game.away_time != '3:33' && (game.status.status == 'Pre\-Game' || game.status.status == 'Preview' || game.status.status == 'Warmup')">{{game.time}}
                {{game.ampm}}</div>
            <div class="home-score"
                style="width: 40px; float: left; font-size: 12px;"
                ng-if="game.status.status == 'In Progress' || game.status.status == 'Final' || game.status.status == 'Game Over' || game.status.status == 'Completed Early'">{{game.linescore.r.home}}</div>
            <div class="home-score"
                style="width: 40px; float: left; font-size: 12px;"
                ng-if="game.status.status == 'Postponed'"></div>
            <div class="inning"
                style="width: 75px; float: left; font-size: 12px;"
                ng-if="game.status.status == 'In Progress'">{{game.status.o}}
                Out</div>
            <div class="inning"
                style="width: 175px; float: left; font-size: 12px;"
                ng-if="game.status.status != 'In Progress' && game.status.status != 'Final' && game.status.status != 'Game Over' && game.status.status != 'Preview' && game.status.status != 'Pre\-Game' && game.status.status != 'Warmup' && game.status.reason">{{game.status.status}}
                - {{game.status.reason}}</div>
            <div class="boxscore"
                style="text-align: left; width: 175px; float: left; font-size: 12px;"
                ng-if="game.status.status != 'Pre\-Game' && game.status.status != 'Preview' && game.status.status != 'Warmup' && game.status.status != 'Postponed' && game.status.status != 'Cancelled'">
                <?php boxScore($level); ?>
            </div>
        </div>
        <div class="row" style="background-color: #ffe6e6">
            <div class="now-batting"
                style="margin-left: 50px; width: 325px; text-align: left; float: left; font-size: 12px;"
                ng-if="game.status.status == 'In Progress'">
                <b>Batter:</b> {{game.batter.first}} {{game.batter.last}}
                ({{game.batter.avg}}/{{game.batter.obp}}/{{game.batter.slg}})
            </div>
        </div>
        <div class="row" style="background-color: #ffe6e6">
            <div class="now-pitching"
                style="margin-left: 50px; text-align: left; width: 325px; float: left; font-size: 12px;"
                ng-if="game.status.status == 'In Progress'">
                <b>Pitcher:</b> {{game.pitcher.first}} {{game.pitcher.last}}
                ({{game.pitcher.wins}}-{{game.pitcher.losses}},
                {{game.pitcher.era}})
            </div>
        </div>
        <div class="row" style="background-color: #ffe6ca">
            <div class="ondeck"
                style="margin-left: 50px; text-align: left; width: 325px; float: left; font-size: 12px;"
                ng-if="game.status.status == 'In Progress'">
                <b>On Deck:</b> {{game.ondeck.first}} {{game.ondeck.last}}
                ({{game.ondeck.avg}}/{{game.ondeck.obp}}/{{game.ondeck.slg}})
            </div>
        </div>
        <div class="row" style="background-color: #ffe6ca">
            <div class="inhole"
                style="margin-left: 50px; text-align: left; width: 325px; float: left; font-size: 12px;"
                ng-if="game.status.status == 'In Progress'">
                <b>In the Hole:</b> {{game.inhole.first}}
                {{game.inhole.last}}
                ({{game.inhole.avg}}/{{game.inhole.obp}}/{{game.inhole.slg}})
            </div>
        </div>
        <div class="row" style="background-color: #ffffcc">
            <div class="baserunners_1b"
                style="margin-left: 50px; text-align: left; width: 150px; float: left; font-size: 12px;"
                ng-if="game.status.status == 'In Progress'">
                <b>On 1B:</b>
                {{game.runners_on_base.runner_on_1b.name_display_roster}}
            </div>
            <div class="baserunners_2b"
                style="width: 150px; text-align: left; float: left; font-size: 12px;"
                ng-if="game.status.status == 'In Progress'">
                <b>On 2B:</b>
                {{game.runners_on_base.runner_on_2b.name_display_roster}}
            </div>
            <div class="baserunners_3b"
                style="width: 150px; text-align: left; float: left; font-size: 12px;"
                ng-if="game.status.status == 'In Progress'">
                <b>On 3B:</b>
                {{game.runners_on_base.runner_on_3b.name_display_roster}}
            </div>
            <div class="winning_pitcher"
                style="margin-left: 50px; text-align: left; width: 325px; float: left; font-size: 12px;"
                ng-if="game.winning_pitcher && game.status.status != 'Postponed' && game.status.status != 'Cancelled'">
                <b>W:</b> {{game.winning_pitcher.first}}
                {{game.winning_pitcher.last}}
                ({{game.winning_pitcher.wins}}-{{game.winning_pitcher.losses}},
                {{game.winning_pitcher.era}})
            </div>
        </div>
        <div class="row" style="background-color: #ffffcc">
            <div class="losing_pitcher"
                style="margin-left: 50px; text-align: left; width: 325px; float: left; font-size: 12px;"
                ng-if="game.winning_pitcher && game.status.status != 'Postponed' && game.status.status != 'Cancelled'">
                <b>L:</b> {{game.losing_pitcher.first}}
                {{game.losing_pitcher.last}}
                ({{game.losing_pitcher.wins}}-{{game.losing_pitcher.losses}},
                {{game.losing_pitcher.era}})
            </div>
            <!--<div class="most_recent_play" style="margin-left: 50px; text-align: left; width: 540px; float: left; font-size: 12px;" ng-if="game.pbp.last"><b>Last play:</b> {{game.pbp.last}}</div>-->
        </div>
        <div class="row" style="background-color: #ffffcc">
            <div class="save_pitcher"
                style="margin-left: 50px; text-align: left; width: 325px; float: left; font-size: 12px;"
                ng-if="game.save_pitcher && game.save_pitcher.id != '' && game.save_pitcher.saves != 1">
                <b>SV:</b> {{game.save_pitcher.first}}
                {{game.save_pitcher.last}}
                ({{game.save_pitcher.wins}}-{{game.save_pitcher.losses}},
                {{game.save_pitcher.era}}, {{game.save_pitcher.saves}}
                saves)
            </div>
            <div class="save_pitcher"
                style="margin-left: 50px; text-align: left; width: 325px; float: left; font-size: 12px;"
                ng-if="game.save_pitcher && game.save_pitcher.id != '' && game.save_pitcher.saves == 1">
                <b>SV:</b> {{game.save_pitcher.first}}
                {{game.save_pitcher.last}}
                ({{game.save_pitcher.wins}}-{{game.save_pitcher.losses}},
                {{game.save_pitcher.era}}, {{game.save_pitcher.saves}} save)
            </div>
        </div>
        <div class="row" style="background-color: #ffffcc">
            <!-- Homer Array -->
            <div class="home_runs"
                style="margin-left: 50px; text-align: left; width: 100%; float: left; font-size: 12px;"
                ng-if="game.home_runs.player.length > 0">
                <b>HR:</b> <span
                    ng-repeat="x in game.home_runs.player"> {{
                    x.name_display_roster}} {{x.team_code | uppercase}}
                    {{x.std_hr}}{{$last ? '' : ', '}} </span>
            </div>
            <!-- Single Homer -->
            <div class="home_runs"
                style="margin-left: 50px; text-align: left; width: 100%; float: left; font-size: 12px;"
                ng-if="game.home_runs.player && game.home_runs.player != '' && game.home_runs.player.length == undefined">
                <b>HR: </b> <span>
                    {{game.home_runs.player.name_display_roster}}
                    {{game.home_runs.player.team_code | uppercase}}
                    {{game.home_runs.player.std_hr}} </span>
            </div>
        </div>
        <div class="horizontal-divider"
            style="height: 1px; margin-top: 10px; border: 0; background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0))"
            ng-show="game.home_team_name != null"></div>
    </div>
    <div class="row" id="error" ng-show="scoreboards[0] == undefined">
        <div class="error-message col-md-6 col-xs-6">No games today</div>
    </div>
    <div class="row" id="server-error" style="display: none">
        <div class="server-message col-md-6 col-xs-6">Server Error</div>
</body>
</html>
