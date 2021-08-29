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
             <tr v-for "item in items" :key="items.num">
                 <th scope="row">{{items.num}}</th>
                 <td>{{item[0]}}</td>
                 <td>{{item[1]}}</td>
                 <td>{{item[2]}}</td>
             </tr>
             </tbody>
         </table>
     </div>
</template>
 
<script>
//Vue.config.devtools = true;
    export default {
        props: {
            items: Array
        },            
        data: function() {
            return {
            }
        }
    }
 </script>