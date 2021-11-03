<template>
    <v-card>
        <v-toolbar color="main" height="70px" dark class="d-block" style="box-shadow:none;">
            <v-toolbar-title>確認リスト</v-toolbar-title>
            <v-spacer></v-spacer>
        </v-toolbar>
        <div v-for="(question,index) in questions" :key="question.id">
            <dl class="d-flex align-center pt-3 pr-2 pl-0 pb-2">
                <dt style="width:40px;" class="text-center">{{ index + 1 }}</dt>
                <dd style="width:calc(100% - 40px);" class="pl-1">{{ question.content }}</dd>
            </dl>
            <v-radio-group v-if="isShowRadio" v-model="answers[index].answer" :column="false" mandatory hide-details class="ma-0 px-2 pb-3">
                <v-radio color="main" v-for="n in 5" :key="n" :label="`${n}`" :value="n" class="mr-4"></v-radio>
            </v-radio-group>
            <v-divider></v-divider>
        </div>
    </v-card>
</template>


<script>
import { mapState } from 'vuex'
export default {
    layout: 'member',
    data() {
        return {
            isShowRadio: false,
            radioGroup: '',
            answers: [],
        }
    },
    computed: {
        ...mapState(['questions']),
    },
    methods: {},
    async mounted() {
        await this.$store.dispatch('getQuestion')
        this.questions.forEach((question) => {
            this.answers.push({
                id: question.id,
                answer: 1,
            })
        })
        this.isShowRadio = true
    },
}
</script>

<style lang="scss" scoped>
::v-deep {
    .v-label {
        left: -5px !important;
    }
}
</style>