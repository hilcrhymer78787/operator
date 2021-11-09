<template>
    <v-toolbar color="main" dark style="box-shadow:none;">
        <v-spacer></v-spacer>
        <div class="d-flex">
            <v-btn @click="onClickPrevMonth()" icon>
                <v-icon style="font-size:30px;">mdi-chevron-left</v-icon>
            </v-btn>
            <h1>{{ year }}年 {{ month }}月</h1>
            <v-btn @click="onClickNextMonth()" icon>
                <v-icon style="font-size:30px;">mdi-chevron-right</v-icon>
            </v-btn>
        </div>
        <v-spacer></v-spacer>
    </v-toolbar>
</template>

<script>
export default {
    props: ['path'],
    computed: {
        // ...mapState(['loginInfo', 'works']),
        year() {
            return this.$route.query.year
        },
        month() {
            return this.$route.query.month
        },
    },
    methods: {
        onClickPrevMonth() {
            if (this.month == 1) {
                this.$router.push(
                    `${this.path}/?year=${Number(this.year) - 1}&month=12`
                )
            } else {
                this.$router.push(
                    `${this.path}/?year=${this.year}&month=${
                        Number(this.month) - 1
                    }`
                )
            }
        },
        onClickNextMonth() {
            if (this.month == 12) {
                this.$router.push(
                    `${this.path}/?year=${Number(this.year) + 1}&month=1`
                )
            } else {
                this.$router.push(
                    `${this.path}/?year=${this.year}&month=${
                        Number(this.month) + 1
                    }`
                )
            }
        },
    },
}
</script>

<style lang="scss" scoped>
h1 {
    width: 183px;
    text-align: center;
    font-weight: normal;
}
</style>