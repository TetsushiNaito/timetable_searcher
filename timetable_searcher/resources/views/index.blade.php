@extends( 'layouts.base' )

@section( 'title', 'Timetable Searcher')

@section( 'header')
    @component( 'components.header ')
@endsection

@section( 'content' )
    <form action="/" method='post'>
    @csrf
    <div id="route_name">
        <!-- 出発地バス停名のプルダウン、要素はCookieから取り出す -->
        <select name="depr_poll">
            <option value="{{$depr_poll}}">{{$depr_poll}}</option>  
        </select>
        @if ( $deprs !== null )
            @for ( $i = 0; $i < count( $deprs ); $i++ )
                <option value="{{$deprs[$i]}}">{{$deprs[$i]}}</option>
            @endfor
        @endif
        <!-- 目的地バス停名のプルダウン、要素はCookieから取り出す -->
        <select name="dest_poll">
            <option value="{{$dest_poll}}">{{$dest_poll}}</option>
        </select>
        @if ( $dests !== null )
            @for ( $i = 0; $i < count( $dests ); $i++ )
                <option value="{{$dests[$i]}}">{{$dests[$i]}}</option>
            @endfor
        @endif
        <!-- 最後に選んだルートの結果を表示するようにする(cookie使うか) -->
    </div>
    <div id="timetable">
    @for ( $i = 0; $i < count( $timetable_lines ); $i++ )
        {{$timetable_lines[$i]}}<br />
    @endfor
    </div>
    <div id="reload_button">
        <!-- 出発時刻の表示 -->
        <input type="submit" value="更新">
    </div>
    <div id="holiday_button">
        <!-- 祝日ダイヤ適用ボタン -->
        <input type="submit" value="今日は祝日">
    </div>
@endsection

@section( 'footer')
    @component( 'components.footer' )
@endsection
