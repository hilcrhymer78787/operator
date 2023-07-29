<template>
    <v-expansion-panels class="mt-3" v-if="$root.layoutName == 'admin'">
        <v-expansion-panel>
            <v-expansion-panel-header>
                シフト一覧を表示
            </v-expansion-panel-header>
            <v-expansion-panel-content>
                <table>
                    <tbody v-for="(calendar,index) in copyCalendars" :key="index">
                        <tr>
                            <td>{{ calendar.date }}</td>
                        </tr>
                    </tbody>
                </table>
                <v-divider></v-divider>
                <v-simple-table>
                    <thead>
                        <tr>
                            <th>売上</th>
                            <th>人件費</th>
                            <th class="textsub">収益</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{sold}}</td>
                            <td>{{laborCost}}</td>
                            <td class="textsub">{{profit}}</td>
                        </tr>
                    </tbody>
                </v-simple-table>
            </v-expansion-panel-content>
        </v-expansion-panel>
    </v-expansion-panels>
</template>
<script>
import moment from 'moment'
export default {
    props: ['filteredCalendars'],
    data() {
        return {}
    },
    computed: {
        copyCalendars() {
            return this.filteredCalendars.map((calendar) => {
                return { date: moment(calendar.date).format('D') }
            })
        },
        // 売上
        sold() {
            const unitPrice = 11000
            return this.filteredCalendars.length * unitPrice
        },
        // 人件費
        laborCost() {
            let laborCost = 0
            this.filteredCalendars.forEach((calendar) => {
                calendar.works.forEach((work) => {
                    laborCost += work.salary
                })
            })
            return laborCost
        },
        // 収益
        profit() {
            return this.sold - this.laborCost
        },
    },
}
</script>

