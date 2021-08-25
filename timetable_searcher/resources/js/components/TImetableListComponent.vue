<template>
     <div class="container">
                        {{ hogehoge }}
            <label><b>出発：</b>
                <select id="depr_poll_menu">
<!--                    <option v-for="deprpolls in deprpoll" :keys="deprpoll.name">{{ deprpoll.name }}</option> -->
                </select>
            </label>
            <label><b>行き先：</b>
                <select id="dest_poll_menu">
                    <option v-for="destpolls in destpoll" :keys="destpoll.name">{{ destpoll.name }}</option>
                </select>
            </label>
         <table class="table table-hover">
             <thead class="thead-light">
             <tr>
                 <th scope="col">#</th>
                 <th scope="col">発車時刻</th>
                 <th scope="col">系統</th>
                 <th scope="col">行き先等備考</th>
             </tr>
             </thead>
             <tbody>
             <tr>
                 <th scope="row">1</th>
                 <td>09:58</td>
                 <td>日９２</td>
                 <td>住.住宅経由綱島駅行</td>
             </tr>
             <tr>
                 <th scope="row">2</th>
                 <td>10:15</td>
                 <td>日９５</td>
                 <td>Ｔ.東急バス</td>
             </tr>
             <tr>
                 <th scope="row">3</th>
                 <td>10:17</td>
                 <td>日９２</td>
                 <td>住.住宅経由綱島駅行</td>
             </tr>
             </tbody>
         </table>
     </div>
</template>
 
<script>
function GetCookies()
{
    var result = new Array();

    var allcookies = document.cookie;
    if( allcookies != '' )
    {
        var cookies = allcookies.split( '; ' );

        for( var i = 0; i < cookies.length; i++ )
        {
            var cookie = cookies[ i ].split( '=' );

            // クッキーの名前をキーとして 配列に追加する
            result[ cookie[ 0 ] ] = decodeURIComponent( cookie[ 1 ] );
        }
    }

    return result;
}
alert(document.cookie );

    export default {
        data: function() {
            return {
                hogehoge: 'hogehoge ' + Vue.$cookies.get('depr_polls') + 'hogehoge'
            }
        }
    }
 </script>