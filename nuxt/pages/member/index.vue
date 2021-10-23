<template>
    <div class="calendar">

        <!-- カレンダー -->
        <v-card>
            <v-toolbar color="main" dark style="box-shadow:none;">
                <v-spacer></v-spacer>
                <div class="d-flex">
                    <v-btn @click="onClickPrevMonth()" icon>
                        <v-icon style="font-size:30px;">mdi-chevron-left</v-icon>
                    </v-btn>
                    <h1>{{ year }}年 {{ month }}月</h1>
                    <v-btn @click="onClickNextMonth()" icon>
                        <v-icon style="font-size:30px;">mdi-chevron-right</v-icon>
                    </v-btn>
                </div>
                <v-spacer></v-spacer>
            </v-toolbar>

            <ul class="indent pa-0">
                <li v-for="day in week" :key="day" class="indent_item">{{day}}</li>
            </ul>

            <ul class="content">
                <li v-for="n in first_day" :key="n" class="content_item blank"></li>
                <li @click="onClickCalendar(calendar.date)" v-for="(calendar, index) in calendars" :key="calendar.date" v-ripple class="content_item">
                    <div class="content_item_inner">
                        <div class="content_item_icn">
                            <div class="content_item_icn_num" :class="{main:index + 1 == nowDay && year == nowYear && month == nowMonth}">
                                {{ index + 1 }}
                            </div>
                        </div>
                        <v-responsive class="pa-1" aspect-ratio="1"></v-responsive>
                    </div>
                </li>
                <li v-for="n in lastDayCount" :key="n + 100" class="content_item blank"></li>
            </ul>
        </v-card>
    </div>
</template>
<script>
import moment from 'moment'
import { mapState } from 'vuex'
export default {
    layout: 'member',
    middleware({ redirect, route }) {
        let year = new Date().getFullYear()
        let month = new Date().getMonth() + 1
        if (!route.query.year || !route.query.month) {
            redirect(`/member/?year=${year}&month=${month}`)
        }
    },
    data() {
        return {
            week: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            dialog: false,
            dialogLoading: false,
            date: '',
            tasks: [],
        }
    },
    computed: {
        ...mapState(['loginInfo', 'works']),
        calendars() {
            let outputData = []

            for (let day = 1; day <= this.lastday; day++) {
                outputData.push({
                    date: moment(
                        new Date(this.year, this.month - 1, day)
                    ).format('YYYY-MM-DD'),
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
        onClickPrevMonth() {
            if (this.month == 1) {
                this.$router.push(`/?year=${Number(this.year) - 1}&month=12`)
            } else {
                this.$router.push(
                    `/?year=${this.year}&month=${Number(this.month) - 1}`
                )
            }
        },
        onClickNextMonth() {
            if (this.month == 12) {
                this.$router.push(`/?year=${Number(this.year) + 1}&month=1`)
            } else {
                this.$router.push(
                    `/?year=${this.year}&month=${Number(this.month) + 1}`
                )
            }
        },
        async onClickCalendar(date) {
            this.dialog = true
            this.dialogLoading = true
            this.date = date
            await this.getTasks()
            this.dialogLoading = false
        },
        async getTasks() {
            const day = moment(this.date).format('D')
            await this.$axios
                .get(
                    `/api/task/read?year=${this.year}&month=${this.month}&day=${day}&token=${this.loginInfo.token}`
                )
                .then((res) => {
                    this.tasks = res.data
                    this.tasks.forEach((task) => {
                        let minute = task.works.reduce(function (sum, work) {
                            return sum + work.work_minute
                        }, 0)
                        this.$set(task, 'minute', minute)
                    })
                })
                .finally(() => {})
        },
    },
    mounted() {},
    watch: {
        $route() {
            // this.getWorks();
        },
    },
}
</script>

<style lang="scss" scoped>
h1 {
    width: 183px;
    text-align: center;
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
        &_inner {
        }
        width: calc(100% / 7);
        border-right: 1px solid #e0e0e0;
        border-top: 1px solid #e0e0e0;
        overflow: hidden;
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
            background-color: #00968734;
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
}
</style>