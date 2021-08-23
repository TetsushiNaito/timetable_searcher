@extends( 'layouts.base' )

@section( 'title', 'Confirm')

@section( 'header' )
    @component( 'components.header' )
@endsection

@section( 'content' )
@if ( $depr_poll && $depr_poll && $line_num )
    出発地バス停：{{$depr_poll}}<br />
    目的地バス停：{{$dest_poll}}<br />
    時刻表示数：{{$line_num}}<br />
    <br />
    を設定しました。
    <button type="button" onclick="location.href='/{{$depr_poll}}/{{$dest_poll}}/{{$line_num}}/0'">時刻表画面へ</button>
@else
おかしいですね？？
@endif
@endsection

@section( 'footer' )
    @component( 'components.footer' )
@endsection
