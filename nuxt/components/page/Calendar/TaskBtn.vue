<template>
    <div>
        <div class="d-flex">
            <v-spacer></v-spacer>
            <v-btn color="sub" @click="updateTask(2,2)" :loading="updateTaskLoading" dark v-if="isShowType2">先月のシフトと給与を確認しました</v-btn>
            <v-btn color="sub" @click="updateTask(3,2)" :loading="updateTaskLoading" dark v-else-if="isShowType3">今月のシフトと給与を確認しました</v-btn>
            <v-btn color="sub" @click="updateTask(4,2)" :loading="updateTaskLoading" dark v-else-if="isShowType4">来月のシフトと給与を確認しました</v-btn>
        </div>
    </div>
</template>

<script>
import moment from 'moment'
import { mapState } from 'vuex'
export default {
    data() {
        return {
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
