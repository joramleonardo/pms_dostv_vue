

<script setup>
    import 'preline';
    import { ref, onMounted } from 'vue';
    import { useForm, usePage, router } from '@inertiajs/vue3';

    const isModalOpen = ref(false);
    const isDropdownOpen = ref(false);

    const props = defineProps({
        projectId: {
            type: Number,
            required: true
        }
    });


    const form = useForm({
        project_id: props.projectId,
        task_name: '',
        task_description: '',
        assignee_ids: [],
        task_status: 'new',
        task_due_date: '',
        task_category: '',
        task_sub_category: ''
    });

    // Fetch Users for Assignee Dropdown
    const users = ref([]);

    onMounted(async () => {
        try {
            const response = await fetch('/tasks-get-users');
            const data = await response.json();
            users.value = data.filter(user => user.role); // Ensures only users with valid roles are shown
        } catch (error) {
            console.error("Error fetching users:", error);
        }
    });


    // Task Categories and Sub-Categories
    const categories = {
        'pre-production': [ 'Drafting of the Memorandum for Call for Topic Proposals',
                            'Reviewing of the First Draft of the Memorandum',
                            'Reviewing of the Second Draft of the Memorandum',
                            'Receiving and logging of the Final draft of the Memorandum',
                            'Reviewing and Approving the final draft of the Memorandum'],
        'production': [     'Conducting the production shoot',
                            'Capturing of Video footage',
                            'Accomplishing the Production Outline Checklist',
                            'Requesting for the signing of the video release and/or consent form',
                            'Requesting for the accomplishment of the Client Satisfaction Measurement Form'],
        'post-production': ['Dumping of video footage',
                            'Conducting of Post-Production Equipment Inspection',
                            'Submitting the pertinent post-travel documents',
                            'Submitting the editing script',
                            'Approval of the Editing Script']
    };
    const subCategories = ref([]);

    // Update Sub-Category Options on Category Change
    const updateSubCategories = () => {
        subCategories.value = categories[form.task_category] || [];
    };

    const submitTask = () => {
        form.project_id = props.projectId;

        form.post('/tasks', {
            onSuccess: () => {
                isModalOpen.value = false;
                form.reset();
                alert("Task Created Successfully!");
            }
        });
    };

    // Get success message
    const successMessage = usePage().props.flash?.success || '';

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

            <div v-if="successMessage" class="bg-green-100 text-green-800 p-2 rounded-md mb-4">
                {{ successMessage }}
            </div>

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
                <!-- Assignee Multi-Select -->
                <div class="mb-3 relative">
                    <label class="block text-sm font-medium text-gray-700">Assignee</label>
                    <div @click="isDropdownOpen = !isDropdownOpen"
                        class="mt-1 block w-full p-2 border rounded-lg bg-white cursor-pointer focus:ring focus:ring-cyan-300">
                        {{ form.assignee_ids.length ? `${form.assignee_ids.length} selected` : 'Select Assignees' }}
                    </div>
                    <div v-if="isDropdownOpen" class="absolute bg-white border rounded-lg w-full mt-1 p-2 z-10 shadow-lg">
                        <label v-for="user in users" :key="user.id" class="flex items-center space-x-2">
                            <input type="checkbox" v-model="form.assignee_ids" :value="user.id">
                            <span>{{ user.name }}</span>
                        </label>
                    </div>
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
