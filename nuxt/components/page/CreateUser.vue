<template>
    <v-card max-width="350" class="mx-auto">
        <v-card-title>
            <span v-if="mode == 'create'">新規ユーザー登録</span>
            <span v-if="mode == 'edit'">ユーザー編集</span>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
            <v-form v-model="noError" ref="form" class="pt-5">
                <v-img @click="imagePickerDialog = true" :src="form.user_img" aspect-ratio="1" style="width:30%;" class="rounded-circle main_img mb-5 mx-auto"></v-img>
                <v-text-field validate-on-blur @keyup.enter="submit" :rules="nameRules" required label="名前" placeholder="名前" prepend-inner-icon="mdi-account" outlined v-model="form.name" color="main"></v-text-field>
                <v-text-field validate-on-blur @keyup.enter="submit" :rules="emailRules" required label="メールアドレス" placeholder="メールアドレス" prepend-inner-icon="mdi-email" outlined v-model="form.email" color="main"></v-text-field>
                <v-text-field validate-on-blur @keyup.enter="submit" :rules="salaryRules" required label="給与" placeholder="給与" prepend-inner-icon="mdi-currency-usd" outlined v-model="form.salary" color="main"></v-text-field>
                <v-text-field v-if="passwordEdit" validate-on-blur @keyup.enter="submit" :rules="passwordRules" required label="パスワード" placeholder="パスワード" prepend-inner-icon="mdi-lock" :append-icon="passwordShow ? 'mdi-eye' : 'mdi-eye-off'" :type="passwordShow ? 'text' : 'password'" outlined v-model="form.password" @click:append="passwordShow = !passwordShow" color="main"></v-text-field>
                <v-text-field v-if="passwordEdit" validate-on-blur @keyup.enter="submit" :rules="passwordAgainRules" required label="パスワードの確認" placeholder="パスワードの確認" prepend-inner-icon="mdi-lock" :append-icon="passwordAgainShow ? 'mdi-eye' : 'mdi-eye-off'" :type="passwordAgainShow ? 'text' : 'passwordAgain'" outlined v-model="form.passwordAgain" @click:append="passwordAgainShow = !passwordAgainShow" color="main"></v-text-field>
                <v-btn v-else @click="passwordEdit = true">パスワードを変更する</v-btn>
                <p v-if="errorMessage && noError" class="error_message mb-2">{{errorMessage}}</p>
            </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
            <v-btn v-if="mode=='edit'" :loading="loading" color="error" dark @click="deleteAccount()">ユーザー削除</v-btn>
            <v-spacer></v-spacer>
            <v-btn @click="$emit('onCloseDialog')">CLOSE</v-btn>
            <v-btn :loading="loading" color="main" dark @click="submit()">登録</v-btn>
        </v-card-actions>

        <v-dialog v-model="imagePickerDialog" scrollable>
            <PageImagePicker @onSelectedImg="onSelectedImg" @onCloseDialog="imagePickerDialog = false" v-if="imagePickerDialog" />
        </v-dialog>

    </v-card>
</template>

<script>
import { mapState } from 'vuex'
export default {
    props: ['mode', 'focusUser'],
    data() {
        return {
            loading: false,
            noError: false,
            errorMessage: '',
            imagePickerDialog: false,
            passwordEdit: true,
            form: {
                id: 0,
                name: '',
                email: '',
                salary: null,
                password: '',
                passwordAgain: '',
                user_img: 'https://picsum.photos/500/300?image=40',
                token: '',
            },
            nameRules: [(v) => !!v || '名前は必須です'],
            emailRules: [
                (v) => !!v || 'メールアドレスは必須です',
                (v) => /.+@.+\..+/.test(v) || '正しい形式で入力してください',
            ],
            salaryRules: [
                (v) => !!v || '給与は必須です',
                (v) => /[+-]?\d+/.test(v) || '数値で入力してください',
            ],
            passwordRules: [
                (v) => !!v || 'パスワードは必須です',
                (v) =>
                    (v && v.length >= 8) ||
                    'パスワードは8桁以上で設定してください',
            ],
            passwordAgainRules: [
                (v) => !!v || 'パスワードは必須です',
                (v) =>
                    (v && v.length >= 8) ||
                    'パスワードは8桁以上で設定してください',
            ],
            passwordShow: false,
            passwordAgainShow: false,
        }
    },
    computed: {
        ...mapState(['loginInfo']),
        isMatchPassword() {
            return this.form.password == this.form.passwordAgain
        },
    },
    methods: {
        async submit() {
            this.errorMessage = ''
            this.$refs.form.validate()
            // バリデーションエラー
            if (!this.noError) {
                return
            }
            // パスワードの不一致
            if (!this.isMatchPassword) {
                this.errorMessage = 'パスワードが一致しません'
                return
            }
            // ログインAPI
            this.loading = true
            await this.$axios
                .post(
                    `/api/user/create?id=${this.form.id}&name=${this.form.name}&email=${this.form.email}&password=${this.form.password}&user_img=${this.form.user_img}&salary=${this.form.salary}`
                )
                .then(async (res) => {
                    console.log(res.data)
                    this.errorMessage = ''
                    if (res.data.errorMessage) {
                        this.errorMessage = res.data.errorMessage
                    } else {
                        await this.$store.dispatch('setLoginInfoByToken')
                        this.$emit('onCloseDialog')
                    }
                })
                .catch((err) => {
                    this.errorMessage = '通信に失敗しました'
                })
            this.loading = false
        },
        onSelectedImg(n) {
            this.$set(
                this.form,
                'user_img',
                `https://picsum.photos/500/300?image=${n}`
            )
        },
        async deleteAccount() {
            if (
                !confirm(
                    `「${this.loginInfo.name}」のユーザー情報を全て削除しますか？`
                )
            ) {
                return
            }
            if (!confirm(`本当によろしいですか？`)) {
                return
            }
            if (!confirm(`知らないですよ？`)) {
                return
            }
            // ユーザー削除API
            this.loading = true
            await this.$axios
                .delete(`/api/user/delete?token=${this.form.token}`)
                .then((res) => {
                    this.$emit('onCloseDialog')
                })
                .catch((err) => {
                    alert('通信に失敗しました')
                })
            // ログアウト
            this.$store.dispatch('logout')
            this.loading = false
        },
    },
    mounted() {
        if (this.mode == 'edit') {
            this.passwordEdit = false
            this.$set(this.form, 'id', this.focusUser.id)
            this.$set(this.form, 'name', this.focusUser.name)
            this.$set(this.form, 'email', this.focusUser.email)
            this.$set(this.form, 'salary', this.focusUser.salary)
            this.$set(this.form, 'password', '')
            this.$set(this.form, 'passwordAgain', '')
            this.$set(this.form, 'user_img', this.focusUser.user_img)
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