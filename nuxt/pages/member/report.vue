<template>
    <PageReport />
</template>
<script>
import moment from 'moment'
export default {
    layout: 'member',
    middleware({ redirect, route }) {
        if (!route.query.date) {
            redirect(
                `/member/report?date=${moment(new Date()).format('YYYY-MM-DD')}`
            )
        }
    },
}
</script>