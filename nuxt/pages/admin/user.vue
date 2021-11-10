<template>
    <v-card>
        <v-toolbar color="main" height="70px" dark class="d-block" style="box-shadow:none;">
            <v-toolbar-title>ユーザー</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn @click="openCreateUserDialog('create','')" light height="35px" width="35px" fab elevation="0">
                <v-icon color="main">mdi-plus</v-icon>
            </v-btn>
        </v-toolbar>
        <ul>
            <li v-for="(user,userIndex) in loginInfo.users" :key="user.id">
                <v-list-item class="pl-2 pr-0" style="height:60px;overflow:hidden;">
                    <v-list-item-avatar>
                        <v-img :src="user.user_img" aspect-ratio="1" class="rounded-circle main_img"></v-img>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title>{{user.name}}</v-list-item-title>
                        <v-list-item-subtitle style="font-size:12px;">
                            <span>給与:{{user.salary}}</span>
                        </v-list-item-subtitle>
                    </v-list-item-content>
                    <v-btn @click="openCreateUserDialog('edit',user)" dark width="30px" color="sub" class="pa-0 mr-3">
                        <v-icon>mdi-pencil</v-icon>
                    </v-btn>
                </v-list-item>
                <v-divider v-if="userIndex + 1 != loginInfo.users.length"></v-divider>
            </li>
        </ul>

        <v-dialog v-model="isShowCreateUserDialog" scrollable>
            <PageCreateUser :mode="mode" :focusUser="focusUser" @onCloseDialog="isShowCreateUserDialog = false" v-if="isShowCreateUserDialog" />
        </v-dialog>

    </v-card>
</template>

<script>
import { mapState } from 'vuex'
export default {
    layout: 'admin',
    data() {
        return {
            mode: 'create',
            focusUser: '',
            isShowCreateUserDialog: false,
        }
    },
    computed: {
        ...mapState(['loginInfo']),
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
