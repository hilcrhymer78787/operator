<template>
    <v-card>
        <v-toolbar color="main" height="70px" dark class="d-block" style="box-shadow:none;">
            <v-toolbar-title>ユーザー</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn v-if="$root.layoutName == 'admin'" @click="openCreateUserDialog('create','')" light height="35px" width="35px" fab elevation="0">
                <v-icon color="main">mdi-plus</v-icon>
            </v-btn>
        </v-toolbar>
        <ul>

            <li v-for="(user,userIndex) in users" :key="userIndex">
                <v-list-item class="pl-2 pr-0" style="height:60px;overflow:hidden;">
                    <v-list-item-avatar>
                        <v-img v-if="user.user_img.slice( 0, 4 ) == 'http'" :src="user.user_img" aspect-ratio="1" class="rounded-circle main_img"></v-img>
                        <v-img v-else :src="backUrl+'/storage/'+user.user_img" aspect-ratio="1" class="rounded-circle main_img"></v-img>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title>{{user.name}}</v-list-item-title>
                        <v-list-item-subtitle v-if="$root.layoutName == 'admin'" style="font-size:12px;">
                            <span>給与:{{user.salary}}</span>
                        </v-list-item-subtitle>
                    </v-list-item-content>
                    <v-btn @click="$router.push(`${path}/user?year=${year}&month=${month}&user_id=${user.id}&user_name=${user.name}`)" class="pa-0 mr-3">
                        <v-icon>mdi-list-status</v-icon>
                    </v-btn>
                    <v-btn v-if="$root.layoutName == 'admin'" @click="openCreateUserDialog('show',user)" class="pa-0 mr-3">
                        <v-icon>mdi-eye</v-icon>
                    </v-btn>
                </v-list-item>
                <v-divider v-if="userIndex + 1 != users.length"></v-divider>
            </li>
        </ul>

        <v-dialog max-width="476px" v-model="isShowCreateUserDialog" scrollable>
            <PageCreateUser :mode="mode" :focusUser="focusUser" @onCloseDialog="isShowCreateUserDialog = false" @openCreateUserDialog="openCreateUserDialog" v-if="isShowCreateUserDialog" />
        </v-dialog>

        <v-dialog max-width="476px" :value="$route.query.year && $route.query.month && $route.query.user_id" scrollable persistent @click:outside="$router.push(`${path}/user`)">
            <PageCreateAnswer :mode="$root.layoutName" :path="`${path}/user`" />
        </v-dialog>

    </v-card>
</template>

<script>
import { mapState } from 'vuex'
export default {
    props: ['path'],
    layout: 'admin',
    beforeRouteUpdate(to, from, next) {
        if (!this.$store.state.rootRock) {
            next()
            return
        }
        if (
            confirm(
                'ページを移動すると入力途中のデータが削除されますが、移動しますか？'
            )
        ) {
            next()
            this.$store.commit('setRootRock', false)
        } else {
            next(false)
        }
    },
    data() {
        return {
            backUrl: process.env.API_BASE_URL,
            mode: 'create',
            focusUser: '',
            isShowCreateUserDialog: false,
        }
    },
    computed: {
        ...mapState(['loginInfo']),
        users() {
            return this.$root.layoutName == 'admin'
                ? this.loginInfo.admin.users
                : this.loginInfo.users.filter((user) => !user.authority)
        },
        year() {
            return new Date().getFullYear()
        },
        month() {
            return new Date().getMonth() + 1
        },
    },
    methods: {
        openCreateUserDialog(mode, user) {
            this.mode = mode
            if (user) {
                this.focusUser = user
            }
            this.isShowCreateUserDialog = true
        },
    },
}
</script>