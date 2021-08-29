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
                        <td>
                            <input type="checkbox" ref="isholiday" @change="changeIsHoliday" value="0">祝日ダイヤ</input>
                        </td>
                        <td style="width:120px">
                            <button class="btn btn-primary btn-sm" @click="searchTimetable">更新</button>
                        </td>
                    </tr>
                </tbody>
                </table>
            </div>
            <component :is="timetable_component" :items="items"></component>
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
                isholiday: "0",
                url: '',
                timetable_component : 'timetable-wait-component'
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
            },
            changeIsHoliday: function() {
                const self = this;
                ( this.$refs.isholiday.checked ) ? self.isholiday = "1" : self.isholiday = "0";
            },
            searchTimeTable: function() {
                const self = this;
                if ( self.url == '' ) {
                    timetable_component = 'timetable-wait-component';
                }
                else {
                    this.axios.get(url).then((response) => {
                            alert(response.data.origin);
                        })
                        .catch((e) => {
                            alert(e);
                        });
                }
            }
        
        },
        mounted: function() {
                const self = this;
                self.depr_poll = this.$refs.depr_poll_menu.value;
                self.dest_poll = this.$refs.dest_poll_menu.value;
                self.isholiday = this.$refs.isholiday.value;
                self.url = 'http://localhost/' + self.depr_poll + '/' + self.dest_poll + '/3/' + self.isholiday;
                alert( 'fugafuga '+url );
        }

    }
 </script>