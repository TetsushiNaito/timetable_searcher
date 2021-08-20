<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;

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
        $baseurl = Config::get('base.url');
        $access_token = Config::get('access.token');        
        $url = $baseurl.'odpt:BusstopPoleTimetable?acl:consumerKey='. $access_token . "&owl:sameAs=$timetable";
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
