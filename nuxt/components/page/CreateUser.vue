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
                <v-text-field validate-on-blur @keyup.enter="login" :rules="nameRules" required label="名前" placeholder="名前" prepend-inner-icon="mdi-account" outlined v-model="form.name" color="main"></v-text-field>
                <v-text-field validate-on-blur @keyup.enter="login" :rules="emailRules" required label="メールアドレス" placeholder="メールアドレス" prepend-inner-icon="mdi-email" outlined v-model="form.email" color="main"></v-text-field>
                <v-text-field validate-on-blur @keyup.enter="login" :rules="salalyRules" required label="給与" placeholder="給与" prepend-inner-icon="mdi-currency-usd" outlined v-model="form.salaly" color="main"></v-text-field>
                <v-text-field validate-on-blur @keyup.enter="login" :rules="passwordRules" required label="パスワード" placeholder="パスワード" prepend-inner-icon="mdi-lock" :append-icon="passwordShow ? 'mdi-eye' : 'mdi-eye-off'" :type="passwordShow ? 'text' : 'password'" outlined v-model="form.password" @click:append="passwordShow = !passwordShow" color="main"></v-text-field>
                <v-text-field validate-on-blur @keyup.enter="login" :rules="passwordAgainRules" required label="パスワードの確認" placeholder="パスワードの確認" prepend-inner-icon="mdi-lock" :append-icon="passwordAgainShow ? 'mdi-eye' : 'mdi-eye-off'" :type="passwordAgainShow ? 'text' : 'passwordAgain'" outlined v-model="form.passwordAgain" @click:append="passwordAgainShow = !passwordAgainShow" color="main"></v-text-field>
                <p v-if="errorMessage && noError" class="error_message mb-2">{{errorMessage}}</p>
            </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
            <v-btn v-if="mode=='edit'" :loading="loading" color="error" dark @click="deleteAccount()">アカウント削除</v-btn>
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
import { mapState } from "vuex";
export default {
    props: ["mode"],
    data() {
        return {
            loading: false,
            noError: false,
            errorMessage: "",
            imagePickerDialog: false,
            form: {
                id: 0,
                name: "",
                email: "",
                salaly: null,
                password: "",
                passwordAgain: "",
                user_img: "https://picsum.photos/500/300?image=40",
                token: "",
            },
            nameRules: [(v) => !!v || "名前は必須です"],
            emailRules: [
                (v) => !!v || "メールアドレスは必須です",
                (v) => /.+@.+\..+/.test(v) || "正しい形式で入力してください",
            ],
            salalyRules: [
                (v) => !!v || "給与は必須です",
                (v) => /[+-]?\d+/.test(v) || "数値で入力してください",
            ],
            passwordRules: [
                (v) => !!v || "パスワードは必須です",
                (v) =>
                    (v && v.length >= 8) ||
                    "パスワードは8桁以上で設定してください",
            ],
            passwordAgainRules: [
                (v) => !!v || "パスワードは必須です",
                (v) =>
                    (v && v.length >= 8) ||
                    "パスワードは8桁以上で設定してください",
            ],
            passwordShow: false,
            passwordAgainShow: false,
        };
    },
    computed: {
        ...mapState(["loginInfo"]),
        isMatchPassword() {
            return this.form.password == this.form.passwordAgain;
        },
    },
    methods: {
        async submit() {
            this.errorMessage = "";
            this.$refs.form.validate();
            // バリデーションエラー
            if (!this.noError) {
                return;
            }
            // パスワードの不一致
            if (!this.isMatchPassword) {
                this.errorMessage = "パスワードが一致しません";
                return;
            }
            // ログインAPI
            this.loading = true;
            await this.$axios
                .post(
                    `/api/user/create?token=${this.form.token}&id=${this.form.id}&name=${this.form.name}&email=${this.form.email}&password=${this.form.password}&user_img=${this.form.user_img}`
                )
                .then((res) => {
                    console.log(res)
                    this.errorMessage = "";
                    if (res.data.errorMessage) {
                        this.errorMessage = res.data.errorMessage;
                    } else {
                        this.$emit("onCloseDialog");
                    }
                })
                .catch((err) => {
                    this.errorMessage = "通信に失敗しました";
                });
            this.loading = false;
        },
        onSelectedImg(n) {
            this.$set(
                this.form,
                "user_img",
                `https://picsum.photos/500/300?image=${n}`
            );
        },
        async deleteAccount() {
            if (
                !confirm(
                    `「${this.loginInfo.name}」のアカウント情報を全て削除しますか？`
                )
            ) {
                return;
            }
            if (!confirm(`本当によろしいですか？`)) {
                return;
            }
            if (!confirm(`知らないですよ？`)) {
                return;
            }
            // アカウント削除API
            this.loading = true;
            await this.$axios
                .delete(`/api/user/delete?token=${this.form.token}`)
                .then((res) => {
                    this.$emit("onCloseDialog");
                })
                .catch((err) => {
                    alert("通信に失敗しました");
                });
            // ログアウト
            this.$store.dispatch('logout')
            this.loading = false;
        },
    },
    mounted() {
        if (this.mode == "edit") {
            this.$set(this.form, "token", this.loginInfo.token);
            this.$set(this.form, "id", this.loginInfo.id);
            this.$set(this.form, "name", this.loginInfo.name);
            this.$set(this.form, "email", this.loginInfo.email);
            this.$set(this.form, "password", "");
            this.$set(this.form, "passwordAgain", "");
            this.$set(this.form, "user_img", this.loginInfo.user_img);
        }
    },
};
</script>
<style lang="scss" scoped>
.error_message {
    color: #ff5252;
    font-size: 12px;
    margin-top: -27px;
    margin-left: 12px;
}
</style>