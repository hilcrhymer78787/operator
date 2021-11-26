<template>
    <PageReport />
</template>
<script>
import moment from 'moment'
export default {
    layout: 'member',
    beforeRouteLeave(to, from, next) {
        if (!this.$store.state.rootRock) {
            next()
            return
        }
        if (
            confirm(
                'ページを移動すると入力途中のデータが削除されますが、移動しますか？'
            )
        ) {
            next()
            this.$store.commit('setRootRock', false)
        } else {
            next(false)
        }
    },
    middleware({ redirect, route }) {
        if (!route.query.date) {
            redirect(
                `/member/report?date=${moment(new Date()).format('YYYY-MM-DD')}`
            )
        }
    },
}
</script>