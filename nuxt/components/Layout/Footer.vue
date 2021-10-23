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
    data() {
        return {
            navs: [
                {
                    to: '/member',
                    ttl: 'カレンダー',
                    icon: 'mdi-calendar-check',
                    badgeContent: 0,
                },
                {
                    to: '/member/hoge',
                    ttl: '確認リスト',
                    icon: 'mdi-list-status',
                    badgeContent: 0,
                },
                {
                    to: '/member/task',
                    ttl: 'タスク',
                    icon: 'mdi-playlist-check',
                    badgeContent: 0,
                },
            ],
        }
    },
}
</script>

<style>
</style>