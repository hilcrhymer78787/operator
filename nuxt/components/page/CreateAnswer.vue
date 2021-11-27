<template>
    <v-card>
        <v-toolbar color="main" dark flat>
            <div class="ttl">確認表（ {{ userName }} ）</div>
            <div class="d-flex">
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
            </div>
        </v-toolbar>
        <v-card-text class="pa-0">
            <div v-if="getAnswerLoading && !questions.length" class="py-5 my-5 text-center">
                <v-progress-circular indeterminate color="main"></v-progress-circular>
            </div>
            <v-form v-model="noError" ref="form">
                <ul>
                    <li v-for="(question,index) in questions" :key="question.id">
                        <dl class="d-flex align-center pt-3 pr-2 pl-0 pb-2">
                            <dt style="width:40px;" class="text-center">{{ index + 1 }}</dt>
                            <dd style="width:calc(100% - 40px);" class="pl-1">{{ question.content }}</dd>
                        </dl>
                        <v-radio-group @change="$store.commit('setRootRock', true)" :readonly="!(isShowType1 || $root.layoutName == 'admin')" v-if="question.answer && !getAnswerLoading" v-model="question.answer.content" :rules="contentRules" row hide-details class="ma-0 px-2 pb-3">
                            <v-radio color="main" v-for="n in 5" :key="n" :label="`${n}`" :value="n" class="mr-4"></v-radio>
                        </v-radio-group>
                        <div v-if="getAnswerLoading" class="pb-2 text-center">
                            <v-progress-circular indeterminate color="main"></v-progress-circular>
                        </div>
                        <v-divider></v-divider>
                    </li>
                </ul>
            </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
            <v-btn v-if="$root.layoutName == 'admin'" :loading="deleteAnswerLoading" dark color="error" @click="deleteAnswer()">削除</v-btn>
            <v-spacer></v-spacer>
            <v-btn v-if="$root.layoutName == 'admin'" @click="$router.push(`/admin/user`)"><v-icon>mdi-close</v-icon></v-btn>
            <v-btn v-if="isShowType1 || $root.layoutName == 'admin'" :loading="postAnswerLoading" dark color="main" @click="postAnswer()">登録</v-btn>
            <v-btn v-if="isShowUpdateTask && $root.layoutName == 'member'" :loading="updateTaskLoading" dark color="error" @click="updateTask()">変更する</v-btn>
        </v-card-actions>

        <v-dialog v-if="$root.layoutName == 'member'" :value="isShowType1 && taskDialog">
            <v-card>
                <v-card-title class="text-h7">タスクがあります</v-card-title>
                <v-card-text class="pa-5">
                    <span style="font-size:16px;">
                        {{this.year}}年{{this.month}}月の自己チェック表を提出してください
                    </span>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text @click="taskDialog = false">OK</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-card>
</template>

<script>
import moment from 'moment'
import { mapState } from 'vuex'
export default {
    props: ['path', 'mode'],
    data() {
        return {
            getAnswerLoading: false,
            postAnswerLoading: false,
            deleteAnswerLoading: false,
            updateTaskLoading: false,
            taskDialog: true,
            questionsData: [],
            noError: false,
            contentRules: [(v) => !!v || 'メールアドレスは必須です'],
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
        userId() {
            return this.$route.query.user_id
        },
        userName() {
            return this.$route.query.user_name
        },
        questions() {
            let returnData = []
            this.questionsData.forEach((question) => {
                if (!question.answer) {
                    question.answer = { content: null }
                }
                returnData.push(question)
            })
            return returnData
        },
        nowYear() {
            return moment(new Date()).format('Y')
        },
        nowMonth() {
            return moment(new Date()).format('M')
        },
        isShowType1() {
            return this.loginInfo.tasks.find((task) => {
                return (
                    task.type == 1 &&
                    task.state == 1 &&
                    task.year == this.year &&
                    task.month == this.month
                )
            })
        },
        isShowUpdateTask() {
            return (
                this.year == this.nowYear &&
                this.month == this.nowMonth &&
                !this.isShowType1 &&
                this.questions[0] &&
                this.questions[0].answer.content
            )
        },
    },
    methods: {
        getAnswer() {
            this.getAnswerLoading = true
            this.$axios
                .get(
                    `/api/answer/read?year=${this.year}&month=${this.month}&user_id=${this.userId}&user_name=${this.userName}`
                )
                .then((res) => {
                    this.questionsData = res.data
                })
                .finally(() => {
                    this.getAnswerLoading = false
                })
        },
        async postAnswer() {
            this.$refs.form.validate()
            if (!this.noError) {
                var noContents = []
                this.questions.forEach((question, index) => {
                    if (!question.answer.content) {
                        noContents.push(index + 1)
                    }
                })
                alert('項目' + noContents + 'が空白です')
                return
            }
            this.postAnswerLoading = true
            await this.$axios
                .post(
                    `/api/answer/create?year=${this.year}&month=${this.month}&user_id=${this.userId}&user_name=${this.userName}`,
                    { questions: this.questions }
                )
                .then((res) => {
                    this.$store.commit('setRootRock', false)
                    if (this.$root.layoutName == 'admin') {
                        this.$router.push(this.path)
                    }
                    if (this.$root.layoutName == 'member') {
                        this.getAnswer()
                        alert('自己チェック表を提出しました')
                        this.$store.dispatch('setLoginInfoByToken')
                    }
                })
                .catch((err) => {
                    console.log(err)
                })
                .finally(() => {
                    this.postAnswerLoading = false
                })
        },
        async deleteAnswer() {
            if (
                !confirm(
                    `${this.year}年${this.month}月${this.userName}のデータを削除しますか？`
                )
            ) {
                return
            }
            if (!confirm('本当によろしいですか？')) {
                return
            }
            this.deleteAnswerLoading = true
            await this.$axios
                .delete(
                    `/api/answer/delete?year=${this.year}&month=${this.month}&user_id=${this.userId}`
                )
                .then((res) => {
                    this.$store.commit('setRootRock', false)
                    if (this.$root.layoutName == 'admin') {
                        this.$router.push(this.path)
                    }
                })
                .catch((err) => {
                    console.log(err)
                })
                .finally(() => {
                    this.deleteAnswerLoading = false
                })
        },
        updateTask() {
            if (!confirm('変更しますか？')) {
                return
            }
            this.updateTaskLoading = true
            this.$axios
                .put(
                    `/api/task/update?date=${this.year}-${this.month}&type=1&state=1`
                )
                .then((res) => {
                    this.$store.dispatch('setLoginInfoByToken')
                })
                .catch((err) => {
                    console.log(err)
                })
                .finally(() => {
                    this.updateTaskLoading = false
                })
        },
        onClickPrevMonth() {
            if (this.month == 1) {
                this.$router.push(
                    `${this.path}/?year=${
                        Number(this.year) - 1
                    }&month=12&user_id=${this.userId}&user_name=${
                        this.userName
                    }`
                )
            } else {
                this.$router.push(
                    `${this.path}/?year=${this.year}&month=${
                        Number(this.month) - 1
                    }&user_id=${this.userId}&user_name=${this.userName}`
                )
            }
        },
        onClickNextMonth() {
            if (this.month == 12) {
                this.$router.push(
                    `${this.path}/?year=${
                        Number(this.year) + 1
                    }&month=1&user_id=${this.userId}&user_name=${this.userName}`
                )
            } else {
                this.$router.push(
                    `${this.path}/?year=${this.year}&month=${
                        Number(this.month) + 1
                    }&user_id=${this.userId}&user_name=${this.userName}`
                )
            }
        },
    },
    mounted() {
        this.getAnswer()
    },
    watch: {
        $route() {
            this.getAnswer()
        },
    },
}
</script>

<style lang="scss" scoped>
::v-deep {
    .v-label {
        left: -5px !important;
    }
}
h1 {
    width: 183px;
    text-align: center;
    font-weight: normal;
}
.v-toolbar {
    height: auto !important;
    ::v-deep {
        .v-toolbar__content {
            display: block;
            height: auto !important;
            .ttl {
                position: relative;
                top: 5px;
            }
        }
    }
}
</style>