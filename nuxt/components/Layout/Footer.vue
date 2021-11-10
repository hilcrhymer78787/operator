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
export default {
    props: ['mode'],
    data() {
        return {}
    },
    computed: {
        navs() {
            if (this.mode == 'admin') {
                let outputData = [
                    {
                        to: `/admin/?year=${new Date().getFullYear()}&month=${
                            new Date().getMonth() + 1
                        }`,
                        ttl: 'シフト',
                        icon: 'mdi-calendar-check',
                        badgeContent: 0,
                    },
                    {
                        to: '/admin/question',
                        ttl: '確認リスト',
                        icon: 'mdi-list-status',
                        badgeContent: 0,
                    },
                    {
                        to: `/admin/task/?year=${new Date().getFullYear()}&month=${
                            new Date().getMonth() + 1
                        }`,
                        ttl: 'タスク',
                        icon: 'mdi-playlist-check',
                        badgeContent: 0,
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
            if (this.mode == 'member') {
                let outputData = [
                    {
                        to: `/member/?year=${new Date().getFullYear()}&month=${
                            new Date().getMonth() + 1
                        }`,
                        ttl: 'シフト',
                        icon: 'mdi-calendar-check',
                        badgeContent: 0,
                    },
                    {
                        to: '/member/question',
                        ttl: '確認リスト',
                        icon: 'mdi-list-status',
                        badgeContent: 0,
                    },
                    {
                        to: `/member/task/?year=${new Date().getFullYear()}&month=${
                            new Date().getMonth() + 1
                        }`,
                        ttl: 'タスク',
                        icon: 'mdi-playlist-check',
                        badgeContent: 0,
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