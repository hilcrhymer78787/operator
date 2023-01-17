<template>
    <div>
        <div class="d-flex">
            <v-spacer></v-spacer>
            <v-btn color="sub" @click="updateTask()" :disabled="getWorksLoading" :loading="updateTaskLoading" :dark="!getWorksLoading" v-if="isShowType">シフトと給与を確認しました</v-btn>
            <v-spacer></v-spacer>
        </div>

        <v-dialog max-width="476px" v-if="isShowType && $root.layoutName == 'member'" :value="taskDialog">
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
import { mapState } from 'vuex'
export default {
    props: ['getWorksLoading'],
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
        isShowType() {
            return this.loginInfo.tasks.find((task) => {
                return (
                    task.type == 2 &&
                    task.state == 1 &&
                    task.year == this.year &&
                    task.month == this.month
                )
            })
        },
    },
    methods: {
        async updateTask() {
            this.updateTaskLoading = true
            await this.$axios
                .put(
                    `/api/task/update?date=${this.year}-${this.month}&type=2&state=2`
                )
                .then((res) => {
                    this.$store.dispatch('setLoginInfoByToken')
                    alert(
                        `${this.year}年${this.month}月のシフトと給与を確認しました`
                    )
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
