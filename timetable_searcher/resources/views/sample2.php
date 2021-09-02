<?php
    if ( ! isset( $_COOKIE['depr_polls'] ) || ! isset( $_COOKIE['dest_polls'] ) ) {
       header("Location: http://localhost/submit");
       exit;
    }
?>