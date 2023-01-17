<template>
    <div>
        <v-app v-if="loginInfo">
            <LayoutHeader />
            <v-main>
                <v-container>
                    <Nuxt />
                </v-container>
            </v-main>
            <LayoutFooter />
        </v-app>
    </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
    computed: {
        ...mapState(['loginInfo']),
    },
}
</script>
<style lang="scss">
.v-application {
    ul {
        padding: 0;
    }
    li {
        list-style: none;
    }
    p {
        margin: 0;
    }
    .v-btn:before {
        content: none;
    }
}
.v-card {
    overflow: hidden;
}
.v-card__title {
    background-color: #000066;
    color: white;
}
</style>