<template>
    <v-simple-table>
        <!-- <thead>
            <tr>
                <th class="text-left">id</th>
                <th class="text-left">Name</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="task in tasks" :key="task.id">
                <td>{{ task.id }}</td>
                <td>{{ task.name }}</td>
                <td>
                    <div class="d-flex justify-end">
                        <v-btn dark @click="dialogOpen(task.id,task.name)" color="orange lighten-1" class="pa-0" style="margin-right:6px;">edit</v-btn>
                        <v-btn dark @click="deleteTask(task.id,task.name)" color="error lighten-1" class="pa-0">delete</v-btn>
                    </div>
                </td>
            </tr>
        </tbody> -->
    </v-simple-table>
</template>

<script>
export default {
    layout: 'admin',
}
</script>

<style>
</style>