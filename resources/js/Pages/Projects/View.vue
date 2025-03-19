<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>


        <!-- Content -->
        <div class="w-full lg:ps-64">
            <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <!-- Header -->
                        <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700">
                            <div>
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                                Projects
                            </h2>
                            <!-- <p class="text-sm text-gray-600 dark:text-neutral-400">
                                Keys you have generated to connect with third-party clients or access the <a class="inline-flex items-center gap-x-1.5 text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500" href="#">Preline API.</a>
                            </p> -->
                            </div>
                            <div class="inline-flex gap-x-2">
                                <CreateProject />
                            </div>
                        </div>
                        <!-- End Header -->
                        <div class="p-6 text-gray-900">
                            <div class="flex flex-col">
                                <div class="-m-1.5 overflow-x-auto">
                                    <div class="p-1.5 min-w-full inline-block align-middle">
                                        <div class="overflow-hidden">
                                        <!-- <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700"> -->
                                            <table class="min-w-full border border-gray-300">
                                                <!-- Table Header -->
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th v-for="(column, index) in tableHeaders" :key="index" class="px-4 py-2 text-left border-b">
                                                        {{ column }}
                                                        </th>
                                                        <!-- Add Action Column -->
                                                        <th class="px-4 py-2 text-left border-b">Actions</th>
                                                    </tr>
                                                </thead>

                                                <!-- Table Body -->
                                                <tbody>
                                                    <tr v-for="(row, rowIndex) in tableData" :key="rowIndex">
                                                        <td v-for="(value, colIndex) in row" :key="colIndex" class="px-4 py-2 border-b">
                                                        {{ value }}
                                                        </td>
                                                        <!-- Action Buttons -->
                                                        <td class="px-4 py-2 border-b">
                                                            <button @click="editRow(rowIndex)" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 mr-2">
                                                                Edit
                                                            </button>
                                                            <button @click="addRow()" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                                                Add
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Content -->
    </AuthenticatedLayout>
</template>

<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import CreateProject from '@/Pages/Projects/Create.vue';

    import {  Head, Link, useForm  } from '@inertiajs/vue3';
    import { ref, onMounted } from 'vue';

    // Define table headers dynamically
    const tableHeaders = ref([
        "ID", "Project Name", "Category", "Description"
    ]);

    // Sample table data
    const tableData = ref([
        [1, "John Doe", "john@example.com", "Admin"],
        [2, "Jane Smith", "jane@example.com", "User"],
        [3, "Alice Brown", "alice@example.com", "Editor"],
        ]);

        // Function to handle Edit button click
        const editRow = (index) => {
        alert(`Editing row: ${index + 1}`);
        // You can add logic to open a modal or navigate to an edit page
        };

        // Function to handle Add button click
        const addRow = () => {
        const newId = tableData.value.length + 1;
        tableData.value.push([newId, "New User", "new@example.com", "Guest"]);
        };
</script>
