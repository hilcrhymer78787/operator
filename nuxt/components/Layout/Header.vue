<template>
    <v-app-bar app>
        <div style="width:45px;height:45px;">
            <v-img @click="isShowMyinfo = true" :src="loginInfo.user_img" aspect-ratio="1" class="rounded-circle main_img"></v-img>
        </div>
        <div class="pl-2">{{loginInfo.name}}</div>
        <v-spacer></v-spacer>
        <v-btn v-if="$root.layoutName == 'member' && loginInfo && loginInfo.authority" to="/admin" router>管理画面へ</v-btn>
        <v-btn v-if="$root.layoutName == 'admin'" to="/member" router>メンバー画面へ</v-btn>
        <v-dialog v-model="isShowMyinfo" scrollable>
            <LayoutHeaderMyinfo @onCloseSelf="isShowMyinfo = false"/>
        </v-dialog>
    </v-app-bar>
</template>

<script>
import { mapState } from 'vuex'
export default {
    props:['mode'],
    data() {
        return {
            isShowMyinfo: false,
        }
    },
    computed: {
        ...mapState(['loginInfo']),
    },
    methods: {},
}
</script>

<style>
</style>