
<template>
    <div>
        <v-form ref="form">
            <v-card>
                <v-toolbar color="main" dark style="box-shadow:none;">
                    <v-toolbar-title>マイページ</v-toolbar-title>
                </v-toolbar>
                <v-card-text class="d-flex align-center">
                    <div class="mx-auto" style="width:30%;">
                        <v-img :src="loginInfo.user_img" aspect-ratio="1" class="rounded-circle main_img"></v-img>
                    </div>
                    <v-spacer></v-spacer>
                    <div class="pt-2" style="width:65%;">
                        <v-text-field class="mt-5 mb-3" dense color="main" prepend-icon="mdi-account" readonly label="名前" v-model="loginInfo.name"></v-text-field>
                        <v-text-field dense color="main" prepend-icon="mdi-email" readonly label="メールアドレス" v-model="loginInfo.email"></v-text-field>
                    </div>
                </v-card-text>
                <v-divider></v-divider>
                <div class="d-flex pa-3">
                    <v-spacer></v-spacer>
                    <v-btn class="mr-2" @click="$emit('onCloseSelf')">close</v-btn>
                    <v-btn color="danger" dark @click="logout()">ログアウト</v-btn>
                </div>
            </v-card>
        </v-form>
    </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
    data() {
        return {}
    },
    computed: {
        ...mapState(['loginInfo']),
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