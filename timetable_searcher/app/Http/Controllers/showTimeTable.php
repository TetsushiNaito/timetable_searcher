<?php

namespace App\Http\Controllers;

class showTimeTable {
    public function show_timetable( $deptpoll_name, $destpoll_name, $line_num, $holiday ) {
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

        // 今日は平日土曜休日のいずれなのかを調べる
        if ( $holiday == 1 ) { //強制休日ダイヤモード
            $day = 'Sunday';
        } else {
            $day = dayCheck();
        }
        //$url = BASEURL . "place/odpt:Station?lon={$lon}&lat={$lat}&radius={$radius}&acl:consumerKey=" . ACCESSTOKEN;
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE);

        //出発地のバス停を検索して、各バス停の情報を得る
        $startpolls = retrieveDeptPolls( $ch, $deptpoll_name );
        //print_r( $startpolls );

        //目的地のバス停の情報を得る
        $destpolls = getPoll( $ch, $destpoll_name );

        //出発地バス停から、目的地に行く路線の時刻表を特定する
        [ $route_names_table, $table_candidate ] = findDeptPolls( $ch, $startpolls, $destpolls );

        // 調べるべき時刻表を特定する
        $tables = retrieveTimeTable( $day, $table_candidate );
        //print_r( $tables );

        $timetable = new TimeTable;
        $timetable->depr_poll = $deptpoll_name;
        $timetable->dest_poll = $destpoll_name;
        $timetable->times = makeTable( $ch, $route_names_table, $tables );

        $nowtime = $timetable->getDeptTimeNow( $line_num );
        //print_r( mb_convert_encoding( $nowtime, 'SJIS', 'UTF-8' ) );
        $line = [];
        for ( $k = 0; $k < count($nowtime); $k++ ) {
            $line[$k] = $nowtime[$k]->dept_time . " " . $nowtime[$k]->route_name . " " . $nowtime[$k]->note;
        }
        curl_close( $ch );
        if ( count($line) == 0 ) {
            $line[0] = "終了";
        }
        return $line;
        //print_r( $table_all );

    }
}