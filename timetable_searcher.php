<?php

require_once 'class.php';
require_once 'isHoliday.php';
require_once 'findDeptPolls.php';
require_once 'makeTable.php';

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
