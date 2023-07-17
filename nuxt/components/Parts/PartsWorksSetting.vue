<template>
    <v-card>
        <v-card-title color="main" dark>
            <span>固定シフトの設定</span>
            <v-spacer></v-spacer>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text class="pa-0">
            <div v-if="getLoading" class="text-center pa-5 ma-5">
                <v-progress-circular indeterminate color="main"></v-progress-circular>
            </div>
            <v-simple-table v-else-if="shifts" class="table">
                <thead>
                    <tr class="indent">
                        <th class="text-left"></th>
                        <th v-for="day in week" :key="day" class="indent_item">{{day}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="n in 5" :key="n">
                        <td class="ttl">第{{n}}</td>
                        <td v-for="key in keys" :key="key">
                            <span class="user_name" v-if="!editable">{{getUserName(shifts[n][key])}}</span>
                            <v-select v-else :readonly="!editable" :items="selectItems" v-model="shifts[n][key]" item-value="val" item-text="txt" dense></v-select>
                        </td>
                    </tr>
                </tbody>
            </v-simple-table>
            <div v-if="$root.layoutName == 'admin'">
                <v-divider></v-divider>
                <v-expansion-panels flat>
                    <v-expansion-panel>
                        <v-expansion-panel-header>
                            ウエイトを表示
                        </v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <v-simple-table class="borderTable">
                                <tbody>
                                    <tr v-for="(user,index) in users" :key="index">
                                        <td>{{ user.name }}</td>
                                        <td>{{ user.weight }}</td>
                                    </tr>
                                </tbody>
                            </v-simple-table>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-expansion-panels>
            </div>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
            <v-btn v-if="$root.layoutName == 'admin' && !editable" color="error" :loading="saveAllShiftLoading" dark @click="saveAllShift()">一括入力</v-btn>
            <v-spacer></v-spacer>
            <v-btn @click="$emit('onCloseDialog')">
                <v-icon>mdi-close</v-icon>
            </v-btn>
            <v-btn v-if="$root.layoutName == 'admin' && !editable" color="sub" dark @click="editable = true">編集</v-btn>
            <v-btn v-if="$root.layoutName == 'admin' && editable" color="main" :loading="saveLoading" dark @click="saveShift()">登録</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import { mapState } from 'vuex'
export default {
    data() {
        return {
            editable: false,
            saveLoading: false,
            saveAllShiftLoading: false,
            getLoading: false,
            week: ['日', '月', '火', '水', '木', '金', '土'],
            keys: ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'],
            shifts: null,
        }
    },
    computed: {
        ...mapState(['loginInfo']),
        selectItems() {
            return [
                { val: 0, txt: '' },
                ...this.loginInfo.users.map((user) => {
                    return { val: user.id, txt: user.name }
                }),
            ]
        },
        users() {
            return this.loginInfo.users
                .map((user) => {
                    return {
                        ...user,
                        weight: this.getWeight(user.id),
                    }
                })
                .sort((a, b) => b.weight - a.weight)
        },
    },
    methods: {
        getWeight(userId) {
            if (!this.shifts) return
            let count = 0
            this.keys.forEach((key) => {
                for (let n = 1; n <= 5; n++) {
                    if (this.shifts[n][key] === userId) {
                        const addCount = n === 5 ? 0.35 : 1
                        count = count + addCount
                    }
                }
            })
            return count.toFixed(2)
        },
        getUserName(id) {
            const result = this.loginInfo.users.find((user) => user.id == id)
            return result?.name ?? null
        },
        getShift() {
            this.getLoading = true
            this.$axios
                .get(`/api/shift/read`)
                .then((res) => {
                    this.shifts = res.data
                })
                .finally(() => {
                    this.getLoading = false
                })
        },
        saveShift() {
            this.saveLoading = true
            this.$axios
                .post(`/api/shift/create`, { shifts: this.shifts })
                .then(() => {
                    this.getShift()
                    this.editable = false
                })
                .finally(() => {
                    this.saveLoading = false
                })
        },
        saveAllShift() {
            if (
                !confirm(
                    `${this.$route.query.year}年${this.$route.query.month}月のシフトデータを一括入力します。該当月のデータは全て上書きされますが、よろしいですか？`
                )
            )
                return
            if (
                !confirm(
                    `この作業はやり直しできませんが、本当によろしいですか？`
                )
            )
                return
            this.saveAllShiftLoading = true
            this.$axios
                .post(`/api/work/all`, {
                    year: this.$route.query.year,
                    month: this.$route.query.month,
                })
                .then((res) => {
                    console.log(res.data)
                    this.$emit('onCloseDialog', true)
                })
                .catch((err) => {
                    console.log(err.response)
                })
                .finally(() => {
                    this.saveAllShiftLoading = false
                })
        },
    },
    mounted() {
        this.getShift()
    },
}
</script>

<style lang="scss" scoped>
.table {
    th,
    td {
        border-right: 1px solid rgba(0, 0, 0, 0.12) !important;
        text-align: center !important;
        padding: 5px !important;
        white-space: nowrap;
        &.ttl {
            padding: 10px !important;
        }
        .user_name {
            font-size: 14px;
        }
    }
    .indent {
        &_item {
            &:nth-child(2) {
                color: #ff5252 !important;
            }
            &:nth-child(8) {
                color: #2196f3 !important;
            }
        }
    }
}
.borderTable {
    border: 1px solid rgba(0, 0, 0, 0.12) !important;
}
</style>