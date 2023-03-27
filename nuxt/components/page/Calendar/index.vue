<template>
    <div>
        <v-card>
            <PartsDatePager ttl="シフト" :path="path" />

            <ul class="indent pa-0">
                <li v-for="day in week" :key="day" class="indent_item">{{day}}</li>
            </ul>

            <ul class="content">
                <li v-for="n in first_day" :key="n" class="content_item blank"></li>
                <li @click="onClickCalendar(calendar)" v-for="(calendar, index) in calendars" :key="calendar.date" v-ripple class="content_item">
                    <div class="content_item_inner">
                        <div class="content_item_icn">
                            <div class="content_item_icn_num" :class="{main:index + 1 == nowDay && year == nowYear && month == nowMonth}">
                                {{ index + 1 }}
                            </div>
                        </div>
                        <div class="pa-1 content_member">
                            <div v-if="getWorksLoading" class="text-center pt2">
                                <v-progress-circular indeterminate color="main"></v-progress-circular>
                            </div>
                            <ul v-else>
                                <li v-for="work in calendar.works" :key="work.id">
                                    <v-chip :color="work.user_id == loginInfo.id ? 'sub' :''" small label>{{work.name}}</v-chip>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li v-for="n in lastDayCount" :key="n + 100" class="content_item blank"></li>
            </ul>

            <v-dialog max-width="476px" :value="focusCalendar" scrollable @click:outside="focusCalendar = ''">
                <PageCreateWork v-if="focusCalendar" :focusCalendar="focusCalendar" @closeCreateWorkDialog="closeCreateWorkDialog" @onCloseDialog="onCloseDialog" />
            </v-dialog>
            <v-divider></v-divider>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn @click="worksSetting = true">
                    固定シフト<v-icon>mdi-cog</v-icon>
                </v-btn>
                <v-dialog max-width="700px" :value="worksSetting" scrollable @click:outside="worksSetting = false">
                    <PartsWorksSetting v-if="worksSetting" @onCloseDialog="onCloseDialog" />
                </v-dialog>
            </v-card-actions>
        </v-card>
        <PageCalendarSalary :path="path" :works="works" class="mt-5" />
        <PageCalendarTaskBtn v-if="reMount" :getWorksLoading="getWorksLoading" class="my-5" />
    </div>
</template>

<script>
import moment from 'moment'
import { mapState } from 'vuex'
export default {
    props: ['path'],
    data() {
        return {
            week: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            works: [],
            focusCalendar: '',
            worksSetting: false,
            reMount: true,
            getWorksLoading: false,
        }
    },
    computed: {
        ...mapState(['loginInfo']),
        calendars() {
            let outputData = []

            for (let day = 1; day <= this.lastday; day++) {
                let date = moment(
                    new Date(this.year, this.month - 1, day)
                ).format('YYYY-MM-DD')
                let works = this.works.filter((work) => work.work_date == date)
                outputData.push({
                    date: date,
                    works: works,
                })
            }

            return outputData
        },
        year() {
            return this.$route.query.year
        },
        month() {
            return this.$route.query.month
        },
        day() {
            return this.$route.query.day
        },
        lastday() {
            return new Date(this.year, this.month, 0).getDate()
        },
        first_day() {
            return new Date(this.year, this.month - 1, 1).getDay()
        },
        lastDayCount() {
            return (
                6 - new Date(this.year, this.month - 1, this.lastday).getDay()
            )
        },
        nowDay() {
            return moment(new Date()).format('D')
        },
        nowYear() {
            return moment(new Date()).format('Y')
        },
        nowMonth() {
            return moment(new Date()).format('M')
        },
    },
    methods: {
        closeCreateWorkDialog() {
            this.focusCalendar = ''
            this.getWorks()
        },
        onCloseDialog(bool) {
            this.focusCalendar = ''
            this.worksSetting = false
            if (bool) {
                this.getWorks()
            }
        },
        async onClickCalendar(calendar) {
            if (
                (this.$root.layoutName == 'member' && !calendar.works.length) ||
                this.getWorksLoading
            ) {
                return
            }
            this.focusCalendar = calendar
        },
        async getWorks() {
            this.getWorksLoading = true
            await this.$axios
                .get(`/api/work/read?year=${this.year}&month=${this.month}`)
                .then((res) => {
                    this.works = res.data
                })
                .finally(() => {
                    this.getWorksLoading = false
                })
        },
    },
    mounted() {
        this.getWorks()
    },
    watch: {
        $route() {
            this.getWorks()
            this.reMount = false
            this.$nextTick(() => {
                this.reMount = true
            })
        },
    },
}
</script>

<style lang="scss" scoped>
@mixin mq-pc {
    @media screen and (min-width: 768px) {
        @content;
    }
}
.indent {
    display: flex;
    &_item {
        width: calc(100% / 7);
        text-align: center;
        padding: 5px 0;
        &:nth-child(1) {
            color: #ff5252;
        }
        &:nth-child(7) {
            color: #2196f3;
        }
    }
}

.content {
    display: flex;
    flex-wrap: wrap;
    padding: 0;
    background-color: white;
    &_item {
        width: calc(100% / 7);
        border-right: 1px solid #e0e0e0;
        border-top: 1px solid #e0e0e0;
        overflow: hidden;
        &_inner {
            li {
                margin-bottom: 3px;
                @include mq-pc {
                    margin-bottom: 4px;
                }
                .v-chip {
                    display: block;
                    width: 100%;
                    padding: 0 5px;
                    line-height: 16px;
                    height: 16px;
                    @include mq-pc {
                        font-size: 16px;
                        line-height: 20px;
                        height: 20px;
                    }
                }
            }
        }
        &:nth-child(7n) {
            border-right: none;
        }
        &:nth-child(7n) .content_item_icn {
            color: #2196f3;
        }
        &:nth-child(7n-6) .content_item_icn {
            color: #ff5252;
        }
        &.blank {
            background-color: #f7f7f7;
        }
        &.main:hover {
            cursor: pointer;
            background-color: #0000663d;
        }
        &_icn {
            padding-top: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
            &_num {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 20px;
                height: 20px;
                font-size: 14px;
                border-radius: 50%;
                &.main {
                    color: white;
                    font-size: 12px;
                }
            }
        }
    }
    &_member {
        min-height: 40px;
        @include mq-pc {
            min-height: 60px;
        }
    }
}
</style>