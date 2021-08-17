<?php

require_once 'class.php';

// base url
const BASEURL = 'https://api-tokyochallenge.odpt.org/api/v4/';
// アクセストークン
const ACCESSTOKEN = 'e5f8c0903e7db287cbe3491292f9d6f42d3e204ea8970378cd7f4f48bc335b1e';

//日吉駅の緯度経度
$lat = 35.5539161;
$lon = 139.6470643;

//プラウドシティ日吉の緯度経度
//$lat = 35.54565951812466;
//$lon = 139.64529179932634;

// 検索半径
// $radius = 150;

//出発地のバス停
$deptpoll_name = '日吉駅東口';
//$deptpoll_name = 'プラウドシティ日吉';
//$deptpoll_name = '宮前西町';
//$deptpoll_name = '江川町';

//目的地のバス停
$destpoll_name = '宮前西町';
//$destpoll_name = '樋橋';
//$destpoll_name = '江川町';
//$destpoll_name = '越路';
//$destpoll_name = '日大高校正門';
//$destpoll_name = '日吉駅東口';

// 表示する候補の数
$num_of_times = 3;

//$url = BASEURL . "place/odpt:Station?lon={$lon}&lat={$lat}&radius={$radius}&acl:consumerKey=" . ACCESSTOKEN;
$ch = curl_init();
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE);

//出発地のバス停を検索して、各バス停の情報を得る
$startpolls = retrieveDeptPoles( $ch, $deptpoll_name );
//print_r( $startpolls );

//目的地のバス停の情報を得る
$destpolls = getPoll( $ch, $destpoll_name );

//出発地バス停から、目的地に行く路線の時刻表を特定する
[ $route_names_table, $table_candidate ] = findDeptPolls( $ch, $startpolls, $destpolls );

//print "hogehogehoge\n";
//print_r( $route_names_table );
//print "hogehogehoge\n";
//print_r( $table_candidate );

// 調べるべき時刻表を特定する
$tables = retrieveTimeTable( $table_candidate );
//print_r( $tables );

$timetable = new TimeTable;
$timetable->dept_poll = $deptpoll_name;
$timetable->dest_poll = $destpoll_name;
$timetable->times = makeTable( $ch, $route_names_table, $tables );

// 試運転
$nowtime = $timetable->getDeptTimeNow( $num_of_times );
//print_r( mb_convert_encoding( $nowtime, 'SJIS', 'UTF-8' ) );
for ( $k = 0; $k < count($nowtime); $k++ ) {
    print_r( $nowtime[$k] );
}
//print_r( $table_all );

curl_close( $ch );


//緯度経度から近くのバス停を得る
//function retrieveDeptPoles( $handle, $lon, $lat, $radius ) {
//    $url = BASEURL."places/odpt:BusstopPole?lon={$lon}&lat={$lat}&radius={$radius}&acl:consumerKey=".ACCESSTOKEN;
//    curl_setopt( $handle, CURLOPT_URL, $url );
//    return json_decode( curl_exec( $handle ), FALSE );
//    //print_r( $result );
//}

function getDataFromAPI( $handle, $url ) : array {
    curl_setopt( $handle, CURLOPT_URL, $url );
    curl_setopt( $handle, CURLOPT_RETURNTRANSFER, true );
    return json_decode( curl_exec( $handle ), FALSE );
}

function retrieveDeptPoles( $handle, $deptpoll_name ) {
    $deptpoll_name = urlencode( $deptpoll_name );
    $url = BASEURL.'odpt:BusstopPole?acl:consumerKey='. ACCESSTOKEN . "&dc:title={$deptpoll_name}";
    return getDataFromAPI( $handle, $url);
}

//バス停名からバス停の情報を得る
function getPoll( $handle, $name ) {
    $name = urlencode( $name );
    $url = BASEURL.'odpt:BusstopPole?acl:consumerKey='. ACCESSTOKEN . "&dc:title={$name}";
    $results = getDataFromAPI( $handle, $url );
    $polls_array = [];

    //バス停番号がないデータは省く
    $busstoppollnumber = 'odpt:busstopPoleNumber';
    foreach ( $results as $result ) {
    //    if ( $result->$busstoppollnumber == '' ) { continue; }
        $polls_array[ $result->$busstoppollnumber ] = $result;
    }
    return $polls_array;
}

function findDeptPolls( $handle, $startpolls, $destpolls ) {
    $timetable_candidate = [];
    $busroutepattern = 'odpt:busroutePattern';
    $busstoppollnumber = 'odpt:busstopPoleNumber';
    $busstoppoleorder = 'odpt:busstopPoleOrder';
    $busstoppoletimetable = 'odpt:busstopPoleTimetable';
    $same_as = 'owl:sameAs';
    $busstoppole = 'odpt:busstopPole';
    $dctitle = 'dc:title';
    
    //目的地バス停名を取り出しておく
    $destpoll_names = [];
    //$hoge = count( $destpolls );
    //print "hoge $hoge\n";
    
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

        //busstopPollNumberが無いものは処理しない
        //if ( $startpoll->$busstoppollnumber == '' ) { continue; } 

        //各バス停を通る路線ごとにチェックを行なう
        //print_r( $startpoll->$busroutepattern );
        foreach ( $startpoll->$busroutepattern as $routes ) {
            preg_match( '/(odpt.BusroutePattern:.*)$/', $routes, $route_names );
            
            //バス停の路線を検索して、目的地バス停があればそのバス停を乗車可能候補とする
            $url = BASEURL.'odpt:BusroutePattern?acl:consumerKey='. ACCESSTOKEN . "&owl:sameAs={$route_names[1]}";
            //print "$url\n";
            $results = getDataFromAPI( $handle, $url );              

            //検索した路線ごとにチェックする
            foreach ( $results as $result ) {
                //print "result\n";
                //print_r( $result );
                // 路線のバス停名を配列にしておく
                $route_poll_names = [];
                for ( $i = 0; $i < count( $result->$busstoppoleorder); $i++ ) {
                    $route_poll_names[$i] = $result->$busstoppoleorder[$i]->$busstoppole;
                };
                //print_r( $route_poll_names );
                // print_r( array_filter( $route_poll_names, function($v) { return preg_match( '/Miyamaenishimachi/', $v ); }));
                //print "startpoll " . $startpoll->$same_as . "\n";
                $dept_num = array_search( $startpoll->$same_as, $route_poll_names );
                foreach ( $destpoll_names as $destpoll_name ) {
                    //print "$destpoll_name\n";
                    $dest_num = array_search( $destpoll_name, $route_poll_names );
                    if ( $dept_num !== false && $dest_num !== false ) {
                        //目的地バス停が出発地バス停の後に来たら、そのバス停を出発地候補にする 
                        //print "$destpoll_name\n";
                        //print_r($route_poll_names);                        ;
                        if ( $dept_num < $dest_num ) {
                            //print "dept $dept_num dest $dest_num\n";
                            //print "resultsameas " . $result->$same_as . "\n";
                            if ( ! in_array($result->$same_as, $route_nums ) ) {
                                //print "route_nums "; print_r( $route_nums );
                                //print "same_as "; print_r($result->$same_as);
                                $route_nums[] = $result->$same_as;
                                $route_names_table[ $result->$same_as ] = $result->$dctitle;
                            } else {
                                break 2;
                            }
                            if ( ! in_array( $startpoll->$busstoppoletimetable, $timetable_candidate ) ) {
                                //print "fuga "; print_r($timetable_candidate);
                                //print "hoge "; print_r($startpoll->$busstoppoletimetable );
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

// 調べるべき時刻表のリストを得る
function retrieveTimeTable( $timetables ) {
    // 平日/土曜/休日の時刻表のどれを参照すべきか選択する
    foreach ( $timetables as $timetable ) {
        $filtereds = array_filter( $timetable, function($value) {
            $day = dayCheck();
            return preg_match( '/'.$day.'/', $value, $array );
        });
        foreach( $filtereds as $filtered ) {
            $results[] = $filtered;
        }
    }
    return $results;
}

// 乗車可能なバスの時刻表を作成する
function makeTable( $handle, $route_names_table, $timetables ) {
    $odpt_object = 'odpt:busstopPoleTimetableObject';
    $odpt_dept = 'odpt:departureTime';
    $odpt_pattern = 'odpt:busroutePattern';
    $odpt_note = 'odpt:note';
    $dc_title = 'dc:title';
    $odpt_destsign = 'odpt:destinationSign';
    $route_pattern = array_keys( $route_names_table );
    $times = [];

    // 各時刻表ごとにループを回す
    foreach( $timetables as $timetable ) {
        // 時刻表の中身を得る
        $url = BASEURL.'odpt:BusstopPoleTimetable?acl:consumerKey='. ACCESSTOKEN . "&owl:sameAs=$timetable";
        //print "$url\n";
        $contents_all = getDataFromAPI( $handle, $url );
        // 路線の行き先を取っておく
        $route_title = explode(':', $contents_all[0]->$dc_title)[0];
        $contents = $contents_all[0]->$odpt_object;
        // 使える路線の時刻を路線名と共に保存する
        for ( $i = 0; $i < count( $contents ); $i++ ) {
            //print_r( $contents[$i] );
            if ( in_array( $contents[$i]->$odpt_pattern, $route_pattern ) ) {
                $time = new Time;
                $time->dept_time = $contents[$i]->$odpt_dept;
                $time->route_dest_name = $route_title;
                $time->route_name = $route_names_table[ $contents[$i]->$odpt_pattern ];
                if ( property_exists( $contents[$i], $odpt_note ) ) {
                    $time->note = $contents[$i]->$odpt_note; 
                }
                if ( property_exists( $contents[$i], $odpt_destsign ) ) {
                    $time->destsign = $contents[$i]->$odpt_destsign;
                }
                $times[] = $time;
            }
        }
    }
    //print_r( $table_all );
    // 時刻ごとに連想配列をソートする
    usort( $times, function( $a, $b ) {
        return strtotime( $a->dept_time ) <=> strtotime( $b->dept_time );
    } );
    return $times;
}

//今日は平日か土曜日か休日か   
function dayCheck() {
    // 祝日チェック
    if ( isHoliday( strtotime( 'Y-m-d' ) ) ) {
        return 'Sunday';
    }
    switch ( strtotime( 'l' ) ) {
        case 'Saturday' :
            $today = 'Saturday';
            break;
        case 'Sunday' :
            $today = 'Sunday';
            break;
        default :
            $today = 'Weekday';
            break;
    }
    return $today;
}