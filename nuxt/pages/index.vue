<template>
    <div></div>
</template>
<script>
export default {
    middleware({ redirect }) {
        redirect(
            `/admin/?year=${new Date().getFullYear()}&month=${
                new Date().getMonth() + 1
            }`
        )
    },
}
</script>