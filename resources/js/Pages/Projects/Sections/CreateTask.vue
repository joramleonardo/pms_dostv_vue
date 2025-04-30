

<script setup>
    import 'preline';
    import { ref, onMounted, computed } from 'vue';
    import { useForm, usePage, router } from '@inertiajs/vue3';

    const isModalOpen = ref(false);
    const isDropdownOpen = ref(false);

    const props = defineProps({
        projectId: {
            type: Number,
            required: true
        }
    });

    const showAlert = ref(false);

    const page = usePage(); // Access page props
    const authUser = computed(() => page.props.auth.user); // Get logged-in user

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

                // alert("Task Created Successfully!");

                showAlert.value = true;
                setTimeout(() => {
                    showAlert.value = false;
                }, 3000);
            }
        });
    };

    // Get success message
    const successMessage = usePage().props.flash?.success || '';

</script>

<template>

    <!-- CREATE TASK ALERT -->
    <div v-if="showAlert" class="fixed bottom-5 right-5 z-50 w-[400px]">
        <div class="flex items-center p-6 text-lg text-green-900 rounded-lg bg-green-200 shadow-2xl dark:bg-green-800 dark:text-green-100" role="alert">
            <svg class="w-6 h-6 me-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0ZM9 5a1 1 0 1 1 2 0v4a1 1 0 0 1-2 0V5Zm1 8.75a1.25 1.25 0 1 1 0-2.5 1.25 1.25 0 0 1 0 2.5Z"/>
            </svg>
            <div>
                <span class="font-bold">Success!</span> <br>
                <span class="text-base">New task was created successfully.</span>
            </div>
        </div>
    </div>

    <!-- Add Task Button -->
    <button @click="isModalOpen = true"
        class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent
              bg-cyan-600 text-white hover:bg-cyan-700 focus:outline-none focus:bg-cyan-600 disabled:opacity-50">
        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14"/>
            <path d="M12 5v14"/>
        </svg>
        Add Task
    </button>

    <!-- Modal -->
    <Transition name="modal">
        <div v-if="isModalOpen" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full overflow-y-auto">
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
                    <div class="mb-3 relative" v-if="['Supervising Producer'].includes(authUser.role)">
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
                    <div class="mb-3 relative" v-if="['Program Manager'].includes(authUser.role)">
                        <label class="block text-sm font-medium text-gray-700">Assignee</label>
                        <div @click="isDropdownOpen = !isDropdownOpen"
                            class="mt-1 block w-full p-2 border rounded-lg bg-white cursor-pointer focus:ring focus:ring-cyan-300">
                            {{ form.assignee_ids.length ? `${form.assignee_ids.length} selected` : 'Select Assignees' }}
                        </div>
                        <div v-if="isDropdownOpen" class="absolute bg-white border rounded-lg w-full mt-1 p-2 z-10 shadow-lg">
                            <label v-for="user in users_prod" :key="user.id" class="flex items-center space-x-2">
                                <input type="checkbox" v-model="form.assignee_ids" :value="user.id">
                                <span>{{ user.name }}</span>
                            </label>
                        </div>
                    </div>
                    <div class="mb-3 relative" v-if="['Editing Supervisor'].includes(authUser.role)">
                        <label class="block text-sm font-medium text-gray-700">Assignee</label>
                        <div @click="isDropdownOpen = !isDropdownOpen"
                            class="mt-1 block w-full p-2 border rounded-lg bg-white cursor-pointer focus:ring focus:ring-cyan-300">
                            {{ form.assignee_ids.length ? `${form.assignee_ids.length} selected` : 'Select Assignees' }}
                        </div>
                        <div v-if="isDropdownOpen" class="absolute bg-white border rounded-lg w-full mt-1 p-2 z-10 shadow-lg">
                            <label v-for="user in users_editing" :key="user.id" class="flex items-center space-x-2">
                                <input type="checkbox" v-model="form.assignee_ids" :value="user.id">
                                <span>{{ user.name }}</span>
                            </label>
                        </div>
                    </div>
                    <div class="mb-3 relative" v-if="['Assoc Producer'].includes(authUser.role)">
                        <label class="block text-sm font-medium text-gray-700">Assignee</label>
                        <div @click="isDropdownOpen = !isDropdownOpen"
                            class="mt-1 block w-full p-2 border rounded-lg bg-white cursor-pointer focus:ring focus:ring-cyan-300">
                            {{ form.assignee_ids.length ? `${form.assignee_ids.length} selected` : 'Select Assignees' }}
                        </div>
                        <div v-if="isDropdownOpen" class="absolute bg-white border rounded-lg w-full mt-1 p-2 z-10 shadow-lg">
                            <label v-for="user in users_assoc" :key="user.id" class="flex items-center space-x-2">
                                <input type="checkbox" v-model="form.assignee_ids" :value="user.id">
                                <span>{{ user.name }}</span>
                            </label>
                        </div>
                    </div>
                    <!-- Task Status -->
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Task Status</label>
                        <select v-model="form.task_status" required disabled
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
                    <div class="mb-3" v-if="['Assoc Producer', 'Editing Supervisor'].includes(authUser.role)">
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
    </Transition>
</template>

<style scoped>
    .modal-enter-active, .modal-leave-active {
    transition: all 0.3s ease;
    }
    .modal-enter-from, .modal-leave-to {
    opacity: 0;
    transform: scale(0.95);
    }
    .modal-enter-to, .modal-leave-from {
    opacity: 1;
    transform: scale(1);
    }
</style>
