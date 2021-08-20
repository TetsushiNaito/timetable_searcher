<?php

use Illuminate\Support\Facades\Config;

//バス停名からバス停の情報を得る
function getPoll( $handle, $name ) {
    $name = urlencode( $name );
    $baseurl = Config::get('base.url');
    $access_token = Config::get('access.token');        
    $url = $baseurl.'odpt:BusstopPole?acl:consumerKey='. $access_token . "&dc:title={$name}";
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
