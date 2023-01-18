<template>
    <v-card class="mx-auto">
        <v-card-title>
            <span v-if="mode == 'create'">新規問題登録</span>
            <span v-if="mode == 'edit'">問題編集</span>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
            <v-form v-model="noError" ref="form" class="pt-5">
                <v-textarea dense validate-on-blur :rules="titleRules" required label="問題" placeholder="問題" outlined v-model="form.title" color="main"></v-textarea>
                <v-textarea dense validate-on-blur required label="回答" placeholder="回答" outlined v-model="form.content" color="main"></v-textarea>
            </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
            <v-btn v-if="mode=='edit'" :loading="deleteQuizLoading" color="error" dark @click="deleteQuiz()">削除</v-btn>
            <v-spacer></v-spacer>
            <v-btn @click="$emit('onCloseDialog')">
                <v-icon>mdi-close</v-icon>
            </v-btn>
            <v-btn :loading="loading" color="main" dark @click="submit()">登録</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import { mapState } from 'vuex'
export default {
    props: ['mode', 'focusQuiz'],
    data() {
        return {
            deleteQuizLoading: false,
            loading: false,
            noError: false,
            form: {
                id: 0,
                title: '',
                content: '',
            },
            titleRules: [(v) => !!v || '問題は必須です'],
        }
    },
    computed: {
        ...mapState(['loginInfo']),
    },
    methods: {
        async submit() {
            this.$refs.form.validate()
            // バリデーションエラー
            if (!this.noError) {
                return
            }
            this.loading = true
            const requestConfig = {
                url: `/api/quiz/create`,
                method: 'POST',
                data: {
                    id: this.form.id,
                    title: this.form.title,
                    content: this.form.content,
                },
            }
            await this.$axios(requestConfig)
                .then(async (res) => {
                    await this.$store.dispatch('getQuiz')
                    this.$emit('onCloseDialog')
                })
                .catch((err) => {
                    alert('通信に失敗しました')
                })
                .finally(() => {
                    this.loading = false
                })
        },
        async deleteQuiz() {
            if (!confirm(`「${this.focusQuiz.title}」を削除しますか？`)) {
                return
            }
            // 問題削除API
            this.deleteQuizLoading = true
            await this.$axios
                .delete(`/api/quiz/delete?id=${this.focusQuiz.id}`)
                .then(async (res) => {
                    await this.$store.dispatch('getQuiz')
                    this.$emit('onCloseDialog')
                })
                .catch((err) => {
                    alert('通信に失敗しました')
                })
            this.deleteQuizLoading = false
        },
    },
    mounted() {
        if (this.mode == 'edit') {
            this.$set(this.form, 'id', this.focusQuiz.id)
            this.$set(this.form, 'title', this.focusQuiz.title)
            this.$set(this.form, 'content', this.focusQuiz.content)
        }
    },
}
</script>
<style lang="scss" scoped>
.error_message {
    color: #ff5252;
    font-size: 12px;
    margin-top: -27px;
    margin-left: 12px;
}
</style>