<template>
    <v-bottom-navigation v-if="$vuetify.breakpoint.xs" app light fixed color="main">
        <v-btn v-for="(nav,navIndex) in navs" :key="navIndex" :style="`width:calc(100% / ${navs.length}); height:100%;background-color:white;`" :to="nav.to" router light exact>
            <span>{{nav.ttl}}</span>
            <v-badge :value="nav.badgeContent" :content="nav.badgeContent" color="main" offset-x="5" offset-y="15">
                <v-icon>{{nav.icon}}</v-icon>
            </v-badge>
        </v-btn>
    </v-bottom-navigation>
    <v-navigation-drawer v-else width="200px" app permanent>
        <v-list-item two-line>
            <v-list-item-avatar @click="isShowMyinfo = true">
                <v-img v-if="loginInfo.user_img.slice( 0, 4 ) == 'http'" :src="loginInfo.user_img" aspect-ratio="1" class="rounded-circle main_img"></v-img>
                <v-img v-else :src="backUrl+'/storage/'+loginInfo.user_img" aspect-ratio="1" class="rounded-circle main_img"></v-img>
            </v-list-item-avatar>
            <v-list-item-content>
                <v-list-item-title>{{loginInfo.name}}</v-list-item-title>
                <v-btn v-if="$root.layoutName == 'member' && loginInfo && loginInfo.authority" to="/admin" router elevation="0">管理画面へ</v-btn>
                <v-btn v-if="$root.layoutName == 'admin'" to="/member" router elevation="0">メンバー画面へ</v-btn>
            </v-list-item-content>
            <v-dialog max-width="476px" v-model="isShowMyinfo" scrollable>
                <LayoutHeaderMyinfo @onCloseSelf="isShowMyinfo = false" />
            </v-dialog>
        </v-list-item>
        <v-divider></v-divider>
        <v-list nav dense>
            <v-list-item v-for="nav in navs" :key="nav.ttl" :to="nav.to" active-class="hoge" router light exact>
                <v-list-item-icon>
                    <v-badge :value="nav.badgeContent" :content="nav.badgeContent" color="main" offset-x="5" offset-y="15">
                        <v-icon>{{nav.icon}}</v-icon>
                    </v-badge>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title>{{ nav.ttl }}</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
import { mapState } from 'vuex'
export default {
    data() {
        return {
            backUrl: process.env.API_BASE_URL,
            isShowMyinfo: false,
        }
    },
    computed: {
        ...mapState(['loginInfo']),
        year() {
            return new Date().getFullYear()
        },
        month() {
            return new Date().getMonth() + 1
        },
        navs() {
            if (this.$root.layoutName == 'admin') {
                let outputData = [
                    {
                        to: `/admin/?year=${this.year}&month=${this.month}`,
                        ttl: 'シフト',
                        icon: 'mdi-calendar-check',
                        badgeContent: 0,
                    },
                    {
                        to: '/admin/question',
                        ttl: '確認表',
                        icon: 'mdi-list-status',
                        badgeContent: 0,
                    },
                    {
                        to: `/admin/task/?year=${this.year}&month=${this.month}`,
                        ttl: 'タスク',
                        icon: 'mdi-playlist-check',
                        badgeContent: this.loginInfo.admin.incompleteTaskNum,
                    },
                    {
                        to: '/admin/user',
                        ttl: 'ユーザー',
                        icon: 'mdi-account-group',
                        badgeContent: 0,
                    },
                    {
                        to: `/admin/other`,
                        ttl: 'その他',
                        icon: 'mdi-dots-horizontal',
                        badgeContent: 0,
                    },
                ]
                return outputData
            }
            if (this.$root.layoutName == 'member') {
                let outputData = [
                    {
                        to: `/member/?year=${this.year}&month=${this.month}`,
                        ttl: 'シフト',
                        icon: 'mdi-calendar-check',
                        badgeContent: 0,
                    },
                    {
                        to: `/member/question/?year=${this.year}&month=${this.month}&user_id=${this.loginInfo.id}&user_name=${this.loginInfo.name}`,
                        ttl: '確認表',
                        icon: 'mdi-list-status',
                        badgeContent: 0,
                    },
                    {
                        to: `/member/task/?year=${this.year}&month=${this.month}`,
                        ttl: 'タスク',
                        icon: 'mdi-playlist-check',
                        badgeContent: this.loginInfo.tasks.length,
                    },
                    {
                        to: '/member/user',
                        ttl: 'ユーザー',
                        icon: 'mdi-account-group',
                        badgeContent: 0,
                    },
                    {
                        to: `/member/other`,
                        ttl: 'その他',
                        icon: 'mdi-dots-horizontal',
                        badgeContent: 0,
                    },
                ]
                return outputData
            }
        },
    },
}
</script>

<style lang="scss" scoped>
.v-bottom-navigation {
    padding: calc(env(safe-area-inset-bottom)) 0
        calc(env(safe-area-inset-bottom) * 1.5);
}
</style>