var scoreboardService = angular.module('scoreboardService', [ 'ngResource' ]);

scoreboardService
        .factory(
                'getScore',
                [
                        '$resource',
                        function($resource) {
                            var res = 'http://gd2.mlb.com/components/game/'
                                    + serviceName
                                    + '/year_:yearNum/month_:monthNum/day_:dayNum/master_scoreboard.json';
                            return $resource(res, {}, {
                                get : {
                                    method : 'GET',
                                    params : {
                                        year : '@yearNum',
                                        month : '@monthNum',
                                        day : '@dayNum'
                                    }
                                }
                            });
                        } ]);