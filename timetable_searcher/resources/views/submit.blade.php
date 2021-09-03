@extends( 'layouts.app_base' )

@section( 'title', 'Submit')

@section( 'content' )
    <div id="input_area">
        <form action="/confirm" method="POST">
        @csrf
            <table class="table">
            <tr>
                <div class="input_form">
                    <!-- 出発地バス停名の入力 -->
                    <label for="depr_poll">出発地バス停：</label>
                    <input type="text" name="depr_poll" value="{{old('depr_poll')}}"><span id="result1"></span>
                    @if ( $errors->has('depr_poll'))
                    {{$errors->first('depr_poll')}}
                    @endif
                </div> 
            </tr>
            <tr>
                <div class="input_form">
                    <!-- 目的地バス停名の入力 -->
                    <label for="dest_poll">目的地バス停：</label>
                    <input type="text" name="dest_poll" value="{{old('dest_poll')}}"><span id="result2"></span>
                    @if ( $errors->has('dest_poll'))
                    {{$errors->first('dest_poll')}}
                    @endif
                </div>
            </tr>
            <tr>
                <div id="submit_button">
                    <!-- submit ボタン --->
                    <button type="submit" class="btn btn-primary">登録</button>
                    <button type="button" class="btn btn-primary" onclick="document.location='http://localhost/timetable';">取消</button>
                </div>
            </tr>
        </table>
        </form>
    </div>
@endsection