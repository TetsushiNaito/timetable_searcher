@extends('layouts.app_base')

@section('submit')
<?php
    if ( ! isset( $_COOKIE['depr_polls'] ) || ! isset( $_COOKIE['dest_polls'] ) ) {
       header("Location: http://localhost/submit");
       exit;
    }
?>
@endsection

@section('content')
<div id="app">
 <header-component></header-component>
</div>
@endsection