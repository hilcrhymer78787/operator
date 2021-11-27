<template>
    <v-card>
        <PageCreateAnswer mode="member" path="/member/question"/>
    </v-card>
</template>


<script>
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
}
</script>