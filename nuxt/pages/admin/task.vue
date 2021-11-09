<template>
    <v-card>
        <PartsDatePager path="/admin/task"/>
        <v-divider></v-divider>
        <v-card-text class="pa-0">
            <v-simple-table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>num</th>
                        <th>score</th>
                        <th>test</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in loginInfo.users" :key="user.id">
                        <td>{{ user.name }}</td>
                        <td>
                            <v-select hide-details dense :items="users"></v-select>
                        </td>
                        <td>
                            <v-select hide-details dense :items="users"></v-select>
                        </td>
                        <td>
                            <v-select hide-details dense :items="users"></v-select>
                        </td>
                        <!-- <td>{{ user.score }}</td> -->
                    </tr>
                </tbody>
            </v-simple-table>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn>
                <v-icon>mdi-chart-line</v-icon>
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import { mapState } from 'vuex'
export default {
    layout: 'admin',
    data() {
        return {
            users: ['あああ', 'いいい', 'ううう', 'えええ'],
            desserts: [
                {
                    name: 'Name1',
                    num: 159,
                    score: 9000,
                },
                {
                    name: 'Name2',
                    num: 237,
                    score: 9000,
                },
                {
                    name: 'Name3',
                    num: 262,
                    score: 9000,
                },
            ],
        }
    },
    computed: {
        ...mapState(['loginInfo']),
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
::v-deep {
    .v-select__selections {
        font-size: 10px !important;
    }
    .v-input__append-inner {
        // display: none;
    }
}
</style>
