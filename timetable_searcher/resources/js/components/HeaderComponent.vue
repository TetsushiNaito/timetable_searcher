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
                                <select ref="depr_poll_menu" @change="changeDeprPoll">
                                    <option v-for="depr in deprs" :key="depr.name" :value="depr.name">{{depr.name}}</option>
                                </select>
                            </label>
                        </td>
                        <td>
                            <label><b>行き先：</b>
                                <select ref="dest_poll_menu" @change="changeDestPoll">
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
            <router-view :depr_poll='depr_poll' :dest_poll='dest_poll' :isholiday='isholiday'></router-view>
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
                depr_poll: '',
                dest_poll: '',
                isholiday: 0             
            }
        },
        methods: {
            changeDeprPoll: function() {
                const self = this;
                self.depr_poll = this.$refs.depr_poll_menu.value;
            },
            changeDestPoll: function() {
                const self = this;
                self.dest_poll = this.$refs.dest_poll_menu.value;
            }
        },
        mounted: function() {
                const self = this;
                self.depr_poll = this.$refs.depr_poll_menu.value;
                self.dest_poll = this.$refs.dest_poll_menu.value;
                self.isholiday = this.$refs.isholiday.value;
        }
    }
 </script>