<?php

// どの路線なら目的地に着けるかを検索する
function findDeptPolls( $handle, $startpolls, $destpolls ) {
    $timetable_candidate = [];
    $busroutepattern = 'odpt:busroutePattern';
    $busstoppoleorder = 'odpt:busstopPoleOrder';
    $busstoppoletimetable = 'odpt:busstopPoleTimetable';
    $same_as = 'owl:sameAs';
    $busstoppole = 'odpt:busstopPole';
    $dctitle = 'dc:title';
    
    //目的地バス停名を取り出しておく
    $destpoll_names = [];
    
    // 目的地バス停の配列を整理する
    $destpolls_copy = $destpolls;
    $destpolls = [];
    foreach ( $destpolls_copy as $destpoll ) {
        $destpolls[] = $destpoll;
    }
    //print_r( $destpolls );
    for ( $i = 0; $i < count( $destpolls ); $i++ ) {
        $destpoll_names[$i] = $destpolls[$i]->$same_as;
    }

    $route_nums = [];
    $route_names_table = [];

    //各バス停から乗れる路線を取り出す
    //各バス停ごとにループを回す
    foreach ( $startpolls as $startpoll ) {
        //各バス停を通る路線ごとにチェックを行なう
        //print_r( $startpoll->$busroutepattern );
        foreach ( $startpoll->$busroutepattern as $routes ) {
            preg_match( '/(odpt.BusroutePattern:.*)$/', $routes, $route_names );
            
            //バス停の路線を検索して、目的地バス停があればそのバス停を乗車可能候補とする
            $baseurl = Config::get('base.url');
            $access_token = Config::get('access.token');  
            $url = $baseurl.'odpt:BusroutePattern?acl:consumerKey='. $access_token . "&owl:sameAs={$route_names[1]}";
            //print "$url\n";
            $results = getDataFromAPI( $handle, $url ); 
            if ( gettype( $results ) == 'string' ) {
                return [ $results, 0 ];
            }

            //検索した路線ごとにチェックする
            foreach ( $results as $result ) {
                // 路線のバス停名を配列にしておく
                $route_poll_names = [];
                for ( $i = 0; $i < count( $result->$busstoppoleorder); $i++ ) {
                    $route_poll_names[$i] = $result->$busstoppoleorder[$i]->$busstoppole;
                };
                $dept_num = array_search( $startpoll->$same_as, $route_poll_names );
                foreach ( $destpoll_names as $destpoll_name ) {
                    //print "$destpoll_name\n";
                    $dest_num = array_search( $destpoll_name, $route_poll_names );
                    if ( $dept_num !== false && $dest_num !== false ) {
                        //目的地バス停が出発地バス停の後に来たら、そのバス停を出発地候補にする 
                        if ( $dept_num < $dest_num ) {
                            if ( ! in_array($result->$same_as, $route_nums ) ) {
                                $route_nums[] = $result->$same_as;
                                $route_names_table[ $result->$same_as ] = $result->$dctitle;
                            } else {
                                break 2;
                            }
                            if ( ! in_array( $startpoll->$busstoppoletimetable, $timetable_candidate ) ) {
                                $timetable_candidate[] = $startpoll->$busstoppoletimetable;
                            }
                            break 2;        
                        }
                    }
                }                  
            }
        }
    }
    return [ $route_names_table, $timetable_candidate ];
}
