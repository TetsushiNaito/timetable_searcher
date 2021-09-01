@extends( 'layouts.base' )

@section( 'title', 'Submit')

@section( 'header' )
    @component( 'components.header' )
@endsection

@section( 'content' )
    <div id="input_area">
        <form action="/confirm" method="POST">
        @csrf
            <div class="input_form">
                <!-- 出発地バス停名の入力 -->
                <label for="depr_poll">出発地バス停：</label>
                <input type="text" name="depr_poll" value="{{old('depr_poll')}}"><span id="result1"></span>
                @if ( $errors->has('depr_poll'))
                {{$errors->first('depr_poll')}}
                @endif
            </div> 
            <div class="input_form">
                <!-- 目的地バス停名の入力 -->
                <label for="dest_poll">目的地バス停：</label>
                <input type="text" name="dest_poll" value="{{old('dest_poll')}}"><span id="result2"></span>
                @if ( $errors->has('dest_poll'))
                {{$errors->first('dest_poll')}}
                @endif
            </div>
            <div id="list_num">
                時刻の表示数：
                <!-- 時刻の表示数の選択 --->
                <select name="line_num">
                    <option>1</option>
                    <option>2</option>
                    <option selected>3</option>
                    <option>4</option>
                    <option>5</option>
                    <option>6</option>
                    <option>7</option>
                    <option>8</option>
                    <option>9</option>
                    <option>10</option>
                </select>
            </div>
            <div id="submit_button">
                <!-- submit ボタン --->
                <input type="submit" name="登録">
            </div>
        </form>
    </div>
@endsection

@section( 'footer' )
    @component( 'components.footer')
@endsection
