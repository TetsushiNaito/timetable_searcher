@extends( 'layouts.app_base' )

@section( 'title', 'Confirm')

@section( 'content' )
@if ( $depr_poll && $depr_poll )
<table class="table table-borderless">
    <tr>
        <td>
            出発地バス停：{{$depr_poll}}
        </td>
    </tr>
    <tr>
        <td>
            目的地バス停：{{$dest_poll}}
        </td>
    </tr>
    <tr>
        <td>
            を設定しました。
        </td>
    </tr>
    <tr>
        <td>
            <button type="button" onclick="location.href='/timetable'">時刻表画面へ</button>
        </td>
    </tr>
</table>
@else
おかしいですね？？
@endif
@endsection
