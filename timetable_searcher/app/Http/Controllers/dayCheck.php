<?php

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
