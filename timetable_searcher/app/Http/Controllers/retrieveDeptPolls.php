<?php

use Illuminate\Support\Facades\Config;

function retrieveDeptPolls( $handle, $deptpoll_name ) {
    $deptpoll_name = urlencode( $deptpoll_name );
    $baseurl = Config::get('base.url');
    $access_token = Config::get('access.token');        
    $url = $baseurl.'odpt:BusstopPole?acl:consumerKey='. $access_token . "&dc:title={$deptpoll_name}";
    return getDataFromAPI( $handle, $url);
}
