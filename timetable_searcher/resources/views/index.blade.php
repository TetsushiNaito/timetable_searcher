@extends( 'layouts.base' )

@section( 'title', 'Timetable Searcher')

@section( 'header')
    @component( 'components.header ')
@endsection
<script>
    function reloadTable() {
        let isHoliday = document.reload.isholiday;
        if ( isHoliday.checked ) {
            location.href = location.href.replace( /\/.$/, '/1' );
        } else {
            location.href = location.href.replace( /\/.$/, '/0' );
        }
    }
</script>
@section( 'content' )
    <form name="reload" action="/">
    @csrf
    <div id="route_name">
        <!-- 出発地バス停名のプルダウン、要素はCookieから取り出す -->
        <select name="depr_poll">
            <option value="{{$depr_poll}}">{{$depr_poll}}</option>  
            @if ( $deprs !== null )
                @for ( $i = 0; $i < count( $deprs ); $i++ )
                <option value="{{$deprs[$i]}}">{{$deprs[$i]}}</option>
                @endfor
            @endif
        </select>
        <!-- 目的地バス停名のプルダウン、要素はCookieから取り出す -->
        <select name="dest_poll">
            <option value="{{$dest_poll}}">{{$dest_poll}}</option>
            @if ( $dests !== null )
                @for ( $i = 0; $i < count( $dests ); $i++ )
                <option value="{{$dests[$i]}}">{{$dests[$i]}}</option>
                @endfor
            @endif
        </select>
        <!-- 最後に選んだルートの結果を表示するようにする(cookie使うか) -->
    </div>
    <div id="timetable">
    @for ( $i = 0; $i < count( $timetable_lines ); $i++ )
        {{$timetable_lines[$i]}}<br />
    @endfor
    </div>
    <div id="reload_button">
        <!-- 出発時刻の表示 -->
        <input type="button" value="更新" onclick="reloadTable()" />
    </div>
    <div id="holiday_button">
        <!-- 祝日ダイヤ適用チェックボックス -->
        <label><input type="checkbox" name="isholiday">今日は祝日ダイヤ</label>
    </div>
    </form>
    @endsection

@section( 'footer')
    @component( 'components.footer' )
@endsection
