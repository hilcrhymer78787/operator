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
                <tr v-for="user in loginInfo.users" :key="user.id">
                    <td>{{ user.name }}</td>
                    <td>{{sumNum(user.id)}}</td>
                    <td>{{sumSalary(user.id)}}</td>
                </tr>
            </tbody>
            <tbody v-if="$root.layoutName == 'member'">
                <tr>
                    <td>{{ loginInfo.name }}</td>
                    <td>{{sumNum(loginInfo.id)}}</td>
                    <td>{{sumSalary(loginInfo.id)}}</td>
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

