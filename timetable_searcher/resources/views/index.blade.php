@extends( 'layouts.base' )

@section( 'title', 'Timetable Searcher')

@section( 'header')
    @component( 'components.header ')
@endsection

@section( 'content' )

<form name="selection" action="/">
@csrf
    <div id="route_name">
    <!-- 出発地バス停名のプルダウン、要素はCookieから取り出す -->
    <select name="departure">
        <option value="{{$depr_poll}}">{{$depr_poll}}</option>  
        @if ( $deprs !== null )
        @for ( $i = 0; $i < count( $deprs ); $i++ )
        <option value="{{$deprs[$i]}}">{{$deprs[$i]}}</option>
        @endfor
        @endif
    </select>
    <!-- 目的地バス停名のプルダウン、要素はCookieから取り出す -->
    <select name="destination">
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
    <script type="text/javascript">
        function reloadTable() {
            // 出発地のバス停名
            let dept_idx = document.selection.departure.selectedIndex;
            let dept_poll_name = document.selection.departure[dept_idx].text;
            // 目的地のバス停名
            let dest_idx = document.selection.destination.selectedIndex;
            let dest_poll_name = document.selection.destination[dest_idx].text;
            //祝日チェックボックス確認
            let hc = 0;
            holiday_check = document.selection.isholiday.checked;
            if ( holiday_check == true ) { hc = 1; }
            
            let xhr = new XMLHttpRequest();
            let url = 'http://localhost:8000/' + dept_poll_name + '/' + dest_poll_name + '/' + '3' + '/' + hc;
            let data = [];
            xhr.open('GET', url, true);
            xhr.responseType = 'json';
            xhr.onload = function (e) {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        data = <?="$timetable_lines_JSON"?>;
                        alert( data );
                    } else {
                    console.error(xhr.statusText);
                    }               
                }
            };
            xhr.onerror = function (e) {
                console.error(xhr.statusText);
            };
            alert( url );
            xhr.send(null); 
        }
    </script>
@endsection
        
@section( 'footer')
    @component( 'components.footer' )
@endsection
