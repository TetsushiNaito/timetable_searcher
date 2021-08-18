<?php

require_once 'Encode.php';
CONST REFERER = 'https://hogehoge.com/submit.php';

function submit_route() {
    //遷移元がsubmit.phpなのか確認する
    // if ( $_SERVER['HTTP_REFERER'] != REFERER ) {
    //    print "You did something nasty !!";
    //    exit;
    //}
    // $_POSTの中身をチェックする
    if ( e($_POST['dept_poll'] === null || e($_POST['dest_poll']) ) {
        
    }


}