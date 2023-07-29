<template>
    <v-card>
        <v-card-title color="main" dark>
            <span>{{focusCalendar.date}}</span>
            <v-spacer></v-spacer>
            <v-btn :to="`${$route.path}report?date=${focusCalendar.date}`" router class="mb-2">
                <v-icon>mdi-file-outline</v-icon>
                <span class="pt-1">日報</span>
            </v-btn>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text class="pa-3" style="min-height:35vh;">
            <v-form ref="form" v-model="noError">
                <v-card v-for="(work,workIndex) in works" :key="workIndex" class="d-flex align-center mb-4 pt-2" style="height:70px;overflow: hidden;">
                    <v-select v-if="$root.layoutName == 'admin'" @change="onUserSelected(work.user_id,workIndex)" class="pt-3 pl-3" style="width:46%;" label="出勤者" :items="users(work.user_id)" v-model="work.user_id" item-value="val" item-text="txt" :rules="[v => !!v || 'Item is required']" dense></v-select>
                    <v-text-field v-if="$root.layoutName == 'member'" v-model="work.name" label="出勤者" class="pt-3 pl-3" style="width:46%;" required readonly></v-text-field>
                    <v-spacer></v-spacer>
                    <v-text-field v-if="$root.layoutName == 'admin' || work.user_id == loginInfo.id" :readonly="$root.layoutName == 'member'" class="pt-3" style="width:30%;" label="給与" v-model="work.salary" item-value="val" item-text="txt" :rules="[v => !!v || 'Item is required']" dense></v-text-field>
                    <v-spacer></v-spacer>
                    <v-btn v-if="$root.layoutName == 'admin'" @click="removeWork(workIndex)" class="close_wrap" v-ripple style="width:12%;" icon>
                        <v-icon color="white">mdi-close</v-icon>
                    </v-btn>
                </v-card>
                <v-btn v-if="$root.layoutName == 'admin'" @click="addWork()" icon class="d-block mx-auto">
                    <v-icon style="font-size:35px;">mdi-plus</v-icon>
                </v-btn>
                <v-checkbox v-if="$root.layoutName == 'admin'" v-model="shiftCheck" label="シフト変更対象者に確認を依頼" color="main"></v-checkbox>
            </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
            <v-btn v-if="$root.layoutName == 'admin'" color="error" :loading="deleteLoading" @click="onClickDelete()">削除</v-btn>
            <v-spacer></v-spacer>
            <v-btn @click="$emit('onCloseDialog')">
                <v-icon>mdi-close</v-icon>
            </v-btn>
            <v-btn v-if="$root.layoutName == 'admin'" color="main" :loading="saveLoading" dark @click="onClickSave()">登録</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import { mapState } from 'vuex'
export default {
    props: ['focusCalendar'],
    data() {
        return {
            shiftCheck: false,
            deleteLoading: false,
            saveLoading: false,
            noError: false,
            works: [],
        }
    },
    computed: {
        ...mapState(['loginInfo']),
    },
    methods: {
        users(userId) {
            let outputData = []
            this.loginInfo.admin.users.forEach((user) => {
                const userDuplicateJudge =
                    this.works.filter((work) => work.user_id == user.id)
                        .length == 0
                if (userDuplicateJudge || userId == user.id) {
                    outputData.push({ val: user.id, txt: user.name })
                }
            })
            return outputData
        },
        onUserSelected(userId, workIndex) {
            this.works[workIndex].salary = this.loginInfo.admin.users.filter(
                (user) => user.id == userId
            )[0].salary
        },
        addWork() {
            this.$refs.form.validate()
            if (!this.noError) {
                return
            }
            this.works.push({
                user_id: 0,
                salary: 0,
            })
        },
        removeWork(workIndex) {
            if (this.works.length == 1) {
                return
            }
            this.works.splice(workIndex, 1)
        },
        async onClickSave() {
            this.$refs.form.validate()
            if (!this.noError) {
                return
            }
            this.saveLoading = true
            await this.$axios
                .post(`/api/work/create?date=${this.focusCalendar.date}`, {
                    works: this.works,
                    shiftCheck: this.shiftCheck,
                })
                .then((res) => {
                    console.log(res.data)
                    if (res.data.length) {
                        let text = ''
                        res.data.forEach((userId, index) => {
                            text =
                                text +
                                `「${
                                    this.loginInfo.users.find(
                                        (user) => user.id == userId
                                    )?.name ?? '不明'
                                }」`
                        })
                        text = text + 'にシフト確認のタスクを発行しました'
                        alert(text)
                    }
                    this.$emit('closeCreateWorkDialog')
                })
                .catch((err) => {
                    alert('通信に失敗しました')
                })
                .finally(() => {
                    this.saveLoading = false
                })
        },
        async onClickDelete() {
            if (
                !confirm(
                    `「${this.focusCalendar.date}」の情報を全て削除しますか？`
                )
            ) {
                return
            }
            this.deleteLoading = true
            await this.$axios
                .delete(`/api/work/delete?date=${this.focusCalendar.date}`)
                .then((res) => {
                    this.$emit('closeCreateWorkDialog')
                })
                .finally(() => {
                    this.deleteLoading = false
                })
        },
    },
    mounted() {
        this.focusCalendar.works.forEach((work) => {
            let obj = {}
            this.$set(obj, 'name', work.name)
            this.$set(obj, 'user_id', work.user_id)
            this.$set(obj, 'salary', work.salary)
            this.works.push(obj)
        })
        if (!this.focusCalendar.works.length) {
            let obj = {}
            this.$set(obj, 'user_id', 0)
            this.$set(obj, 'salary', 0)
            this.works.push(obj)
        }
    },
}
</script>
<style lang="scss" scoped>
.close_wrap {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: rgba(0, 0, 0, 0.6);
    height: 100%;
    border-radius: 0 !important;
}
</style>