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
                        `/member/?year=${task.year}&month=${task.month}`
                    )
                    return
                case 5:
                    this.$router.push(`/member/report/?date=${task.date}`)
                    return
            }
        },
        getTaskName(task) {
            switch (task.type) {
                case 1:
                    return `${moment(`${task.year}-${task.month}`).format('Y年M月')}の自己チェック表の提出`
                case 2:
                    return `${moment(`${task.year}-${task.month}`).format('Y年M月')}のシフト&給与確認`
                case 5:
                    return `${moment(task.date).format('Y年M月D日')}の日報提出`
            }
        },
    },
}
</script>
<style lang="scss" scoped>
</style>