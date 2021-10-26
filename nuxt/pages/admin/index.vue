<template>
    <PageCalendar path="/admin" />
</template>
<script>
export default {
    layout: 'admin',
    middleware({ redirect, route }) {
        if (!route.query.year || !route.query.month) {
            redirect(
                `/admin/?year=${new Date().getFullYear()}&month=${
                    new Date().getMonth() + 1
                }`
            )
        }
    },
}
</script>