<template>
     <div class="container">
        <table class="table table-hover">
            <thead class="thead-light">
            <tr>
                 <th scope="col">#</th>
                 <th scope="col">発車時刻</th>
                 <th scope="col">系統</th>
                 <th scope="col">行き先等備考 {{depr_poll}}</th>
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
            depr_poll: String,
            dest_poll: String,
            isholiday: String
        },            
        data: function() {
            return {
                url: ''
            }
        },
        watch: {
            depr_poll: function(value) {
                //alert( 'depr_poll ' + this.depr_poll );
                const self = this;
                self.getTables();
            },
            dest_poll: function(value) {
                //alert( 'dest_poll ' + this.dest_poll );
                const self = this;
                self.getTables();
            }
        },
        methods: {
            getTables: function() {
                const self = this;
                self.url = 'http://localhost:8000/' + this.depr_poll + '/' + this.dest_poll + '/3/' + self.isholiday;
                alert( 'hoge '+ self.url )
            }    
        }
    }
 </script>