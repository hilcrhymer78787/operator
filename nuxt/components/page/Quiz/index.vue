<template>
    <v-card>
        <v-toolbar color="main" height="70px" dark class="d-block" style="box-shadow:none;">
            <v-toolbar-title>問題</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn v-if="$root.layoutName == 'admin'" light height="35px" width="35px" fab elevation="0">
                <v-icon color="main" @click="dialogOpen('create')">mdi-plus</v-icon>
            </v-btn>
        </v-toolbar>
        <div v-if="isLoading" class="py-5 my-5 text-center">
            <v-progress-circular indeterminate color="main"></v-progress-circular>
        </div>
        <div v-else-if="!quizzes.length" class="pa-5 text-center">現在登録されている項目はありません</div>
        <v-card-text v-else class="d-flex align-center">
            <v-expansion-panels multiple>
                <v-expansion-panel v-for="(quiz,i) in quizzes" :key="i">
                    <v-expansion-panel-header>
                        Q{{i+1}}：{{quiz.title}}
                    </v-expansion-panel-header>
                    <v-expansion-panel-content style="color:orange;">
                        <p class="mb-4">A{{i+1}}</p>
                        <pre>{{quiz.content}}</pre>
                        <div v-if="$root.layoutName == 'admin'" class="d-flex mt-4">
                            <v-spacer></v-spacer>
                            <v-btn dark @click="dialogOpen('edit',quiz)" color="orange lighten-1" class="py-1">
                                <v-icon>mdi-pencil</v-icon>
                            </v-btn>
                        </div>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels>
        </v-card-text>

        <v-dialog max-width="476px" v-model="isShowCreateQuizDialog" scrollable>
            <PageCreateQuiz :mode="mode" :focusQuiz="focusQuiz" @onCloseDialog="isShowCreateQuizDialog = false" v-if="isShowCreateQuizDialog" />
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
            focusQuiz: '',
            isShowCreateQuizDialog: false,
            isLoading: false,
        }
    },
    computed: {
        ...mapState(['quizzes']),
    },
    methods: {
        dialogOpen(mode, quiz) {
            this.mode = mode
            this.focusQuiz = quiz
            this.isShowCreateQuizDialog = true
        },
    },
    async mounted() {
        this.isLoading = true
        await this.$store.dispatch('getQuiz')
        this.isLoading = false
    },
}
</script>
