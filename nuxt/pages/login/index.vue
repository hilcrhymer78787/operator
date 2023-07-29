<template>
    <v-dialog max-width="476px" :value="true" scrollable hide-overlay persistent no-click-animation>
        <v-card class="mx-auto">
            <v-card-title>ログイン</v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-form v-model="noError" ref="form" class="pt-5">
                    <v-text-field validate-on-blur @keyup.enter="onClickLogin" :rules="emailRules" required label="メールアドレス" placeholder="メールアドレス" prepend-inner-icon="mdi-email" outlined v-model="form.email" color="main" class="pt-5"></v-text-field>
                    <v-text-field validate-on-blur @keyup.enter="onClickLogin" :rules="passwordRules" required label="パスワード" placeholder="パスワード" prepend-inner-icon="mdi-lock" :append-icon="passwordShow ? 'mdi-eye' : 'mdi-eye-off'" :type="passwordShow ? 'text' : 'password'" outlined v-model="form.password" @click:append="passwordShow = !passwordShow" color="main"></v-text-field>
                    <div v-if="NODE_ENV === 'development'" class="d-flex">
                        <v-btn @click="onClickAdminLogin">adminでログイン</v-btn>
                        <v-spacer></v-spacer>
                        <v-btn @click="onClickMemberLogin">memberでログイン</v-btn>
                    </div>
                </v-form>
                <p v-if="errorMessage && noError" class="error_message mb-2">{{errorMessage}}</p>
            </v-card-text>

            <v-divider></v-divider>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn :loading="loading" color="main" dark @click="onClickLogin">ログイン</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapState } from "vuex";
export default {
    layout:'login',
    data() {
        return {
            NODE_ENV:process.env.NODE_ENV,
            loading: false,
            noError: false,
            errorMessage: "",
            form: {
                email: "",
                password: "",
            },
            emailRules: [
                (v) => !!v || "メールアドレスは必須です",
                (v) => /.+@.+\..+/.test(v) || "正しい形式で入力してください",
            ],
            passwordRules: [
                (v) => !!v || "パスワードは必須です",
                (v) =>
                    (v && v.length >= 8) ||
                    "パスワードは8桁以上で設定してください",
            ],
            passwordShow: false,
        };
    },
    computed: {
        ...mapState(["loginInfo"]),
    },
    methods: {
        onClickLogin(){
            this.login(this.form.email, this.form.password)
        },
        onClickAdminLogin(){
            this.login("yusan@gmail.com", "password")
        },
        onClickMemberLogin(){
            this.login("koichi@gmail.com", "password")
        },
        async login(email, password) {
            this.$refs.form.validate();
            if (!this.noError) {
                return;
            }
            this.loading = true;
            this.errorMessage = ''
            await this.$axios
                .get(`/api/user/basic_authentication?email=${email}&password=${password}`)
                .then((res) => {
                    if (res.data.errorMessage) {
                        this.errorMessage = res.data.errorMessage;
                    } else {
                        this.$cookies.set("token", res.data.token, {
                            maxAge: 60 * 60 * 24 * 30,
                        });
                        this.$router.push("/member");
                    }
                })
                .catch(() => {
                    alert("通信エラーです");
                });
            this.loading = false;
        },
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