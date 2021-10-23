<template>
    <v-card>
        <v-toolbar color="main" height="70px" dark class="d-block" style="box-shadow:none;">
            <v-toolbar-title>マニュアル</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn light height="35px" width="35px" fab elevation="0">
                <v-icon color="main">mdi-plus</v-icon>
            </v-btn>
        </v-toolbar>
        <v-card-text id="scrollArea" class="pa-0">
            <div id="scrollAreaInner">
                <v-list-item v-ripple class="pl-2 pr-0" style="height:60px;overflow:hidden;">
                    <v-list-item-content>
                        <v-list-item-title>hogehoge</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-divider></v-divider>
                <!-- <div class="pa-5 text-center">現在登録されているタスクはありません</div> -->
            </div>
        </v-card-text>
    </v-card>
</template>

<script>
export default {
    layout: 'member',
}
</script>

<style>
</style>