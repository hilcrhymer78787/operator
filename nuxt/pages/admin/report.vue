<template>
    <PageReport />
</template>
<script>
import moment from 'moment'
export default {
    layout: 'admin',
    middleware({ redirect, route }) {
        if (!route.query.date) {
            redirect(
                `/admin/report?date=${moment(new Date()).format('YYYY-MM-DD')}`
            )
        }
    },
}
</script>