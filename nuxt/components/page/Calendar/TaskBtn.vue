<template>
    <div class="d-flex">
        <v-spacer></v-spacer>
        <v-btn color="sub" dark v-if="isShowType2">先月のシフトを確認しました</v-btn>
        <v-btn color="sub" dark v-if="isShowType3">今月のシフトを確認しました</v-btn>
        <v-btn color="sub" dark v-if="isShowType4">来月のシフトを確認しました</v-btn>
    </div>
</template>

<script>
import moment from 'moment'
import { mapState } from 'vuex'
export default {
    data() {
        return {}
    },
    methods: {
        getDate(year, month) {
            return {
                lastDate: moment(`${year}-${month}`)
                    .subtract(1, 'months')
                    .format('Y-M'),
                thisDate: moment(`${year}-${month}`).format('Y-M'),
                nextDate: moment(`${year}-${month}`)
                    .add(1, 'months')
                    .format('Y-M'),
            }
        },
    },
    computed: {
        ...mapState(['loginInfo']),
        year() {
            return this.$route.query.year
        },
        month() {
            return this.$route.query.month
        },
        // 先月のシフト確認
        isShowType2() {
            return this.loginInfo.tasks.find((task) => {
                return (
                    task.type == 2 &&
                    this.getDate(task.year, task.month).lastDate ==
                        this.getDate(this.year, this.month).thisDate
                )
            })
        },
        // 今月のシフト確認
        isShowType3() {
            return this.loginInfo.tasks.find((task) => {
                return (
                    task.type == 3 &&
                    this.getDate(task.year, task.month).thisDate ==
                        this.getDate(this.year, this.month).thisDate
                )
            })
        },
        // 来月のシフト確認
        isShowType4() {
            return this.loginInfo.tasks.find((task) => {
                return (
                    task.type == 4 &&
                    this.getDate(task.year, task.month).nextDate ==
                        this.getDate(this.year, this.month).thisDate
                )
            })
        },
    },
}
</script>
