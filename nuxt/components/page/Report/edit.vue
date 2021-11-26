<template>
    <v-card>
        <v-card-title>
            <span>日報</span>
            <v-spacer></v-spacer>
            <span>{{jaDate}}</span>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
            <pre v-if="mode=='read'">{{report.content}}</pre>
            <v-form v-if="mode=='edit'" ref="form" class="pt-5">
                <v-textarea v-model="content" dense label="内容" outlined height="320px"></v-textarea>
            </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
            <v-btn v-if="mode=='edit'" @click="deleteReport()" dark color="error">削除</v-btn>
            <v-spacer></v-spacer>
            <v-btn v-if="mode=='read'" @click="setEdit()" dark color="sub">編集</v-btn>
            <v-btn v-if="mode=='edit'" @click="mode='read'">取消</v-btn>
            <v-btn v-if="mode=='edit'" @click="editReport()" :loading="editReportLoading" dark color="sub">送信</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import moment from 'moment'
export default {
    props: ['report'],
    data() {
        return {
            editReportLoading: false,
            mode: 'read',
            content: '',
        }
    },
    computed: {
        date() {
            return this.$route.query.date
        },
        jaDate() {
            moment.locale('ja', {
                weekdays: ['日', '月', '火', '水', '木', '金', '土'],
            })
            return moment(this.date).format('Y年M月D日（dddd）')
        },
    },
    methods: {
        setEdit() {
            this.mode = 'edit'
            this.content = this.report.content
        },
        editReport() {
            this.editReportLoading = true
            var data = {
                id:this.report.id,
                date: this.date,
                content: this.content,
            }
            this.$axios
                .put(`/api/report/edit`, { data: data })
                .then((res) => {
                    this.mode = 'read'
                    this.$emit('readReport')
                })
                .catch((err) => {
                    console.log(err)
                })
                .finally(() => {
                    this.editReportLoading = false
                })
        },
        deleteReport() {},
    },
}
</script>

<style>
</style>