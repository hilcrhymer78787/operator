<template>
    <v-card>
        <v-card-title>
            <span>日報</span>
            <v-spacer></v-spacer>
            <span>{{jaDate}}</span>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
            <v-form v-model="noError" ref="form" class="pt-5">
                <v-select color="main" v-model="form.guest" label="人数" :items="guestNums" dense></v-select>
                <ul>
                    <li v-for="(radio,index) in form.radios" :key="index">
                        <v-select color="main" v-model="radio.value" :label="radio.ttl" :items="nums" dense></v-select>
                    </li>
                </ul>
                <v-textarea v-model="form.goal" :rules="[(v) => !!v || '本日の目標を入力してください']" dense validate-on-blur label="本日の目標" outlined class="mt-3" height="65px"></v-textarea>
                <v-textarea v-model="form.content" :rules="[(v) => !!v || '本日の反省を入力してください']" dense validate-on-blur label="本日の反省" outlined></v-textarea>
            </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn @click="createReport()" :loading="createReportLoading" dark color="main">送信</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import moment from 'moment'
import { mapState } from 'vuex'
export default {
    data() {
        return {
            createReportLoading: false,
            noError: false,
            form: {
                guest: '100名',
                goal: '',
                content: '',
                radios: [
                    {
                        ttl: 'プレート被り',
                        value: 0,
                        max: 5,
                    },
                    {
                        ttl: 'テーブル逃し',
                        value: 0,
                        max: 10,
                    },
                    {
                        ttl: 'お断り',
                        value: 0,
                        max: 5,
                    },
                ],
            },
        }
    },
    computed: {
        ...mapState(['loginInfo']),
        date() {
            return this.$route.query.date
        },
        jaDate() {
            moment.locale('ja', {
                weekdays: ['日', '月', '火', '水', '木', '金', '土'],
            })
            return moment(this.date).format('Y年M月D日（dddd）')
        },
        year() {
            return moment(this.date).format('YYYY')
        },
        month() {
            return moment(this.date).format('MM')
        },
        ticksLabels() {
            var output = []
            for (let i = 0; i <= 10; i++) {
                output.push(i.toString())
            }
            return output
        },
        guestNums() {
            var output = []
            for (let i = 0; i <= 30; i++) {
                output.push(`${i * 10}名`)
            }
            return output
        },
        nums() {
            var output = []
            for (let i = 0; i <= 10; i++) {
                output.push(i)
            }
            return output
        },
        lineReport() {
            return `
${this.jaDate}
出演者：${this.loginInfo.name}

人数：${this.form.guest}
${this.form.radios[0].ttl}：${this.form.radios[0].value}
${this.form.radios[1].ttl}：${this.form.radios[1].value}
${this.form.radios[2].ttl}：${this.form.radios[2].value}

【本日の目標】
${this.form.goal}

【本日の反省】
${this.form.content}
`
        },
    },
    methods: {
        createReport() {
            this.$refs.form.validate()
            if (!this.noError) {
                return
            }
            if (!confirm('日報を送信しますか？')) {
                return
            }
            this.createReportLoading = true
            var data = {
                name: this.loginInfo.name,
                lineGroupId: this.loginInfo.lineGroupId,
                date: this.date,
                content: this.lineReport,
            }
            this.$axios
                .post(`/api/report/create`, { data: data })
                .then((res) => {
                    console.log(res.data)
                })
                .catch((err) => {
                    console.log(err)
                })
                .finally(() => {
                    this.$store.commit('setRootRock', false)
                    this.$emit('readReport')
                    this.$store.dispatch('setLoginInfoByToken')
                    this.createReportLoading = false
                    alert('日報を送信しました')
                })
        },
    },
    watch: {
        lineReport() {
            this.$store.commit('setRootRock', true)
        },
    },
}
</script>


<style>
</style>