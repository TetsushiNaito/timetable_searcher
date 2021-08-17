<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>index</title>
</head>
<body>
<div id="base">
    <div id="header">
    <!-- タイトルバナー -->
    </div>

    <!-- 新規ルート追加登録はメニューからさせることにしよう -->
    
    <div id="route_name">
    <!-- 出発地バス停名 ～ 目的地バス停名の表示（プルダウン） -->
    <!-- 複数ルート登録できてプルダウンで選べるのがいいよなぁ -->
    <!-- 最後に選んだルートの結果を表示するようにする(cookie使うか) -->
        <?php
            if ( "ルートがひとつもない" ) {
        ?>
                <div id="new_submit_button">
                <!--ルートがひとつもなければ、代わりに「新規登録」ボタンを表示させる -->
                </div>
        <?php
            }    
        ?>
    </div>
    <div id="timetable">
    <!-- 出発時刻の表示 -->
    </div>
    <div id="reload_button">
    <!-- 更新ボタン -->
    </div>
    <div id="holiday_button">
    <!-- 祝日ダイヤ適用ボタン -->
    </div>
    <div id="footer">
    <!-- フッター -->       
    </div>
</div>
</body>
</html>