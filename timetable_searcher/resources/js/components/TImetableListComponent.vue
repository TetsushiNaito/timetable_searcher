<template>
     <div class="container">
        <table class="table table-borderless">
            <tr>
                <td>
                    <label><b>出発：</b>
                    <select id="depr_poll_menu">
                        <option v-for="depr in deprs" :key="depr.name" :value="depr.name">{{depr.name}}</option>
                    </select>
                    </label>
                </td>
                <td>
                    <label><b>行き先：</b>
                    <select id="dest_poll_menu">
                        <option v-for="dest in dests" :key="dest.name" :value="dest.name">{{dest.name}}</option>
                    </select>
                    </label>
                </td>
                <td>
                    <button class="btn btn-primary btn-sm">更新</button>
                </td>
                <td>
                    <input type="checkbox" id="isholiday" value="1">今日は祝日ダイヤ</input>
                </td>
            </tr>
        </table>
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
    export default {
        data: function() {
            return {
//              hogehoge: 'hogehoge ' + Vue.$cookies.get('depr_polls') + 'hogehoge'
                deprs: [ { name: '日吉駅東口' }, { name: '箕輪町' } ],
                dests: [ { name: '宮前西町' }, { name: '日大高校正門' } ],
                DeprPollName: document.getElementById( 'depr_poll_menu' ).value,
                DestPollName: document.getElementById( 'dest_poll_menu' ).value,
                IsHoliday: document.getElementById( 'isholiday' ).value
            }
        }
    }
 </script>