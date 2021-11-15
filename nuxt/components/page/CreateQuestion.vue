<template>
    <v-card class="mx-auto">
        <v-card-title>
            <span v-if="mode == 'create'">新規マニュアル登録</span>
            <span v-if="mode == 'edit'">マニュアル編集</span>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
            <v-form v-model="noError" ref="form" class="pt-5">
                <v-textarea dense validate-on-blur @keyup.enter="submit()" :rules="contentRules" required label="内容" placeholder="内容" outlined v-model="form.content" color="main"></v-textarea>
            </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
            <v-btn v-if="mode=='edit'" :loading="deleteQuestionLoading" color="error" dark @click="deleteQuestion()">削除</v-btn>
            <v-spacer></v-spacer>
            <v-btn @click="$emit('onCloseDialog')">CLOSE</v-btn>
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
            await this.$axios
                .post(
                    `/api/question/create?id=${this.form.id}&content=${this.form.content}`
                )
                .then(async (res) => {
                    await this.$store.dispatch('getQuestion')
                    this.$emit('onCloseDialog')
                })
                .catch((err) => {
                    alert('通信に失敗しましたÏ')
                })
            this.loading = false
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
            this.$set(this.form, 'id', this.focusQuestion.id)
            this.$set(this.form, 'content', this.focusQuestion.content)
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