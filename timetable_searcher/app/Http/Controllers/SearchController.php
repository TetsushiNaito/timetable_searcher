<?php

namespace App\Http\Controllers;

require_once 'dayCheck.php';
require_once 'findDeptPolls.php';
require_once 'getDataFromAPI.php';
require_once 'getPoll.php';
require_once 'isHoliday.php';
require_once 'makeTable.php';
require_once 'retrieveDeptPolls.php';
require_once 'retrieveTimeTable.php';
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
        $showTimeTable = new showTimeTable;
        $timetable_lines = $showTimeTable->show_timetable( $depr_poll, $dest_poll, $line_num );
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
            $cookie = $request->cookie('depr_polls');
            // 登録されていないバス停名のみ追加する
            $array = explode( ':', $cookie );
            if ( ! array_search( $depr_poll, $array ) ) {
                $response->cookie( 'depr_polls', $depr_poll . ':' . $cookie );
            }
        }
        else {
            $response->cookie( 'depr_polls', $depr_poll . ':' );
        }
        if ( isset( $_COOKIE['dest_polls'] ) ) {
            $cookie = $request->cookie( 'dest_polls');
            // 登録されていないバス停名のみ追加する
            $array = explode( ':', $cookie );
            if ( ! array_search( $dest_poll, $array ) ) {
                $response->cookie( 'dest_polls', $dest_poll . ':' . $cookie );
            }
        }
        else {
            $response->cookie( 'dest_polls', $dest_poll . ':' );
        }
        $response->cookie( 'line_num', $request->line_num );
        return $response;
    }
}