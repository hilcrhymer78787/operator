<template>
    <v-card class="mx-auto">
        <v-card-title>
            <span v-if="mode == 'create'">新規マニュアル登録</span>
            <span v-if="mode == 'edit'">マニュアル編集</span>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
            <v-form v-model="noError" ref="form" class="pt-5">
                <v-textarea dense validate-on-blur :rules="contentRules" required label="内容" placeholder="内容" outlined v-model="form.content" color="main"></v-textarea>
                <v-textarea dense validate-on-blur required label="詳細" placeholder="詳細" outlined v-model="form.reason" color="main"></v-textarea>
                <v-checkbox v-if="$root.layoutName == 'admin'" v-model="form.important" label="日報で毎日確認" color="main"></v-checkbox>
            </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
            <v-btn v-if="mode=='edit'" :loading="deleteQuestionLoading" color="error" dark @click="deleteQuestion()">削除</v-btn>
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
    props: ['mode', 'focusQuestion'],
    data() {
        return {
            deleteQuestionLoading: false,
            loading: false,
            noError: false,
            form: {
                id: 0,
                content: '',
                reason: '',
                important: false,
            },
            contentRules: [(v) => !!v || '内容は必須です'],
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
                url: `/api/question/create`,
                method: 'POST',
                data: {
                    id: this.form.id,
                    content: this.form.content,
                    reason: this.form.reason,
                    important: this.form.important,
                },
            }
            await this.$axios(requestConfig)
                .then(async (res) => {
                    await this.$store.dispatch('getQuestion')
                    this.$emit('onCloseDialog')
                })
                .catch((err) => {
                    alert('通信に失敗しました')
                })
                .finally(() => {
                    this.loading = false
                })
        },
        async deleteQuestion() {
            if (!confirm(`「${this.focusQuestion.content}」を削除しますか？`)) {
                return
            }
            // マニュアル削除API
            this.deleteQuestionLoading = true
            await this.$axios
                .delete(`/api/question/delete?id=${this.focusQuestion.id}`)
                .then(async (res) => {
                    await this.$store.dispatch('getQuestion')
                    this.$emit('onCloseDialog')
                })
                .catch((err) => {
                    alert('通信に失敗しました')
                })
            this.deleteQuestionLoading = false
        },
    },
    mounted() {
        if (this.mode == 'edit') {
            Object.assign(this.form, {
                id: this.focusQuestion.id,
                content: this.focusQuestion.content,
                reason: this.focusQuestion.reason,
                important: this.focusQuestion.important,
            })
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