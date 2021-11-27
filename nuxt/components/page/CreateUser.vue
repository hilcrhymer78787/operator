<template>
    <v-card class="mx-auto">
        <v-card-title>
            <span v-if="mode == 'create'">新規ユーザー登録</span>
            <span v-if="mode == 'edit'">ユーザー編集</span>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
            <v-form v-model="noError" ref="form" class="pt-5">
                <div class="mb-5 d-flex align-center justify-center">
                    <div @click="imagePickerDialog = true" class="mr-5" style="width:30%;">
                        <v-img v-if="file" :src="uploadedImage" aspect-ratio="1" class="rounded-circle main_img"></v-img>
                        <v-img v-else-if="form.user_img.slice( 0, 4 ) == 'http'" :src="form.user_img" aspect-ratio="1" class="rounded-circle main_img"></v-img>
                        <v-img v-else :src="backUrl+'/storage/'+form.user_img" aspect-ratio="1" class="rounded-circle main_img"></v-img>
                    </div>
                    <v-btn @click="$refs.input.click()">
                        <v-icon>mdi-upload</v-icon>
                    </v-btn>
                    <input ref="input" class="d-none" type="file" accept="image/*" @change="fileSelected">
                </div>
                <div class="mb-4 text-center">出演歴{{appearanceLength}}</div>
                <v-checkbox v-if="loginInfo.id != focusUser.id" v-model="form.authority" color="main" label="管理画面へのアクセス権限" class="ma-0 pa-0"></v-checkbox>
                <v-text-field dense validate-on-blur @keyup.enter="submit" :rules="nameRules" required label="名前" placeholder="名前" prepend-inner-icon="mdi-account" outlined v-model="form.name" color="main"></v-text-field>
                <v-text-field dense validate-on-blur @keyup.enter="submit" :rules="emailRules" required label="メールアドレス" placeholder="メールアドレス" prepend-inner-icon="mdi-email" outlined v-model="form.email" color="main"></v-text-field>
                <v-text-field dense validate-on-blur @keyup.enter="submit" :rules="salaryRules" required label="給与" placeholder="給与" prepend-inner-icon="mdi-currency-usd" outlined v-model="form.salary" color="main"></v-text-field>
                <v-text-field dense @click="datePickerDialog = true" :rules="joinedCompanyAtRules" readonly required label="入社日" placeholder="入社日" prepend-inner-icon="mdi-clipboard-text-clock" outlined :value="joinedCompanyAtFormated" color="main"></v-text-field>
                <v-text-field dense v-if="passwordEdit" validate-on-blur @keyup.enter="submit" :rules="passwordRules" required label="パスワード" placeholder="パスワード" prepend-inner-icon="mdi-lock" :append-icon="passwordShow ? 'mdi-eye' : 'mdi-eye-off'" :type="passwordShow ? 'text' : 'password'" outlined v-model="form.password" @click:append="passwordShow = !passwordShow" color="main"></v-text-field>
                <v-text-field dense v-if="passwordEdit" validate-on-blur @keyup.enter="submit" :rules="passwordAgainRules" required label="パスワードの確認" placeholder="パスワードの確認" prepend-inner-icon="mdi-lock" :append-icon="passwordAgainShow ? 'mdi-eye' : 'mdi-eye-off'" :type="passwordAgainShow ? 'text' : 'passwordAgain'" outlined v-model="form.passwordAgain" @click:append="passwordAgainShow = !passwordAgainShow" color="main"></v-text-field>
                <v-btn v-else @click="passwordEdit = true">パスワードを変更する</v-btn>
                <p v-if="errorMessage && noError" class="error_message mb-2">{{errorMessage}}</p>
            </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
            <v-btn v-if="mode=='edit'" :loading="deleteUserLoading" color="error" dark @click="deleteUser()">ユーザー削除</v-btn>
            <v-spacer></v-spacer>
            <v-btn @click="$emit('onCloseDialog')">CLOSE</v-btn>
            <v-btn :loading="loading" color="main" dark @click="submit()">登録</v-btn>
        </v-card-actions>

        <v-dialog v-model="datePickerDialog">
            <v-date-picker v-model="form.joinedCompanyAt" @change="datePickerDialog = false" type="month" locale="ja"></v-date-picker>
        </v-dialog>

        <v-dialog v-model="imagePickerDialog" scrollable>
            <PageImagePicker @onSelectedImg="onSelectedImg" @onCloseDialog="imagePickerDialog = false" v-if="imagePickerDialog" />
        </v-dialog>

    </v-card>
</template>

<script>
import { mapState } from 'vuex'
import moment from 'moment'
export default {
    props: ['mode', 'focusUser'],
    data() {
        return {
            datePickerDialog: false,
            uploadedImage: null,
            file: null,
            backUrl: process.env.API_BASE_URL,
            deleteUserLoading: false,
            loading: false,
            noError: false,
            errorMessage: '',
            imagePickerDialog: false,
            passwordEdit: true,
            form: {
                id: 0,
                authority: false,
                name: '',
                email: '',
                salary: null,
                joinedCompanyAt: '',
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
            joinedCompanyAtRules: [(v) => !!v || '入社日は必須です'],
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
        joinedCompanyAtFormated() {
            return this.form.joinedCompanyAt ? moment(this.form.joinedCompanyAt).format('Y年M月') : ''
        },
        appearanceLength() {
            var dateTo = moment(this.form.joinedCompanyAt)
            var dateFrom = moment()
            var totalMonths = dateFrom.diff(dateTo, 'months') + 1
            var year = Math.floor(totalMonths / 12)
            var month = totalMonths % 12
            return `${year ? year + '年' : ''}${month}ヶ月目`
        },
    },
    methods: {
        fileSelected(e) {
            this.file = e.target.files[0]
            this.$set(
                this.form,
                'user_img',
                moment().format('YYYYMMDDHHmmss') + this.file.name
            )
            let reader = new FileReader()
            reader.onload = (e) => {
                this.uploadedImage = e.target.result
            }
            reader.readAsDataURL(this.file)
        },
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
            let imgData = new FormData()
            imgData.append('file', this.file)
            await this.$axios
                .post(
                    `/api/user/create?id=${this.form.id}&authority=${
                        this.form.authority ? 1 : 0
                    }&name=${this.form.name}&email=${
                        this.form.email
                    }&password=${this.form.password}&user_img=${
                        this.form.user_img
                    }&img_oldname=${this.form.img_oldname}&salary=${
                        this.form.salary
                    }&joined_company_at=${this.form.joinedCompanyAt}-01&exist_file=${
                        this.file ? 1 : 0
                    }`,
                    imgData
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
        async deleteUser() {
            if (this.focusUser.authority) {
                alert(
                    '管理画面へのアクセス権限を持ったユーザーは削除できません'
                )
                return
            }
            if (
                !confirm(
                    `「${this.focusUser.name}」のユーザー情報を全て削除しますか？`
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
            this.deleteUserLoading = true
            await this.$axios
                .delete(`/api/user/delete?id=${this.focusUser.id}`)
                .then(async (res) => {
                    await this.$store.dispatch('setLoginInfoByToken')
                    this.$emit('onCloseDialog')
                })
                .catch((err) => {
                    alert('通信に失敗しました')
                })
            this.deleteUserLoading = false
        },
    },
    beforeDestroy() {
        this.file = null
    },
    mounted() {
        this.$set(this.form, 'joinedCompanyAt', moment().format('Y-M-D'))
        if (this.mode == 'edit') {
            console.log(this.focusUser)
            this.passwordEdit = false
            this.$set(this.form, 'id', this.focusUser.id)
            this.$set(this.form, 'authority', this.focusUser.authority)
            this.$set(this.form, 'name', this.focusUser.name)
            this.$set(this.form, 'email', this.focusUser.email)
            this.$set(this.form, 'salary', this.focusUser.salary)
            this.$set(this.form, 'joinedCompanyAt', moment(this.focusUser.joined_company_at).format('Y-M'))
            this.$set(this.form, 'password', '')
            this.$set(this.form, 'passwordAgain', '')
            this.$set(this.form, 'user_img', this.focusUser.user_img)
            this.$set(this.form, 'img_oldname', this.focusUser.user_img)
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