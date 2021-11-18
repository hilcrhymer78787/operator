<template>
    <div>
        <v-card>
            <v-card-title>タスク</v-card-title>
            <v-card-text class="pa-0">
                <ul>
                    <li v-for="task in loginInfo.tasks" :key="task.id">
                        <v-list-item v-ripple @click="onClickTask(task)">
                            <v-list-item-content>
                                <v-list-item-title v-html="getTaskName(task)"></v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider></v-divider>
                    </li>
                </ul>
                <div v-if="!loginInfo.tasks.length" class="pa-5 text-center">現在登録されているタスクはありません</div>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
import { mapState } from 'vuex'
import moment from 'moment'
export default {
    layout: 'member',
    data() {
        return {}
    },
    computed: {
        ...mapState(['loginInfo']),
    },
    methods: {
        onClickTask(task) {
            switch (task.type) {
                case 1:
                    this.$router.push(
                        `/member/question/?year=${task.year}&month=${task.month}&user_id=${this.loginInfo.id}&user_name=${this.loginInfo.name}`
                    )
                    return
                case 2:
                    this.$router.push(
                        `/member/?year=${
                            this.getDate(task.year, task.month).lastYear
                        }&month=${
                            this.getDate(task.year, task.month).lastMonth
                        }`
                    )
                    return
                case 3:
                    this.$router.push(
                        `/member/?year=${task.year}&month=${task.month}`
                    )
                    return
                case 4:
                    this.$router.push(
                        `/member/?year=${
                            this.getDate(task.year, task.month).nextYear
                        }&month=${
                            this.getDate(task.year, task.month).nextMonth
                        }`
                    )
                    return
            }
        },
        //  getDate(2021, 12)
        getTaskName(task) {
            let beforeMonth = moment(`${task.year}-${task.month}`)
                .subtract(1, 'months')
                .format('Y年M月')
            let nowMonth = moment(`${task.year}-${task.month}`).format('Y年M月')
            let afterMonth = moment(`${task.year}-${task.month}`)
                .add(1, 'months')
                .format('Y年M月')
            switch (task.type) {
                case 1:
                    return `今月（${nowMonth}）の自己チェック表の提出`
                case 2:
                    return `先月（${beforeMonth}）のシフト&給与確認`
                case 3:
                    return `今月（${nowMonth}）のシフト&給与確認`
                case 4:
                    return `来月（${afterMonth}）のシフト&給与確認`
            }
        },
        getDate(year, month) {
            var date = `${year}-${month}`
            return {
                lastDate: moment(date).subtract(1, 'months').format('Y-M'),
                lastYear: moment(date).subtract(1, 'months').format('Y'),
                lastMonth: moment(date).subtract(1, 'months').format('M'),
                thisDate: moment(date).format('Y-M'),
                nextDate: moment(date).add(1, 'months').format('Y-M'),
                nextYear: moment(date).add(1, 'months').format('Y'),
                nextMonth: moment(date).add(1, 'months').format('M'),
            }
        },
    },
}
</script>
<style lang="scss" scoped>
</style>