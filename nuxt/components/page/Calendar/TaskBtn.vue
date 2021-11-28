<template>
    <div>
        <div class="d-flex">
            <v-spacer></v-spacer>
            <v-btn color="sub" @click="updateTask(2,2)" :disabled="getWorksLoading" :loading="updateTaskLoading" :dark="!getWorksLoading" v-if="isShowType2">シフトと給与を確認しました</v-btn>
            <v-btn color="sub" @click="updateTask(3,2)" :disabled="getWorksLoading" :loading="updateTaskLoading" :dark="!getWorksLoading" v-else-if="isShowType3">シフトと給与を確認しました</v-btn>
            <v-btn color="sub" @click="updateTask(4,2)" :disabled="getWorksLoading" :loading="updateTaskLoading" :dark="!getWorksLoading" v-else-if="isShowType4">シフトと給与を確認しました</v-btn>
            <v-spacer></v-spacer>
        </div>

        <v-dialog v-if="(isShowType2 || isShowType3 || isShowType4) && $root.layoutName == 'member'" :value="taskDialog">
            <v-card>
                <v-card-title class="text-h7">タスクがあります</v-card-title>
                <v-card-text class="pa-5">
                    <span style="font-size:16px;">
                        {{this.year}}年{{this.month}}月のシフトと給与を確認し、ページ下のボタンを押してください
                    </span>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text @click="taskDialog = false">
                        OK
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import moment from 'moment'
import { mapState } from 'vuex'
export default {
    props:['getWorksLoading'],
    data() {
        return {
            taskDialog: true,
            updateTaskLoading: false,
        }
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
                    task.state == 1 &&
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
                    task.state == 1 &&
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
                    task.state == 1 &&
                    this.getDate(task.year, task.month).nextDate ==
                        this.getDate(this.year, this.month).thisDate
                )
            })
        },
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
        async updateTask(type, state) {
            this.updateTaskLoading = true
            let apiParam = ''
            switch (type) {
                case 2:
                    apiParam = `/api/task/update?date=${
                        this.getDate(this.year, this.month).nextDate
                    }&type=${type}&state=${state}`
                    break
                case 3:
                    apiParam = `/api/task/update?date=${
                        this.getDate(this.year, this.month).thisDate
                    }&type=${type}&state=${state}`
                    break
                case 4:
                    apiParam = `/api/task/update?date=${
                        this.getDate(this.year, this.month).lastDate
                    }&type=${type}&state=${state}`
                    break
            }
            await this.$axios
                .put(apiParam)
                .then((res) => {
                    this.$store.dispatch('setLoginInfoByToken')
                    alert(`${this.year}年${this.month}月のシフトと給与を確認しました`)
                })
                .catch(() => {
                    alert('エラー')
                })
                .finally(() => {
                    this.updateTaskLoading = false
                })
        },
    },
}
</script>
