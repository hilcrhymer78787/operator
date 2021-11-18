<template>
    <v-bottom-navigation app light fixed color="main">
        <v-btn v-for="(nav,navIndex) in navs" :key="navIndex" :style="`width:calc(100% / ${navs.length}); height:100%;background-color:white;`" :to="nav.to" router light exact>
            <span>{{nav.ttl}}</span>
            <v-badge :value="nav.badgeContent" :content="nav.badgeContent" color="main" offset-x="5" offset-y="15">
                <v-icon>{{nav.icon}}</v-icon>
            </v-badge>
        </v-btn>
    </v-bottom-navigation>
</template>

<script>
import { mapState } from 'vuex'
export default {
    data() {
        return {}
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
                ]
                return outputData
            }
        },
    },
}
</script>

<style>
</style>