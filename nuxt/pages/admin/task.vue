<template>
    <div>
        <v-card>
            <PartsDatePager ttl="タスク" path="/admin/task" />
            <v-divider></v-divider>
            <v-card-text class="pa-0">
                <v-simple-table>
                    <thead>
                        <tr>
                            <th class="px-1 py-1 text-center">名前</th>
                            <th class="px-1 py-1 text-center">日報</th>
                            <th class="px-1 py-1 text-center">
                                <div class="d-flex align-center justify-center" :class="{'flex-column':$vuetify.breakpoint.xs}">
                                    確認表
                                    <v-btn @click="bulkChange(1)" v-if="editMode" elevation="0" outlined small class="pa-1" :class="{'ml-2':!$vuetify.breakpoint.xs}" color="sub">全て未完了</v-btn>
                                </div>
                            </th>
                            <th class="px-1 py-1 text-center">
                                <div class="d-flex align-center justify-center" :class="{'flex-column':$vuetify.breakpoint.xs}">
                                    シフト確認
                                    <v-btn @click="bulkChange(2)" v-if="editMode" elevation="0" outlined small class="pa-1" :class="{'ml-2':!$vuetify.breakpoint.xs}" color="sub">全て未完了</v-btn>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in taskUsers" :key="user.id">
                            <td>{{ user.name }}</td>
                            <td :class="{notyet:user.notSubmittedReportNum}">{{ user.notSubmittedReportNum }}</td>
                            <td v-for="n in 2" :key="n" class="color" :class="{notyet:user[`type${n}_state`] == 1,finish:user[`type${n}_state`] == 2}">
                                <v-select :readonly="!editMode" v-model="user[`type${n}_state`]" hide-details dense :items="$TASK_STATE" item-value="val" item-text="txt"></v-select>
                            </td>
                        </tr>
                    </tbody>
                </v-simple-table>
                <div v-if="getTasksLoading && !taskUsers.length" class="py-5 text-center">
                    <v-progress-circular indeterminate color="main"></v-progress-circular>
                </div>
            </v-card-text>
            <v-divider></v-divider>
            <v-card-actions>
                <!-- <v-btn v-if="editMode" :loading="deleteTaskLoading" @click="deleteTask()" dark color="error" class="py-1">
                    delete
                </v-btn> -->
                <v-btn v-if="editMode" @click="bulkDialog = true" dark color="sub">一括入力</v-btn>
                <v-spacer></v-spacer>
                <v-btn v-if="!editMode" @click="editMode = true" dark color="sub" class="py-1">
                    <v-icon>mdi-pencil</v-icon>
                </v-btn>
                <v-btn v-if="editMode" @click="onCloseEdit()" class="py-1">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-btn v-if="editMode" :loading="createTaskLoading" @click="createTask()" dark color="main" class="py-1">登録</v-btn>
            </v-card-actions>
        </v-card>

        <v-dialog max-width="476px" v-model="bulkDialog" width="500">
            <v-card>
                <v-card-title>一括入力</v-card-title>
                <v-card-text class="pa-5">
                    <v-btn @click="bulkInput(null)" class="d-block mb-3">全て「不要」にする</v-btn>
                    <v-btn @click="bulkInput(1)" class="d-block mb-3">全て「未完了」にする</v-btn>
                    <v-btn @click="bulkInput(2)" class="d-block">全て「完了」にする</v-btn>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn @click="bulkDialog = false"><v-icon>mdi-close</v-icon></v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
    layout: 'admin',
    data() {
        return {
            bulkDialog: false,
            getTasksLoading: false,
            createTaskLoading: false,
            deleteTaskLoading: false,
            editMode: false,
            oldTaskUsers: [],
            taskUsers: [],
            items: [],
        }
    },
    computed: {
        ...mapState(['loginInfo']),
        year() {
            return this.$route.query.year
        },
        month() {
            return this.$route.query.month
        },
    },
    methods: {
        bulkChange(n) {
            this.taskUsers.forEach((user) => {
                user[`type${n}_state`] = 1
            })
        },
        async bulkChange(n) {
            this.getWorksLoading = true
            await this.$axios
                .get(`/api/work/read?year=${this.year}&month=${this.month}`)
                .then((res) => {
                    const works = res.data
                    const workedUserIds = works
                        .reduce((work, current) => {
                            const duplicateIndex = work.findIndex(
                                (item) => item.user_id === current.user_id
                            )
                            if (duplicateIndex === -1) {
                                work.push(current)
                            }
                            return work
                        }, [])
                        .map((work) => work.user_id)
                    console.log(workedUserIds,'workedUserIds')
                    this.taskUsers.forEach((user) => {
                        if (!workedUserIds.find((id) => id === user.id))
                            return
                        user[`type${n}_state`] = 1
                    })
                })
                .finally(() => {
                    this.getWorksLoading = false
                })
        },
        async getTasks() {
            this.getTasksLoading = true
            await this.$axios
                .get(`/api/task/read?year=${this.year}&month=${this.month}`)
                .then((res) => {
                    this.oldTaskUsers = res.data
                    this.taskUsers = []
                    this.oldTaskUsers.forEach((user) => {
                        let obj = {}
                        this.$set(obj, 'id', user.id)
                        this.$set(obj, 'name', user.name)
                        this.$set(obj, 'type1_state', user.type1_state)
                        this.$set(obj, 'type2_state', user.type2_state)
                        this.$set(obj, 'type3_state', user.type3_state)
                        this.$set(obj, 'type4_state', user.type4_state)
                        this.$set(
                            obj,
                            'notSubmittedReportNum',
                            user.notSubmittedReportNum
                        )
                        this.taskUsers.push(obj)
                    })
                })
                .catch((err) => {
                    console.log(err.response)
                })
                .finally(() => {
                    this.getTasksLoading = false
                })
        },
        onCloseEdit() {
            this.taskUsers = []
            this.oldTaskUsers.forEach((user) => {
                let obj = {}
                this.$set(obj, 'id', user.id)
                this.$set(obj, 'name', user.name)
                this.$set(obj, 'type1_state', user.type1_state)
                this.$set(obj, 'type2_state', user.type2_state)
                this.$set(obj, 'type3_state', user.type3_state)
                this.$set(obj, 'type4_state', user.type4_state)
                this.$set(
                    obj,
                    'notSubmittedReportNum',
                    user.notSubmittedReportNum
                )
                this.taskUsers.push(obj)
            })
            this.editMode = false
        },
        async createTask() {
            this.createTaskLoading = true
            await this.$axios
                .post(
                    `/api/task/create?year=${this.year}&month=${this.month}`,
                    { users: this.taskUsers }
                )
                .then((res) => {
                    this.getTasks()
                    this.$store.dispatch('setLoginInfoByToken')
                    this.editMode = false
                })
                .catch(() => {
                    alert('エラー')
                })
                .finally(() => {
                    this.createTaskLoading = false
                })
        },
        async deleteTask() {
            this.deleteTaskLoading = true
            await this.$axios
                .delete(
                    `/api/task/delete?year=${this.year}&month=${this.month}`
                )
                .then((res) => {
                    this.getTasks()
                    this.editMode = false
                })
                .catch(() => {
                    alert('エラー')
                })
                .finally(() => {
                    this.deleteTaskLoading = false
                })
        },
        bulkInput(value) {
            this.taskUsers.forEach((user) => {
                for (let n = 1; n <= 2; n++) {
                    user[`type${n}_state`] = value
                }
            })
            this.bulkDialog = false
        },
    },
    mounted() {
        this.getTasks()
    },
    watch: {
        $route() {
            this.getTasks()
        },
    },
}
</script>

<style lang="scss" scoped>
td,
th {
    border-right: thin solid rgba(0, 0, 0, 0.12);
    &:last-child {
        border-right: none;
    }
}
.v-card__title {
    display: block;
    font-size: 15px;
    line-height: 25px;
}
.finish {
    color: #4caf50;
}
.notyet {
    color: #ff5252;
}
::v-deep {
    .v-select__selections {
        font-size: 12px !important;
    }
    .finish {
        .v-select__selection {
            color: #4caf50 !important;
        }
    }
    .notyet {
        .v-select__selections {
            color: #ff5252 !important;
        }
    }
}
.v-input--is-readonly {
    ::v-deep {
        .v-input {
            &__append-inner {
                display: none;
            }
            &__slot {
                &:before,
                &:after {
                    display: none !important;
                }
            }
        }
    }
}
</style>
