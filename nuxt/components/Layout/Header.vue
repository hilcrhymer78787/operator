<template>
    <v-app-bar v-if="$vuetify.breakpoint.xs" app class="header">
        <v-container class="d-flex align-center">
            <div @click="isShowMyinfo = true" style="width:45px;height:45px;">
                <v-img v-if="loginInfo.user_img.slice( 0, 4 ) == 'http'" :src="loginInfo.user_img" aspect-ratio="1" class="rounded-circle main_img"></v-img>
                <v-img v-else :src="backUrl+'/storage/'+loginInfo.user_img" aspect-ratio="1" class="rounded-circle main_img"></v-img>
            </div>
            <div class="pl-2">{{loginInfo.name}}</div>
            <v-spacer></v-spacer>
            <v-btn v-if="$root.layoutName == 'member' && loginInfo && loginInfo.authority" to="/admin" router>管理画面へ</v-btn>
            <v-btn v-if="$root.layoutName == 'admin'" to="/member" router>メンバー画面へ</v-btn>
        </v-container>
        <v-dialog max-width="476px" v-model="isShowMyinfo" scrollable>
            <LayoutHeaderMyinfo @onCloseSelf="isShowMyinfo = false" />
        </v-dialog>
    </v-app-bar>
</template>

<script>
import { mapState } from 'vuex'
export default {
    props: ['mode'],
    data() {
        return {
            backUrl: process.env.API_BASE_URL,
            isShowMyinfo: false,
        }
    },
    computed: {
        ...mapState(['loginInfo']),
    },
    methods: {},
}
</script>

<style lang="scss" scoped>
.header {
    ::v-deep {
        .v-toolbar__content {
            padding: 0 !important;
        }
    }
}
</style>