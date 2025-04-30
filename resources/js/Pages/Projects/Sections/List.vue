<script setup>
    import 'preline/dist/preline';

    import { ref, computed, onMounted, onBeforeUnmount   } from 'vue';
    import { usePage, router } from '@inertiajs/vue3';

    import ViewSingleProject from '@/Pages/Projects/ViewSingleProject.vue';
    import Modal from '@/Components/Modal.vue';

    const newSubtaskRow = ref({}); // Tracks active input per task

    const tasks = computed(() => usePage().props.tasks || []);
    const openSubmenus = ref({});
    const subtaskModal = ref(false);
    const subtasks = ref({});
    const openDropdown = ref(null);

    const page = usePage(); // Access page props
    const authUser = computed(() => page.props.auth.user); // Get logged-in user


    const isDropdownOpen = ref(false);
    const newSubtask = ref({
        task_id: null,
        name: '',
        description: '',
        assignee_id: [],
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

    // Fetch Users for Assignee Dropdown
    const users = ref([]);
    const users_prod = ref([]);
    const users_assoc = ref([]);
    const users_editing = ref([]);


    onMounted(async () => {
        try {
            const response = await fetch('/tasks-get-users');
            const data = await response.json();
            users.value = data.filter(user => user.role);

            const response1 = await fetch('/tasks-get-prod-users');
            const data1 = await response1.json();
            users_prod.value = data1.filter(user => user.role);

            const response2 = await fetch('/tasks-get-editing-users');
            const data2 = await response2.json();
            users_editing.value = data2.filter(user => user.role);

            const response3 = await fetch('/tasks-get-assoc-users');
            const data3 = await response3.json();
            users_assoc.value = data3.filter(user => user.role);

        } catch (error) {
            console.error("Error fetching users:", error);
        }

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

    const showInlineSubtaskInput = (taskId) => {
    newSubtask.value = {
        task_id: taskId,
        name: '',
        description: '',
        assignee_id: [],
        status: 'pending',
    };
    newSubtaskRow.value[taskId] = true;
};

const cancelInlineSubtask = (taskId) => {
    newSubtaskRow.value[taskId] = false;
    newSubtask.value = {};
};

const submitInlineSubtask = async (taskId) => {
    if (!newSubtask.value.name.trim()) return alert("Name required");

    router.post('/subtasks', newSubtask.value, {
        onSuccess: async () => {
            const response = await fetch(`/tasks/${taskId}/subtasks`);
            const data = await response.json();
            subtasks.value[taskId] = data;
            newSubtaskRow.value[taskId] = false;
            newSubtask.value = {};
            fetchSubtaskCounts();
        },
        onError: () => alert("Failed to save subtask."),
    });
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
                <div
                  class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-900 dark:border-neutral-700"
                >
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
                        <th v-if="authUser?.role === 'Program Manager'" class="px-4 py-3 text-center text-xs font-semibold uppercase">Actions</th>
                      </tr>
                    </thead>
                    <tbody v-for="task in tasks" :key="task.id" class="divide-y divide-gray-200 dark:divide-neutral-700">
                      <!-- Main task row -->
                      <tr class="bg-white hover:bg-gray-50 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                        <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">
                          {{ task.task_name }}
                          <button @click="toggleSubmenu(task.id)" class="ml-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <svg :class="{ 'rotate-180': openSubmenus[task.id] }" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                          </button>
                        </td>
                        <td class="px-4 py-2">
                          <div class="flex -space-x-2">
                            <div v-for="assignee in task.assignees" :key="assignee.id" class="relative inline-flex items-center justify-center" :title="assignee.name">
                              <img :src="'https://ui-avatars.com/api/?name=' + assignee.name + '&background=random'" :alt="assignee.name" class="inline-block size-6 rounded-full ring-2 ring-white dark:ring-neutral-900" />
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
                        <td v-if="['Program Manager', 'Editing Supervisor', 'Assoc Producer'].includes(authUser.role)" class="px-4 py-2 text-center">
                          <button class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 opacity-50 cursor-not-allowed">Edit</button>
                          <button class="ml-2 px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 opacity-50 cursor-not-allowed">Delete</button>
                        </td>
                      </tr>

                      <!-- Subtasks list row -->
                      <tr v-if="openSubmenus[task.id]" class="bg-gray-100 dark:bg-neutral-800">
                        <td colspan="8" class="px-4 py-3 text-sm text-gray-700 dark:text-white">
                          <h3 class="font-semibold mb-2">Subtasks:</h3>
                          <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700 rounded-md">
                            <thead class="bg-gray-50 dark:bg-neutral-800">
                              <tr>
                                <th class="px-4 py-3 text-start text-xs font-semibold uppercase">Subtask Name</th>
                                <th class="px-4 py-3 text-start text-xs font-semibold uppercase">Assignee</th>
                                <th class="px-4 py-3 text-start text-xs font-semibold uppercase">Status</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold uppercase">Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr v-for="subtask in subtasks[task.id]" :key="subtask.id" class="bg-white dark:bg-neutral-900">
                                <td class="px-4 py-2">{{ subtask.name }}</td>
                                <td class="px-4 py-2">{{ subtask.assignee?.name || 'Unassigned' }}</td>
                                <td class="px-4 py-2 text-center">
                                  <span :class="{
                                      'bg-blue-500 text-white': subtask.status === 'pending',
                                      'bg-orange-500 text-white': subtask.status === 'in_progress',
                                      'bg-green-500 text-white': subtask.status === 'completed'
                                    }" class="text-xs font-semibold px-3 py-1 rounded-lg">
                                    {{ subtask.status.replace('_', ' ').toUpperCase() }}
                                  </span>
                                </td>
                                <td class="px-4 py-2 text-center text-gray-400 text-sm italic">—</td>
                              </tr>

                              <!-- Inline add subtask row -->
                              <tr v-if="newSubtaskRow[task.id]">
                                <td colspan="4" class="px-4 py-3">
                                  <input v-model="newSubtask.name" placeholder="Subtask name" class="w-full mb-2 p-2 border rounded" />
                                  <textarea v-model="newSubtask.description" placeholder="Description" class="w-full mb-2 p-2 border rounded"></textarea>

                                  <!-- Assignee Dropdown -->
                                  <div class="relative mb-2">
                                    <div @click="isDropdownOpen = !isDropdownOpen" class="w-full p-2 border rounded bg-white cursor-pointer">
                                      {{ newSubtask.assignee_id.length ? `${newSubtask.assignee_id.length} selected` : 'Select Assignees' }}
                                    </div>
                                    <div v-if="isDropdownOpen" class="absolute z-10 bg-white border rounded mt-1 shadow max-h-60 overflow-y-auto w-full">
                                      <label v-for="user in users" :key="user.id" class="flex items-center px-3 py-2">
                                        <input type="checkbox" v-model="newSubtask.assignee_id" :value="user.id" class="mr-2" />
                                        {{ user.name }}
                                      </label>
                                    </div>
                                  </div>

                                  <div class="flex gap-2">
                                    <button @click="submitInlineSubtask(task.id)" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
                                    <button @click="cancelInlineSubtask(task.id)" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                                  </div>
                                </td>
                              </tr>

                              <!-- Add button when inline row is hidden -->
                              <tr v-else>
                                <td colspan="4" class="px-4 py-2">
                                  <button @click="showInlineSubtaskInput(task.id)" class="px-3 py-1 bg-cyan-600 text-white rounded hover:bg-cyan-700">
                                    + Add Subtask
                                  </button>
                                </td>
                              </tr>
                            </tbody>
                          </table>
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
  </ViewSingleProject>
</template>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: all 0.3s ease;
}
.modal-enter-from,
.modal-leave-to {
  opacity: 0;
  transform: scale(0.95);
}
.modal-enter-to,
.modal-leave-from {
  opacity: 1;
  transform: scale(1);
}
</style>
