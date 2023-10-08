<template>
    <v-card>
        <v-toolbar color="main" height="70px" dark class="d-block" style="box-shadow:none;">
            <v-toolbar-title>確認表</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn light height="35px" width="35px" fab elevation="0">
                <v-icon color="main" @click="dialogOpen('create')">mdi-plus</v-icon>
            </v-btn>
        </v-toolbar>
        <div v-if="!questions.length" class="pa-5 text-center">現在登録されている項目はありません</div>
        <v-simple-table>
            <tbody>
                <tr v-for="(question,index) in questions" :key="index">
                    <td class="pt-2 pb-1 pr-0">
                        <div class="index" :class="{focus:question.important}">{{ index + 1 }}</div>
                    </td>
                    <td class="pt-2 pb-1 pr-0">{{ question.content }}</td>
                    <td>
                        <div class="d-flex justify-end">
                            <v-btn dark @click="dialogOpen('edit',question)" color="orange lighten-1" class="py-1">
                                <v-icon>mdi-pencil</v-icon>
                            </v-btn>
                        </div>
                    </td>
                </tr>
            </tbody>
        </v-simple-table>

        <v-dialog max-width="476px" v-model="isShowCreateQuestionDialog" scrollable>
            <PageCreateQuestion :mode="mode" :focusQuestion="focusQuestion" @onCloseDialog="isShowCreateQuestionDialog = false" v-if="isShowCreateQuestionDialog" />
        </v-dialog>

    </v-card>
</template>

<script>
import { mapState } from 'vuex'
export default {
    layout: 'admin',
    data() {
        return {
            mode: '',
            focusQuestion: '',
            isShowCreateQuestionDialog: false,
        }
    },
    computed: {
        ...mapState(['questions']),
    },
    methods: {
        dialogOpen(mode, question) {
            this.mode = mode
            this.focusQuestion = question
            this.isShowCreateQuestionDialog = true
        },
    },
    async mounted() {
        await this.$store.dispatch('getQuestion')
    },
}
</script>

<style lang="scss" scoped>
.index {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 25px;
    height: 25px;
    &.focus {
        border-radius: 5px;
        border: 2px solid #ffa726;
    }
}
</style>