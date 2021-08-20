<?php

// 調べるべき時刻表のリストを得る
function retrieveTimeTable( $day, $timetables ) {
    // 平日/土曜/休日の時刻表のどれを参照すべきか選択する  
    foreach ( $timetables as $timetable ) {
        $filtereds = array_filter( $timetable, function($value) use ($day) {
            return preg_match( '/'.$day.'/', $value, $array );
        });
        foreach( $filtereds as $filtered ) {
            $results[] = $filtered;
        }
    }
    return $results;
}