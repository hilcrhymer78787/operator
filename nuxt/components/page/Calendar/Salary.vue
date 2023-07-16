<template>
    <v-card>
        <PartsDatePager ttl="給与" :path="path" />
        <v-simple-table>
            <thead>
                <tr>
                    <th class="text-left">名前</th>
                    <th class="text-left">出勤回数</th>
                    <th class="text-left">給与</th>
                </tr>
            </thead>
            <tbody v-if="$root.layoutName == 'admin'">
                <tr v-for="user in users" :key="user.id">
                    <td>{{ user.name }}</td>
                    <td>{{user.sumNum}}</td>
                    <td>{{user.sumSalary}}</td>
                </tr>
            </tbody>
            <tbody v-if="$root.layoutName == 'member'">
                <tr>
                    <td>{{ myInfo.name }}</td>
                    <td>{{ myInfo.sumNum }}</td>
                    <td>{{ myInfo.sumSalary }}</td>
                </tr>
            </tbody>
        </v-simple-table>
    </v-card>
</template>
<script>
import { mapState } from 'vuex'
export default {
    props: ['works', 'path'],
    data() {
        return {}
    },
    computed: {
        ...mapState(['loginInfo']),
        myInfo() {
            return {
                name: this.loginInfo.name,
                sumNum: this.sumNum(this.loginInfo.id),
                sumSalary: this.sumSalary(this.loginInfo.id),
            }
        },
        users() {
            return this.loginInfo.admin.users
                .map((user) => {
                    return {
                        id: user.id,
                        name: user.name,
                        sumNum: this.sumNum(user.id),
                        sumSalary: this.sumSalary(user.id),
                    }
                })
                .sort((a, b) => b.sumNum - a.sumNum)
        },
    },
    methods: {
        sumSalary(userId) {
            return this.works.reduce((sum, work) => {
                return work.user_id == userId ? sum + work.salary : sum + 0
            }, 0)
        },
        sumNum(userId) {
            return this.works.filter((work) => work.user_id == userId).length
        },
    },
}
</script>

