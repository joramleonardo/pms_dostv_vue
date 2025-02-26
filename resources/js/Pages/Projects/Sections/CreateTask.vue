<!-- <script setup>
    import 'preline';

</script>


<template>
    <button type="button" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent
                            bg-gray-800 text-white hover:bg-cyan-600 focus:outline-none focus:bg-cyan-600 disabled:opacity-50
                            disabled:pointer-events-none">
        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/>
        </svg>
        Add Task
    </button>


</template>

 -->

 <script setup>
import 'preline';
import { ref, onMounted } from 'vue';
import { useForm, router } from '@inertiajs/vue3';

// State for Modal Visibility
const isModalOpen = ref(false);

// Task Form
const form = useForm({
    task_name: '',
    task_description: '',
    assignee_id: '',
    task_status: 'new',
    task_due_date: '',
    task_category: '',
    task_sub_category: ''
});

// Fetch Users for Assignee Dropdown
const users = ref([]);
onMounted(async () => {
    try {
        const response = await fetch('/tasks/get-users');
        users.value = await response.json();
        console.log(users.value);
    } catch (error) {
        console.error("Error fetching users:", error);
    }
});

// Task Categories and Sub-Categories
const categories = {
    'pre-production': ['Script Writing', 'Planning', 'Storyboard'],
    'production': ['Filming', 'Recording', 'Directing'],
    'post-production': ['Editing', 'VFX', 'Color Grading']
};
const subCategories = ref([]);

// Update Sub-Category Options on Category Change
const updateSubCategories = () => {
    subCategories.value = categories[form.task_category] || [];
};

// Form Submission
const submitTask = () => {
    form.post('/tasks', {
        onSuccess: () => {
            isModalOpen.value = false;
            form.reset();
        }
    });
};
</script>

<template>
    <!-- Add Task Button -->
    <button @click="isModalOpen = true"
        class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent
               bg-gray-800 text-white hover:bg-cyan-600 focus:outline-none focus:bg-cyan-600 disabled:opacity-50">
        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14"/>
            <path d="M12 5v14"/>
        </svg>
        Add Task
    </button>

    <!-- Modal -->
    <div v-if="isModalOpen" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
            <h2 class="text-lg font-semibold mb-4">Create New Task</h2>

            <form @submit.prevent="submitTask">
                <!-- Task Name -->
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Task Name</label>
                    <input v-model="form.task_name" type="text" required
                        class="mt-1 block w-full p-2 border rounded-lg focus:ring focus:ring-cyan-300">
                </div>

                <!-- Task Description -->
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Task Description</label>
                    <textarea v-model="form.task_description" rows="2"
                        class="mt-1 block w-full p-2 border rounded-lg focus:ring focus:ring-cyan-300"></textarea>
                </div>

                <!-- Assignee Dropdown -->
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Assignee</label>
                    <select v-model="form.assignee_id" required
                        class="mt-1 block w-full p-2 border rounded-lg focus:ring focus:ring-cyan-300">
                        <option value="" disabled>Select Assignee</option>
                        <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                    </select>
                </div>

                <!-- Task Status -->
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Task Status</label>
                    <select v-model="form.task_status" required
                        class="mt-1 block w-full p-2 border rounded-lg focus:ring focus:ring-cyan-300">
                        <option value="new">New</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>

                <!-- Due Date -->
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Due Date</label>
                    <input v-model="form.task_due_date" type="date" required
                        class="mt-1 block w-full p-2 border rounded-lg focus:ring focus:ring-cyan-300">
                </div>

                <!-- Task Category -->
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Task Category</label>
                    <select v-model="form.task_category" @change="updateSubCategories" required
                        class="mt-1 block w-full p-2 border rounded-lg focus:ring focus:ring-cyan-300">
                        <option value="" disabled>Select Category</option>
                        <option value="pre-production">Pre-Production</option>
                        <option value="production">Production</option>
                        <option value="post-production">Post-Production</option>
                    </select>
                </div>

                <!-- Task Sub-Category -->
                <div v-if="subCategories.length" class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Task Sub-Category</label>
                    <select v-model="form.task_sub_category" required
                        class="mt-1 block w-full p-2 border rounded-lg focus:ring focus:ring-cyan-300">
                        <option value="" disabled>Select Sub-Category</option>
                        <option v-for="sub in subCategories" :key="sub" :value="sub">{{ sub }}</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" @click="isModalOpen = false"
                        class="px-4 py-2 bg-gray-300 rounded-lg">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-cyan-600 text-white rounded-lg">Create Task</button>
                </div>
            </form>
        </div>
    </div>
</template>
