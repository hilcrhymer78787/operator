<template>
    <div>
        <v-card>
            <v-toolbar color="main" height="70px" dark class="d-block" style="box-shadow:none;">
                <v-toolbar-title>タスク</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn light height="35px" width="35px" fab elevation="0">
                    <v-icon color="main">mdi-plus</v-icon>
                </v-btn>
            </v-toolbar>
            <v-card-text id="scrollArea" class="pa-0">
                <div id="scrollAreaInner">
                    <div>
                        <v-list-item v-ripple class="pl-2 pr-0" style="height:60px;overflow:hidden;">
                            <v-list-item-content>
                                <v-list-item-title>hogehoge</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider></v-divider>
                    </div>
                    <!-- <div class="pa-5 text-center">現在登録されているタスクはありません</div> -->
                </div>
            </v-card-text>
        </v-card>

    </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
    components: {},
    data() {
        return {
            deleteTaskLoading: false,
            taskDialog: false,
            dialog: false,
            focusTask: null,
            loadings: [],
        }
    },
    computed: {
        ...mapState(['loginInfo']),
    },
    methods: {},
    mounted() {},
}
</script>
<style lang="scss" scoped>

</style>