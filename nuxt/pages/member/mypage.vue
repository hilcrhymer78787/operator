
<template>
    <div>
        <v-form ref="form">
            <v-card class="mb-5">
                <v-toolbar color="main" dark style="box-shadow:none;">
                    <v-toolbar-title>マイページ</v-toolbar-title>
                </v-toolbar>
                <v-card-text class="d-flex align-center">
                    <div class="mx-auto" style="width:30%;">
                        <v-img :src="loginInfo.user_img" aspect-ratio="1" class="rounded-circle main_img"></v-img>
                    </div>
                    <v-spacer></v-spacer>
                    <div class="pt-2" style="width:65%;">
                        <v-text-field class="mt-5 mb-3" dense color="main" prepend-icon="mdi-account" readonly label="名前" v-model="loginInfo.name"></v-text-field>
                        <v-text-field dense color="main" prepend-icon="mdi-email" readonly label="メールアドレス" v-model="loginInfo.email"></v-text-field>
                    </div>
                </v-card-text>
                <v-divider></v-divider>
                <div class="d-flex pa-3">
                    <v-spacer></v-spacer>
                    <v-btn @click="logout()" class="mr-2">ログアウト</v-btn>
                    <v-btn @click="createUserDialog = true" dark color="orange lighten-1">編集</v-btn>
                </div>
            </v-card>
        </v-form>

    </div>
</template>

<script>
import { mapState } from "vuex";
export default {
    data() {
        return {
            selectedRoomId: 0,
            onChangeRoomloading: false,
            joinRoomloadings: [],
            rejectRoomloadings: [],
            createUserDialog: false,
            modeCreateRoomDialog: false,
            createRoomDialog: false,
        };
    },
    computed: {
        ...mapState(["loginInfo"]),
    },
    methods: {
        logout() {
            if (confirm("ログアウトしますか？")) {
                this.$store.dispatch('logout')
            }
        },
        openCreateRoomDialog(mode) {
            this.modeCreateRoomDialog = mode;
            this.createRoomDialog = true;
        },
        async onChangeRoom() {
            this.onChangeRoomloading = true;
            await this.$axios
                .put(
                    `/api/user/update/room_id?token=${this.loginInfo.token}&room_id=${this.selectedRoomId}`
                )
                .catch((err) => {
                    alert("通信に失敗しました");
                });
            await this.$store.dispatch("setLoginInfoByToken");
            this.onChangeRoomloading = false;
        },
        roomMateNames(users) {
            let outputData = "";
            users.forEach((user, index) => {
                if (index != 0) {
                    outputData = outputData + "、";
                }
                outputData = outputData + user.name;
            });
            return outputData;
        },
        async joinRoom(invitationId, roomIndex) {
            this.$set(this.joinRoomloadings, roomIndex, true);
            await this.$axios
                .put(
                    `/api/invitation/update?token=${this.loginInfo.token}&invitation_id=${invitationId}`
                )
                .then(async (res) => {
                    await this.$store.dispatch("setLoginInfoByToken");
                })
                .catch((err) => {
                    alert("通信に失敗しました");
                });
            this.$set(this.joinRoomloadings, roomIndex, false);
        },
        async rejectRoom(invitationId, roomIndex, roomName) {
            if (!confirm(`「${roomName}」への招待を拒否しますか？`)) {
                return;
            }
            this.$set(this.rejectRoomloadings, roomIndex, true);
            await this.$axios
                .delete(
                    `/api/invitation/delete?token=${this.loginInfo.token}&invitation_id=${invitationId}`
                )
                .then(async (res) => {
                    await this.$store.dispatch("setLoginInfoByToken");
                })
                .catch((err) => {
                    alert("通信に失敗しました");
                });
            this.$set(this.rejectRoomloadings, roomIndex, false);
        },
    },
    watch: {
        loginInfo() {
            this.selectedRoomId = this.loginInfo.room_id;
        },
    },
    mounted() {
        this.selectedRoomId = this.loginInfo.room_id;
    },
};
</script>