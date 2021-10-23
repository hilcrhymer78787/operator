<template>
    <v-card>
        <v-toolbar color="main" height="70px" dark class="d-block" style="box-shadow:none;">
            <v-toolbar-title>ユーザー</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn light height="35px" width="35px" fab elevation="0">
                <v-icon color="main">mdi-plus</v-icon>
            </v-btn>
        </v-toolbar>
        <ul>
            <li v-for="(user,userIndex) in loginInfo.users" :key="user.id">
                <v-list-item class="pl-2 pr-0" style="height:60px;overflow:hidden;">
                    <v-list-item-avatar>
                        <v-img :src="loginInfo.user_img" aspect-ratio="1" class="rounded-circle main_img"></v-img>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title>{{user.name}}</v-list-item-title>
                    </v-list-item-content>
                    <v-btn dark color="sub" class="pa-0" style="margin-right:6px;">edit</v-btn>
                </v-list-item>
                <v-divider v-if="userIndex + 1 != loginInfo.users.length"></v-divider>
            </li>
        </ul>

        <!-- <v-simple-table>
            <thead>
                <tr>
                    <th class="text-left">id</th>
                    <th class="text-left">Name</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in loginInfo.users" :key="user.id">
                    <td>{{ user.id }}</td>
                    <td>{{ user.name }}</td>
                    <td>
                        <div class="d-flex justify-end">
                            <v-btn dark @click="dialogOpen(user.id,user.name)" color="sub" class="pa-0" style="margin-right:6px;">edit</v-btn>
                        </div>
                    </td>
                </tr>
            </tbody>
        </v-simple-table> -->
    </v-card>
</template>

<script>
import { mapState } from 'vuex'
export default {
    layout: 'admin',
    computed: {
        ...mapState(['loginInfo']),
    },
}
</script>

<style>
</style>