<template>
    <div>
        <PageReportCreate v-if="report && report == 'notFound'" @readReport="readReport"/>
        <PageReportEdit v-else-if="report && report.id" :report="report" />
        <div v-else class="text-center pa-5 ma-5">
            <v-progress-circular indeterminate color="main"></v-progress-circular>
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
        }
    },
    computed: {
        date() {
            return this.$route.query.date
        },
    },
    methods: {
        readReport() {
            this.$axios
                .get(`/api/report/read?date=${this.date}`)
                .then((res) => {
                    this.report = res.data
                    console.log(res.data)
                })
                .catch((err) => {
                    console.log(err)
                })
                .finally(() => {})
        },
    },
    mounted() {
        this.readReport()
    },
}
</script>

<style>
</style>
