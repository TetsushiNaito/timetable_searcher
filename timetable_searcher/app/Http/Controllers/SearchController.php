<?php

namespace App\Http\Controllers;

require_once 'isHoliday.php';
require_once 'findDeptPolls.php';
require_once 'makeTable.php';
require_once 'TimeTable.php';

//トップページ
const TOPPAGE = 'http://localhost:8000/';
// バス停の新規登録画面
const SUBMITPAGE = 'http://localhost:8000/submit/';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use PhpParser\Node\Expr\AssignOp\ShiftLeft;
use Time;

// base url
Config::set('base.url', 'https://api-tokyochallenge.odpt.org/api/v4/') ;

// アクセストークン
Config::set('access.token', 'e5f8c0903e7db287cbe3491292f9d6f42d3e204ea8970378cd7f4f48bc335b1e');

class SearchController extends Controller
{
    public function index( Request $request ) {
        //初回はCookieが空なので必ず登録画面に飛ばす
        if ( ! isset( $_COOKIE['depr_polls'] ) || ! isset( $_COOKIE['dest_polls'] ) ) {
         //   header( 'Location: ' . SUBMITPAGE );
            return redirect( '/submit' );
        }        
        $depr_polls_cookie = $request->cookie( 'depr_polls' );
        $dest_polls_cookie = $request->cookie( 'dest_polls' );
        $deprs = explode( ':', $depr_polls_cookie );
        $dests = explode( ':', $dest_polls_cookie );
        $line_num = $request->cookie( 'line_num' );
        $depr_poll = array_shift( $deprs );
        $dest_poll = array_shift( $dests );
        $timetable_lines = $this->show_timetable( $depr_poll, $dest_poll, $line_num );
        $data = [
            'deprs' => $deprs,
            'dests' => $dests,
            'depr_poll' => $depr_poll,
            'dest_poll' => $dest_poll,
            'line_num' => $line_num,
            'timetable_lines' => $timetable_lines
        ];
        return view( 'index', $data );
    }

    public function post( Request $request ) {
        $validation_rule = [
            'depr_poll' => 'required',
            'dest_poll' => 'required',
        ];
        $this->validate( $request, $validation_rule );

        $depr_poll = $request->depr_poll;
        $dest_poll = $request->dest_poll;
        
        $response = response()->view( 'confirm', [ 
            'depr_poll' => $depr_poll,
            'dest_poll' => $dest_poll,
            'line_num' => $request->line_num
        ] );
        
        if ( isset( $_COOKIE['depr_polls'] ) ) {
            $response->cookie( 'depr_polls', $depr_poll . ':' . $_COOKIE['depr_polls'] );
        }
        else {
            $response->cookie( 'depr_polls', $depr_poll . ':' );
        }
        if ( isset( $_COOKIE['dest_polls'] ) ) {
            $response->cookie( 'dest_polls', $dest_poll . ':' . $_COOKIE['dest_polls'] );
        }
        else {
            $response->cookie( 'dest_polls', $dest_poll . ':' );
        }
        $response->cookie( 'line_num', $request->line_num );
        return $response;
    }
    
    public function show_timetable( $deptpoll_name, $destpoll_name, $line_num ) {
        // 今日は平日土曜休日のいずれなのかを調べる
        $day = $this->dayCheck();

        //出発地のバス停
        //$deptpoll_name = '日吉駅東口';
        //$deptpoll_name = 'プラウドシティ日吉';
        //$deptpoll_name = '宮前西町';
        //$deptpoll_name = '江川町';

        //目的地のバス停
        //$destpoll_name = '宮前西町';
        //$destpoll_name = '樋橋';
        //$destpoll_name = '江川町';
        //$destpoll_name = '越路';
        //$destpoll_name = '日大高校正門';
        //$destpoll_name = '日吉駅東口';

        // 表示する候補の数
        //$line_num = 3;

        //$url = BASEURL . "place/odpt:Station?lon={$lon}&lat={$lat}&radius={$radius}&acl:consumerKey=" . ACCESSTOKEN;
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE);

        //出発地のバス停を検索して、各バス停の情報を得る
        $startpolls = $this->retrieveDeptPoles( $ch, $deptpoll_name );
        //print_r( $startpolls );

        //目的地のバス停の情報を得る
        $destpolls = $this->getPoll( $ch, $destpoll_name );

        //出発地バス停から、目的地に行く路線の時刻表を特定する
        [ $route_names_table, $table_candidate ] = findDeptPolls( $ch, $startpolls, $destpolls );

        // 調べるべき時刻表を特定する
        $tables = $this->retrieveTimeTable( $table_candidate );
        //print_r( $tables );

        $timetable = new TimeTable;
        $timetable->depr_poll = $deptpoll_name;
        $timetable->dest_poll = $destpoll_name;
        $timetable->times = makeTable( $ch, $route_names_table, $tables );

        // 
        $nowtime = $timetable->getDeptTimeNow( $line_num );
        //print_r( mb_convert_encoding( $nowtime, 'SJIS', 'UTF-8' ) );
        for ( $k = 0; $k < count($nowtime); $k++ ) {
            $line[$k] = $nowtime[$k]->dept_time . " " . $nowtime[$k]->route_name . " " . $nowtime[$k]->note;
        }
        //print_r( $table_all );

        curl_close( $ch );
    }

    function getDataFromAPI( $handle, $url ) {
        curl_setopt( $handle, CURLOPT_URL, $url );
        curl_setopt( $handle, CURLOPT_RETURNTRANSFER, true );
        return json_decode( curl_exec( $handle ), FALSE );
    }

    function retrieveDeptPoles( $handle, $deptpoll_name ) {
        $deptpoll_name = urlencode( $deptpoll_name );
        $baseurl = Config::get('base.url');
        $access_token = Config::get('access.token');        
        $url = $baseurl.'odpt:BusstopPole?acl:consumerKey='. $access_token . "&dc:title={$deptpoll_name}";
        return $this->getDataFromAPI( $handle, $url);
    }
    
    //バス停名からバス停の情報を得る
    function getPoll( $handle, $name ) {
        $name = urlencode( $name );
        $baseurl = Config::get('base.url');
        $access_token = Config::get('access.token');        
        $url = $baseurl.'odpt:BusstopPole?acl:consumerKey='. $access_token . "&dc:title={$name}";
        $results = $this->getDataFromAPI( $handle, $url );
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
}