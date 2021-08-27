<template>
     <div class="container">
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
//Vue.config.devtools = true;
    export default {
        props: {
            params: Array
        },            
        data: function() {
            return {
                url: '',
                hoge: this.params 
            }
        },
        methods: {
            getTables() {
                const self = this;
                self.url = self.buildURL();
                alert( 'hoge ' + self.url );
            },
            buildURL() {
                const self = this;
                return 'http://localhost:8000/' + self.params[0] + '/' + self.params[1] + '/3/' + self.params[2];
            }
        },
        mounted: function() {
            const self = this;
            self.getTables();
        }
    }
 </script>