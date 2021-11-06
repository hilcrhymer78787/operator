<template>
    <div></div>
</template>
<script>
export default {
    middleware({ redirect }) {
        redirect(
            `/member/?year=${new Date().getFullYear()}&month=${
                new Date().getMonth() + 1
            }`
        )
    },
}
</script>