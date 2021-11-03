<template>
    <div>
        <v-app v-if="loginInfo && loginInfo.authority">
            <LayoutHeader mode="admin" />
            <v-main>
                <v-container style="max-width:500px;">
                    <Nuxt />
                </v-container>
            </v-main>
            <LayoutFooter mode="admin" />
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
.v-dialog {
    max-width: 476px !important;
}
.v-application {
    background-color: #e9e9e9 !important;
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
.v-card__title {
    background-color: #000066;
    color: white;
}
</style>