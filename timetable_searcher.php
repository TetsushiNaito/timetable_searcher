<?php

require_once 'class.php';
require_once 'isHoliday.php';
require_once 'findDeptPolls.php';

// base url
const BASEURL = 'https://api-tokyochallenge.odpt.org/api/v4/';
// アクセストークン
const ACCESSTOKEN = 'e5f8c0903e7db287cbe3491292f9d6f42d3e204ea8970378cd7f4f48bc335b1e';

// 今日は平日土曜休日のいずれなのかを調べる
$day = dayCheck();

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


//今日は平日か土曜日か休日か   
function dayCheck() {
    // 祝日チェック
    if ( isHoliday( date( 'Y-m-d' ) ) ) {
        return 'Sunday';
    }
    switch ( date( 'l' ) ) {
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

// 調べるべき時刻表のリストを得る
function retrieveTimeTable( $timetables ) {
    // 平日/土曜/休日の時刻表のどれを参照すべきか選択する  
    foreach ( $timetables as $timetable ) {
        $filtereds = array_filter( $timetable, function($value) {
            global $day;
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
