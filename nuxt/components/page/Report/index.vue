<template>
    <div>
        <div v-if="readReportLoading" class="text-center pa-5 ma-5">
            <v-progress-circular indeterminate color="main"></v-progress-circular>
        </div>
        <PageReportCreate v-else-if="report && report == 'notFound' && editable" @readReport="readReport" />
        <PageReportEdit v-else-if="report && report.id" :report="report" :editable="editable" @readReport="readReport" />
        <div v-else class="text-center pa-5 ma-5">
            <span>日報はありません</span>
        </div>
    </div>
</template>

<script>
import moment from 'moment'
import { mapState } from 'vuex'
export default {
    data() {
        return {
            report: null,
            userEditable: null,
            readReportLoading: false,
        }
    },
    computed: {
        date() {
            return this.$route.query.date
        },
        editable() {
            return this.userEditable || this.$root.layoutName == 'admin'
        },
    },
    methods: {
        readReport() {
            this.readReportLoading = true
            this.$axios
                .get(`/api/report/read?date=${this.date}`)
                .then((res) => {
                    console.log(res.data)
                    this.report = res.data.main
                    this.userEditable = res.data.editable
                })
                .catch((err) => {
                    console.log(err)
                })
                .finally(() => {
                    this.readReportLoading = false
                })
        },
    },
    mounted() {
        this.readReport()
    },
    watch: {
        $route() {
            this.readReport()
        },
    },
}
</script>

<style>
</style>
