@extends( 'layouts.app_base' )

@section( 'title', 'Submit')

@section( 'content' )
    <div id="input_area">
        <form action="/confirm" method="POST">
        <table class="table table-borderless">
        @csrf
            <tr">
                <td colspan="2" class="text-center">
                    <!-- 出発地バス停名の入力 -->
                        <label for="depr_poll">出発地バス停：</label>
                        <input type="text" name="depr_poll" value="{{old('depr_poll')}}"><span id="result1"></span>
                        @if ( $errors->has('depr_poll'))
                        {{$errors->first('depr_poll')}}
                        @endif
                </td> 
            </tr>
            <tr>
                <td colspan="2" class="text-center">
                        <!-- 目的地バス停名の入力 -->
                        <label for="dest_poll">目的地バス停：</label>
                        <input type="text" name="dest_poll" value="{{old('dest_poll')}}"><span id="result2"></span>
                        @if ( $errors->has('dest_poll'))
                        {{$errors->first('dest_poll')}}
                        @endif
                </td>
            </tr>
            <tr>
                <td style="width:50%; text-align:right">
                    <button type="submit" class="btn btn-primary">登録</button>
                </td>
                <td style="width:50%; text-align:left">
                    <button type="button" class="btn btn-primary" onclick="document.location='https://kurubus.com/timetable';">取消</button>
                </td>
            </tr>
        </table>
    </form>
    </div>
@endsection