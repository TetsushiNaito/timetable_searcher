@extends('layouts.app_base')

@section('submit')
<?php
    if ( ! isset( $_COOKIE['depr_polls'] ) || ! isset( $_COOKIE['dest_polls'] ) ) {
       header("Location: https://kurubus.com/submit");
       exit;
    }
?>
@endsection

@section('content')
<div id="app">
 <header-component></header-component>
</div>
@endsection