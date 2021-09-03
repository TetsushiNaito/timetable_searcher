<template>
    <div>
        <div>
        <digital-clock></digital-clock>
        </div>
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
                        <button class="btn btn-primary btn-sm" onclick="document.location='http://localhost/submit';">登録</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" ref="isholiday" @change="changeIsHoliday" value="0">祝日ダイヤ</input>
                    </td>
                    <td>
                        <button class="btn btn-primary btn-lg" @click="searchTimetable">更新</button>
                    </td>
                </tr>
            </tbody>
            </table>
        </div>
        <component :is="timetable_component" :items="items"></component>
    </div>
 </template>
 
 <script>
    export default {
        data: function() {
            return {
                /* hogehoge: 'hogehoge ' + Vue.$cookies.get('depr_polls') + 'hogehoge' */
                /* deprs: [ { name: '日吉駅東口' }, { name: '箕輪町' } , { name: '大倉山駅前' }], */
                /* dests: [ { name: '宮前西町' }, { name: '日大高校正門' }, { name: '港北区総合庁舎前' } ], */
                deprs: this.$cookies.get('depr_polls').split(':').map( function(value) { return JSON.parse( '{"name":"' + value + '"}' ) } ),
                dests: this.$cookies.get('dest_polls').split(':').map( function(value) { return JSON.parse( '{"name":"' + value + '"}' ) } ),
                depr_poll: '',
                dest_poll: '',
                isholiday: "0",
                items: [],
                url: '',
                timetable_component : 'timetable-init-component'
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
            searchTimetable: function() {
                const self = this;
                self.url = 'http://localhost/' + self.depr_poll + '/' + self.dest_poll + '/3/' + self.isholiday;
                self.timetable_component = 'timetable-wait-component';
                alert ('url: '+self.url)
                this.axios.get(self.url).then((response) => {
                    self.items = response.data;
                    alert( self.items );
                    if ( self.items == -1 ) {
                        self.timetable_component = 'timetable-sorry-component';
                    } else if (self.items == -11 ) {
                        self.timetable_component = 'timetable-error-component';
                    } else {
                        self.timetable_component = 'timetable-list-component';
                    }
                })
                .catch((e) => {
                    alert(e);
                });
            }
        },
        mounted: function() {
                const self = this;
                self.depr_poll = this.$refs.depr_poll_menu.value;
                self.dest_poll = this.$refs.dest_poll_menu.value;
                self.isholiday = this.$refs.isholiday.value;
                self.url = 'http://localhost/' + self.depr_poll + '/' + self.dest_poll + '/3/' + self.isholiday;
        }

    }
 </script>