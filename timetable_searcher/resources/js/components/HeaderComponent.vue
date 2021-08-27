import { eventbus } from './../app.js';

<template>
     <div class="container-fluid bg-light mb-3">
         <div class="container">
             <nav class="navbar navbar-light">
                 <span class="navbar-brand mb-0 h1">Timetable Searcher</span>
             </nav>
             <div>
                <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td>
                            <label><b>出発：</b>
                                <select ref="depr_poll_menu">
                                    <option v-for="depr in deprs" :key="depr.name" :value="depr.name">{{depr.name}}</option>
                                </select>
                            </label>
                        </td>
                        <td>
                            <label><b>行き先：</b>
                                <select ref="dest_poll_menu">
                                    <option v-for="dest in dests" :key="dest.name" :value="dest.name">{{dest.name}}</option>
                                </select>
                            </label>
                        </td>
                        <td style="width:100px">
                        <router-link v-bind:to="{name: 'timetable.list'}">
                            <button class="btn btn-primary btn-sm">更新</button>
                        </router-link>
                        </td>
                        <td>
                            <input type="checkbox" ref="isholiday" value="1">今日は祝日ダイヤ</input>
                        </td>
                    </tr>
                </tbody>
                </table>
            </div>
            <router-view :params='params'></router-view>
         </div>
     </div>
 </template>
 
 <script>
    export default {
        data: function() {
            return {
//              hogehoge: 'hogehoge ' + Vue.$cookies.get('depr_polls') + 'hogehoge'
                deprs: [ { name: '日吉駅東口' }, { name: '箕輪町' } ],
                dests: [ { name: '宮前西町' }, { name: '日大高校正門' } ],
                params: []    
            }
        },
        mounted: function () {
            const self = this;
            self.params = [
                this.$refs.depr_poll_menu.value,
                this.$refs.dest_poll_menu.value,
                this.$refs.isholiday.value
            ];    
        }
    }
 </script>