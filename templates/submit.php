<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>submit</title>
</head>
<body>
<div id="base">
    <div id="header">
    <!-- タイトルバナー -->
    </div>
    <div id="submit">
        <div id="submit_title">
        <!-- 新規登録画面タイトル -->
        </div>
        <div id="input_area">
            <form action="submit_route.php" method="POST">
                <div class="input_form">
                    <!-- 出発地バス停名の入力 -->
                    出発地バス停：
                    <input type="text" name="dept_poll">
                </div> 
                <div class="input_form">
                    <!-- 目的地バス停名の入力 -->
                    目的地バス停：
                    <input type="text" name="dest_poll">
                </div>
                <div id="list_num">
                    時刻の表示数：
                    <!-- 時刻の表示数の選択 --->
                    <select name="line_num">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
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
    </div>
    <div id="footer">
    <!-- フッター -->
    </div>
</div>    
</body>
</html>