@extends( 'layouts.app_base' )

@section( 'title', 'Confirm')

@section( 'content' )
@if ( $depr_poll && $depr_poll )
<table class="table table-borderless">
    <tr>
        <td class="text-center">
            出発地バス停：{{$depr_poll}}
        </td>
    </tr>
    <tr>
        <td class="text-center">
            目的地バス停：{{$dest_poll}}
        </td>
    </tr>
    <tr>
        <td class="text-center">
            を設定しました。
        </td>
    </tr>
    <tr>
        <td class="text-center">
            <button type="button" onclick="location.href='/timetable'">時刻表画面へ</button>
        </td>
    </tr>
</table>
@else
おかしいですね？？
@endif
@endsection
