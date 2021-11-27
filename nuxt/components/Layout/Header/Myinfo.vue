
<template>
    <div>
        <v-form ref="form">
            <v-card>
                <v-toolbar color="main" dark style="box-shadow:none;">
                    <v-toolbar-title>マイページ</v-toolbar-title>
                </v-toolbar>
                <v-card-text class="d-flex align-center">
                    <div class="mx-auto" style="width:30%;">
                        <v-img v-if="loginInfo.user_img.slice( 0, 4 ) == 'http'" :src="loginInfo.user_img" aspect-ratio="1" class="rounded-circle main_img"></v-img>
                        <v-img v-else :src="backUrl+'/storage/'+loginInfo.user_img" aspect-ratio="1" class="rounded-circle main_img"></v-img>
                    </div>
                    <v-spacer></v-spacer>
                    <div class="pt-2" style="width:65%;">
                        <v-text-field class="mt-5 mb-3" dense color="main" prepend-icon="mdi-account" readonly label="名前" v-model="loginInfo.name"></v-text-field>
                        <v-text-field class="mb-3" dense color="main" prepend-icon="mdi-email" readonly label="メールアドレス" v-model="loginInfo.email"></v-text-field>
                        <v-text-field class="mb-3" dense color="main" prepend-icon="mdi-currency-usd" readonly label="給与" v-model="loginInfo.salary"></v-text-field>
                        <v-text-field dense color="main" prepend-icon="mdi-clipboard-text-clock" readonly label="出演歴" v-model="appearanceLength"></v-text-field>
                    </div>
                </v-card-text>
                <v-divider></v-divider>
                <div class="d-flex pa-3">
                    <v-btn color="danger" dark @click="logout()">ログアウト</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn class="mr-2" @click="$emit('onCloseSelf')"><v-icon>mdi-close</v-icon></v-btn>
                </div>
            </v-card>
        </v-form>
    </div>
</template>

<script>
import moment from 'moment'
import { mapState } from 'vuex'
export default {
    data() {
        return {
            backUrl: process.env.API_BASE_URL,
        }
    },
    computed: {
        ...mapState(['loginInfo']),
        appearanceLength() {
            var dateTo = moment(this.loginInfo.joined_company_at)
            var dateFrom = moment()
            var totalMonths = dateFrom.diff(dateTo, 'months') + 1
            var year = Math.floor(totalMonths / 12)
            var month = totalMonths % 12
            return `${year ? year + '年' : ''}${month}ヶ月目`
        },
    },
    methods: {
        logout() {
            if (confirm('ログアウトしますか？')) {
                this.$store.dispatch('logout')
            }
        },
    },
    mounted() {},
}
</script>