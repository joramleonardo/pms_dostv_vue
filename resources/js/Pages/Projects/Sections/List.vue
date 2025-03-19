<script setup>
    import 'preline/dist/preline';

    import { ref, computed, onMounted, onBeforeUnmount   } from 'vue';
    import { usePage, router } from '@inertiajs/vue3';

    import ViewSingleProject from '@/Pages/Projects/ViewSingleProject.vue';
    import Modal from '@/Components/Modal.vue';

    const tasks = computed(() => usePage().props.tasks || []);
    const openSubmenus = ref({});
    const subtaskModal = ref(false);
    const subtasks = ref({});
    const openDropdown = ref(null);


    const newSubtask = ref({
        task_id: null,
        name: '',
        description: '',
        assignee_id: null,
        status: 'pending'
    });

    const statusOptions = [
        { value: 'pending', label: 'Pending', class: 'bg-blue-500 text-white' },
        { value: 'in_progress', label: 'In Progress', class: 'bg-orange-500 text-white' },
        { value: 'completed', label: 'Done', class: 'bg-green-500 text-white' },
    ]

    const toggleDropdown = (subtaskId) => {
        openDropdown.value = openDropdown.value === subtaskId ? null : subtaskId;
    };

    const closeDropdown = (event) => {
        if (!event.target.closest(".relative")) {
            openDropdown.value = null;
        }
    };

    onMounted(() => {
        fetchSubtaskCounts();
        document.addEventListener("click", closeDropdown);
    });

    onBeforeUnmount(() => {
        document.removeEventListener("click", closeDropdown);
    });

    const updateSubtaskStatus = async (subtask, newStatus) => {
        router.put(`/subtasks/${subtask.id}/update-status`, { status: newStatus }, {
            onSuccess: (page) => {
                subtask.status = newStatus;
                openDropdown.value = null;
                alert("Subtask status updated!");

                fetchSubtaskCounts();
            },
            onError: (error) => {
                console.error("Error updating subtask status:", error);
                alert("Failed to update subtask status.");
            }
        });
    };




    const toggleSubmenu = async (taskId) => {
        openSubmenus.value[taskId] = !openSubmenus.value[taskId];

        if (openSubmenus.value[taskId]) {
            try {
                const response = await fetch(`/tasks/${taskId}/subtasks`);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const data = await response.json();
                subtasks.value[taskId] = data;
            } catch (error) {
                console.error("Error fetching subtasks:", error);
            }
        }
    };

    const deleteTask = (id) => {
        if (confirm("Are you sure you want to delete this task?")) {
            router.delete(`/tasks/${id}`, {
                onSuccess: () => {
                    alert("Task deleted successfully!");
                }
            });
        }
    };

    const openSubtaskModal = (taskId) => {
        newSubtask.value.task_id = taskId;
        subtaskModal.value = true;
    };

    const closeSubtaskModal = () => {
        subtaskModal.value = false;

    };

    const saveSubtask = async () => {
        router.post('/subtasks', newSubtask.value, {
            onSuccess: async () => {
                alert('Subtask added successfully!');
                closeSubtaskModal();

                fetchSubtaskCounts();

                const response = await fetch(`/tasks/${newSubtask.value.task_id}/subtasks`);
                const data = await response.json();
                subtasks.value[newSubtask.value.task_id] = data;

                newSubtask.value = {
                    task_id: null,
                    name: '',
                    description: '',
                    assignee_id: null
                };

            }
        });
    };

    const taskSubtaskCounts = ref({}); // Store total and completed subtasks per task

    // Fetch subtasks count when page loads
    const fetchSubtaskCounts = async () => {
        try {
            const response = await fetch('/tasks/subtask-counts'); // API Endpoint for subtask counts
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            taskSubtaskCounts.value = await response.json(); // Store the count in state
        } catch (error) {
            console.error("Error fetching subtask counts:", error);
        }
    };


</script>

<template>

    <ViewSingleProject :project="usePage().props.project">
        <div class="w-full lg:ps-64">
            <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
                <div class="mx-auto sm:px-6 lg:px-8">

                    <div class="flex flex-col">
                        <div class="overflow-x-auto">
                            <div class="min-w-full inline-block align-middle">
                                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">

                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                                        <thead class="bg-gray-50 dark:bg-neutral-800">
                                            <tr>
                                                <th class="px-4 py-3 text-start text-xs font-semibold uppercase">Task Name</th>
                                                <th class="px-4 py-3 text-start text-xs font-semibold uppercase">Assignee</th>
                                                <th class="px-4 py-3 text-start text-xs font-semibold uppercase">Status</th>
                                                <th class="px-4 py-3 text-start text-xs font-semibold uppercase">Subtask</th>
                                                <th class="px-4 py-3 text-start text-xs font-semibold uppercase">Due Date</th>
                                                <th class="px-4 py-3 text-start text-xs font-semibold uppercase">Category</th>
                                                <th class="px-4 py-3 text-start text-xs font-semibold uppercase">Sub-Category</th>
                                                <th class="px-4 py-3 text-center text-xs font-semibold uppercase">Actions</th>
                                            </tr>
                                        </thead>

                                        <tbody class="divide-y divide-gray-200 dark:divide-neutral-700" v-for="task in tasks" :key="task.id" >
                                            <tr class="bg-white hover:bg-gray-50 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                                                <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">
                                                    {{ task.task_name }}
                                                    <button @click="toggleSubmenu(task.id)"
                                                            class="ml-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                                        <svg :class="{'rotate-180': openSubmenus[task.id]}"
                                                             class="w-5 h-5 transition-transform"
                                                             fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                             xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                    </button>
                                                </td>

                                                <td class="px-4 py-2">
                                                    <div class="flex -space-x-2">
                                                        <div v-for="assignee in task.assignees"
                                                            :key="assignee.id"
                                                            class="relative inline-flex items-center justify-center"
                                                            data-hs-tooltip="true"
                                                            :title="assignee.name">

                                                            <img class="inline-block size-6 rounded-full ring-2 ring-white dark:ring-neutral-900"
                                                                :src="'https://ui-avatars.com/api/?name=' + assignee.name + '&background=random'"
                                                                :alt="assignee.name">
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="px-4 py-2">
                                                    <span :class="{
                                                        'bg-green-100 text-green-800': task.task_status === 'completed',
                                                        'bg-yellow-100 text-yellow-800': task.task_status === 'in_progress',
                                                        'bg-gray-100 text-gray-800': task.task_status === 'new'
                                                    }" class="px-2 py-1 text-xs font-semibold rounded-lg">
                                                        {{ task.task_status.replace('_', ' ').toUpperCase() }}
                                                    </span>
                                                </td>

                                                <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">
                                                    {{ taskSubtaskCounts[task.id]?.completed || 0 }} / {{ taskSubtaskCounts[task.id]?.total || 0 }}
                                                </td>
                                                <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">{{ task.task_due_date }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">{{ task.task_category }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">{{ task.task_sub_category }}</td>

                                                <td class="px-4 py-2 text-center">
                                                    <button class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                                        Edit
                                                    </button>

                                                    <button @click="deleteTask(task.id)"
                                                                class="ml-2 px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>

                                            <tr v-if="openSubmenus[task.id]" class="bg-gray-100 dark:bg-neutral-800">
                                                <td colspan="7" class="px-4 py-3 text-sm text-gray-700 dark:text-white">
                                                    <div v-if="subtasks[task.id] && subtasks[task.id].length">
                                                        <h3 class="font-semibold">Subtasks:</h3>
                                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700 rounded-md mt-2">
                                                            <thead class="bg-gray-50 dark:bg-neutral-800">
                                                                <tr>
                                                                    <th class="px-4 py-3 text-start text-xs font-semibold uppercase">Subtask Name</th>
                                                                    <th class="px-4 py-3 text-start text-xs font-semibold uppercase">Assignee</th>
                                                                    <th class="px-4 py-3 text-start text-xs font-semibold uppercase">Status</th>
                                                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                                                <tr v-for="subtask in subtasks[task.id]" :key="subtask.id" class="bg-white hover:bg-gray-50 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                                                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">{{ subtask.name }}</td>
                                                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">{{ subtask.assignee?.name || 'Unassigned' }}</td>
                                                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-white text-center">
                                                                        <div class="relative  mx-auto">
                                                                            <button @click="toggleDropdown(subtask.id)"
                                                                                :class="{
                                                                                    'bg-blue-500 text-white hover:bg-blue-400': subtask.status === 'pending',
                                                                                    'bg-orange-500 text-white hover:bg-orange-400': subtask.status === 'in_progress',
                                                                                    'bg-green-500 text-white hover:bg-green-400': subtask.status === 'completed'
                                                                                }"
                                                                                class="px-3 py-2 text-xs font-semibold rounded-lg w-full text-center focus:outline-none"
                                                                            >
                                                                                {{ subtask.status.replace('_', ' ').toUpperCase() }}
                                                                            </button>
                                                                            <div v-if="openDropdown === subtask.id" ref="dropdownMenu"
                                                                                class="absolute left-1/2 transform -translate-x-1/2 mt-2 bg-white shadow-md border rounded-lg z-10 w-40"
                                                                                @click.stop>
                                                                                <ul>
                                                                                    <li v-for="option in statusOptions"
                                                                                        :key="option.value"
                                                                                        @click="updateSubtaskStatus(subtask, option.value)"
                                                                                        :class="option.class"
                                                                                        class="cursor-pointer px-4 py-2 hover:opacity-75 text-center"
                                                                                    >
                                                                                        {{ option.label }}
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-4 py-2 text-center">
                                                                        <button class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                                                            Edit
                                                                        </button>

                                                                        <button class="ml-2 px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                                                            Delete
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <button @click="openSubtaskModal(task.id)" class="mt-3 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                                        Add Subtask
                                                    </button>

                                                    <Modal :show="subtaskModal" @close="closeSubtaskModal">
                                                        <div class="p-6 ">
                                                            <h2 class="text-lg font-medium text-gray-900">Add Subtask</h2>
                                                            <input v-model="newSubtask.name" type="text" placeholder="Subtask Name" class="border p-2 rounded w-full" />
                                                            <textarea v-model="newSubtask.description" placeholder="Description" class="border p-2 rounded w-full mt-2"></textarea>
                                                            <button @click="saveSubtask" class="px-4 py-2 bg-blue-500 text-white rounded mt-4">Save</button>
                                                        </div>
                                                    </Modal>
                                                </td>
                                            </tr>



                                        </tbody>
                                    </table>

                                    <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-700">
                                        <p class="text-sm text-gray-600 dark:text-neutral-400">
                                            Showing {{ tasks.length }} tasks
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ViewSingleProject>
</template>
