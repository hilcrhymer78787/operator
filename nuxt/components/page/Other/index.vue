<template>
    <div class="list">
        <v-card v-for="nav in navs" :key="nav.path" class="card" v-ripple @click="$router.push(nav.path)">
            <div class="card_inner">
                <v-icon size="40">{{nav.icon}}</v-icon>
                <p>{{nav.ttl}}</p>
            </div>
        </v-card>
    </div>
</template>
<script>
export default {
    props: ['path'],
    data() {
        return {
            navs: [
                {
                    path: `${this.path}/quiz`,
                    icon: 'mdi-head-question-outline',
                    ttl:'問題'
                },
                {
                    path: `${this.path}/rule`,
                    icon: 'mdi-note',
                    ttl:'規約'
                },
            ],
        }
    },
}
</script>
<style lang="scss" scoped>
.list {
    display: flex;
    .card {
        padding: 20px;
        width: 32%;
        margin-right: 2%;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        &:last-child {
            margin-right: 0;
        }
        &_inner {
            text-align: center;
        }
    }
}
</style>