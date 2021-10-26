<template>
    <PageCalendar path="/member" />
</template>
<script>
export default {
    layout: 'member',
    middleware({ redirect, route }) {
        if (!route.query.year || !route.query.month) {
            redirect(
                `/member/?year=${new Date().getFullYear()}&month=${
                    new Date().getMonth() + 1
                }`
            )
        }
    },
}
</script>