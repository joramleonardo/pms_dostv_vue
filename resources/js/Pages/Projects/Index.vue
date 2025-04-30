<script setup>
        import 'preline';
        import { ref, onMounted, computed } from 'vue';
        import { useForm, router, Head, Link, usePage } from '@inertiajs/vue3';
        import { useEventBus } from "@/eventBus";

        import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
        import CreateProject from '@/Pages/Projects/Create.vue';
        import EditProject from "@/Pages/Projects/EditProject.vue";
        import SearchFilter from '@/Pages/Projects/SearchFilter.vue';

        import axios from 'axios';

        const eventBus = useEventBus();
        const page = usePage();
        const authUser = computed(() => page.props.auth.user);
        const showAlert_delete = ref(false);
        const showAlert_create = ref(false);

        const responseData = ref({
            data: [],
            current_page: 1,
            last_page: 1,
            prev_page_url: null,
            next_page_url: null,
        });

        const editingProjectField = ref({ id: null, field: null });

        const newProject = ref({
            project_name: '',
            coverage_segment: '',
            project_status: 'pending',
            description: '',
            start_date: '',
            end_date: ''
        });

        const segmentOptions = [
            "EXPERTALK ONLINE",
            "DOSTv",
            "SIYENSIKAT",
            "OTHER..."
        ];

        const statusOptions = [
            { value: 'pending', label: 'Pending' },
            { value: 'in_progress', label: 'In Progress' },
            { value: 'completed', label: 'Completed' }
        ];

        const isCreating = ref(false);

        const deleteProject = (id) => {
            if (!confirm("Are you sure you want to delete this project?")) return;

            router.delete(route("projects.destroy", id), {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    fetchProjects();
                    eventBus.emit("projectCreated");
                    showAlert_delete.value = true;
                    setTimeout(() => showAlert_delete.value = false, 3000);
                },
            });
        };

        const fetchProjects = async (page = 1) => {
            try {
                const res = await axios.get(`get-projects?page=${page}`);
                responseData.value = res.data;
                responseData.value.current_page = page;
            } catch (err) {
                console.error("Error fetching projects:", err);
            }
        };

        const fetchAssignedProjects = async (page = 1) => {
            try {
                const res = await axios.get(`get-assigned-projects?page=${page}`);
                responseData.value = res.data;
                responseData.value.current_page = page;
            } catch (err) {
                console.error("Error fetching assigned projects:", err);
            }
        };

        onMounted(() => {
            if (authUser.value?.role === "Program Manager") fetchProjects();
            else if (["Assoc Producer", "Editing Supervisor"].includes(authUser.value?.role)) fetchAssignedProjects();
        });

        const prevPage = () => {
            if (responseData.value.current_page > 1) {
                const fetch = authUser.value?.role === "Program Manager" ? fetchProjects : fetchAssignedProjects;
                fetch(responseData.value.current_page - 1);
            }
        };

        const nextPage = () => {
            if (responseData.value.current_page < responseData.value.last_page) {
                const fetch = authUser.value?.role === "Program Manager" ? fetchProjects : fetchAssignedProjects;
                fetch(responseData.value.current_page + 1);
            }
        };

        const createNewProject = async () => {
            if (!newProject.value.project_name.trim()) {
                alert("Please enter a project name!");
                return;
            }

            isCreating.value = true;

            try {
                await axios.post(route('projects.store'), newProject.value);
                await fetchProjects();
                newProject.value = { project_name: '', coverage_segment: '', project_status: 'pending', description: '', start_date: '', end_date: '' };
                showAlert_create.value = true;
                setTimeout(() => showAlert_create.value = false, 3000);
            } catch (error) {
                console.error("Error creating project:", error);
                alert("Failed to create project.");
            } finally {
                isCreating.value = false;
            }
        };

        const enableEdit = (id, field) => {
            editingProjectField.value = { id, field };
        };

        const saveInlineProject = async (project) => {
            try {
                await axios.put(`/projects-update/${project.id}`, project);

                editingProjectField.value = { id: null, field: null };
                await fetchProjects();
            } catch (error) {
                console.error("Failed to save:", error);
                alert("Failed to save changes.");
            }
        };
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Projects" />
        <div class="w-full lg:ps-64">
            <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">

                <!-- DELETE ALERT -->
                <div v-if="showAlert_delete" class="fixed top-5 right-5 z-50 w-[400px]">
                    <div class="flex items-center p-6 text-lg text-green-900 rounded-lg bg-green-200 shadow-2xl dark:bg-green-800 dark:text-green-100" role="alert">
                        <svg class="w-6 h-6 me-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0ZM9 5a1 1 0 1 1 2 0v4a1 1 0 0 1-2 0V5Zm1 8.75a1.25 1.25 0 1 1 0-2.5 1.25 1.25 0 0 1 0 2.5Z"/>
                        </svg>
                        <div>
                            <span class="font-bold">Success!</span> <br>
                            <span class="text-base">Project was deleted successfully.</span>
                        </div>
                    </div>
                </div>
                <div class="mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="px-6 py-4 flex justify-between items-center border-b border-gray-200 dark:border-neutral-700">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                                Projects
                            </h2>
                        </div>

                        <div class="w-full overflow-x-auto pb-2">
                            <div class="min-w-[1100px]">
                                <table class="w-full bg-white border border-gray-200 shadow-md rounded-lg">
                                    <thead class="bg-gray-100 border-b">
                                        <tr>
                                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">#</th>
                                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Project Name</th>
                                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Coverage/Segment</th>
                                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Status</th>
                                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Start Date</th>
                                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">End Date</th>
                                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Description</th>
                                            <th v-if="authUser?.role === 'Program Manager'" class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- New project row always visible -->
                                        <tr class="bg-gray-50" v-if="['Supervising Producer', 'Program Manager'].includes(authUser.role)">
                                            <td class="py-3 px-4 text-sm text-gray-500">#</td>
                                            <td class="py-3 px-4">
                                                <input v-model="newProject.project_name" placeholder="Project Name" class="w-full p-1 border rounded" />
                                            </td>
                                            <td class="py-3 px-4">
                                                <select v-model="newProject.coverage_segment" class="w-full p-1 border rounded">
                                                    <option value="" disabled>Select Segment</option>
                                                    <option v-for="option in segmentOptions" :key="option" :value="option">
                                                        {{ option }}
                                                    </option>
                                                </select>
                                            </td>
                                            <td class="py-3 px-4">
                                                <select v-model="newProject.project_status" class="w-full p-1 border rounded">
                                                    <option value="" disabled>Select Status</option>
                                                    <option v-for="option in statusOptions" :key="option.value" :value="option.value">
                                                        {{ option.label }}
                                                    </option>
                                                </select>
                                            </td>
                                            <td class="py-3 px-4">
                                                <input type="date" v-model="newProject.start_date" class="w-full p-1 border rounded" />
                                            </td>
                                            <td class="py-3 px-4">
                                                <input type="date" v-model="newProject.end_date" class="w-full p-1 border rounded" />
                                            </td>
                                            <td class="py-3 px-4">
                                                <textarea v-model="newProject.description" placeholder="Description" class="w-full p-1 border rounded"></textarea>
                                            </td>
                                            <td v-if="authUser?.role === 'Program Manager'" class="py-3 px-4">
                                                <button @click="createNewProject" :disabled="isCreating" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed">
                                                    {{ isCreating ? 'Saving...' : 'Save' }}
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Existing projects loop -->
                                        <tr v-for="(item, index) in responseData.data" :key="item.id" class="hover:bg-gray-100 dark:hover:bg-neutral-700">
                                            <td class="py-3 px-4 text-sm text-gray-700">{{ index + 1 }}</td>
                                            <!-- Project Name: requires edit button -->
                                            <td class="py-3 px-4">
                                                <template v-if="editingProjectField.id === item.id && editingProjectField.field === 'project_name'">
                                                    <input
                                                    v-model="item.project_name"
                                                    @blur="() => saveInlineProject(item)"
                                                    class="w-full p-1 border rounded"
                                                    autofocus
                                                    />
                                                </template>
                                                <template v-else>
                                                    <div class="flex items-center gap-2">
                                                    <Link :href="route('list.index', item.id)"
                                                        class="text-teal-500 underline decoration-teal-500 hover:opacity-80 focus:outline-none focus:opacity-80 font-semibold">
                                                        {{ item.project_name }}
                                                    </Link>
                                                    <button
                                                        @click="enableEdit(item.id, 'project_name')"
                                                        class="text-gray-500 hover:text-yellow-600 focus:outline-none"
                                                        title="Edit project name"
                                                    >
                                                        ✏️
                                                    </button>
                                                    </div>
                                                </template>
                                            </td>
                                            <!-- Coverage Segment -->
                                            <td class="py-3 px-4 text-sm text-gray-700" @click="enableEdit(item.id, 'coverage_segment')">
                                                <select
                                                    v-if="editingProjectField.id === item.id && editingProjectField.field === 'coverage_segment'"
                                                    v-model="item.coverage_segment"
                                                    @change="() => saveInlineProject(item)"
                                                    class="w-full p-1 border rounded"
                                                >
                                                    <option v-for="option in segmentOptions" :key="option" :value="option">{{ option }}</option>
                                                </select>
                                                <template v-else>
                                                    {{ item.coverage_segment }}
                                                </template>
                                            </td>
                                            <!-- Project Status -->
                                            <td class="py-3 px-4 text-sm text-gray-700" @click="enableEdit(item.id, 'project_status')">
                                                <select v-if="editingProjectField.id === item.id && editingProjectField.field === 'project_status'"
                                                v-model="item.project_status"
                                                @change="() => saveInlineProject(item)"
                                                class="w-full p-1 border rounded"
                                                >
                                                <option v-for="option in statusOptions" :key="option.value" :value="option.value">{{ option.label }}</option>
                                                </select>
                                                <template v-else>
                                                    <transition name="badge">
                                                        <span
                                                        :key="item.project_status"
                                                        :class="{
                                                            'bg-yellow-300 text-yellow-800 ': item.project_status === 'pending',
                                                            'bg-blue-300 text-blue-800': item.project_status === 'in_progress',
                                                            'bg-green-300 text-green-800': item.project_status === 'completed'
                                                        }"
                                                        class="text-xs font-bold px-2.5 py-0.5 rounded border border-gray-200 inline-block"
                                                        >
                                                        {{ item.project_status.replace('_', ' ').replace(/\b\w/g, c => c.toUpperCase()) }}
                                                        </span>
                                                    </transition>
                                                </template>

                                            </td>
                                            <!-- Start Date -->
                                            <td class="py-3 px-4 text-sm text-gray-700" @click="enableEdit(item.id, 'start_date')">
                                                <input v-if="editingProjectField.id === item.id && editingProjectField.field === 'start_date'"
                                                type="date"
                                                v-model="item.start_date"
                                                @blur="() => saveInlineProject(item)"
                                                class="w-full p-1 border rounded"
                                                />
                                                <template v-else>{{ item.start_date }}</template>
                                            </td>
                                            <!-- End Date -->
                                            <td class="py-3 px-4 text-sm text-gray-700" @click="enableEdit(item.id, 'end_date')">
                                                <input v-if="editingProjectField.id === item.id && editingProjectField.field === 'end_date'"
                                                type="date"
                                                v-model="item.end_date"
                                                @blur="() => saveInlineProject(item)"
                                                class="w-full p-1 border rounded"
                                                />
                                                <template v-else>{{ item.end_date }}</template>
                                            </td>
                                            <!-- Description -->
                                            <td class="py-3 px-4 text-sm text-gray-700" @click="enableEdit(item.id, 'description')">
                                            <textarea
                                                v-if="editingProjectField.id === item.id && editingProjectField.field === 'description'"
                                                v-model="item.description"
                                                @blur="() => saveInlineProject(item)"
                                                class="w-full p-1 border rounded"
                                            ></textarea>
                                            <template v-else>
                                                {{ item.description }}
                                            </template>
                                            </td>
                                            <!-- Actions -->
                                            <td v-if="authUser?.role === 'Program Manager'" class="py-3 px-4">

                                                <button @click="deleteProject(item.id)" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                                Delete
                                                </button>
                                            </td>
                                        </tr>

                                    </tbody>

                                </table>

                                <div class="flex justify-between items-center mt-6">
                                    <button @click="prevPage" :disabled="!responseData.prev_page_url" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-md shadow-sm bg-white text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                        Previous
                                    </button>
                                    <button @click="nextPage" :disabled="!responseData.next_page_url" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-md shadow-sm bg-white text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                        Next
                                    </button>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        <div v-if="showAlert_create" class="fixed bottom-5 right-5 z-50 w-[400px]">
            <div class="flex items-center p-6 text-lg text-green-900 rounded-lg bg-green-200 shadow-2xl dark:bg-green-800 dark:text-green-100" role="alert">
                <svg class="w-6 h-6 me-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0ZM9 5a1 1 0 1 1 2 0v4a1 1 0 0 1-2 0V5Zm1 8.75a1.25 1.25 0 1 1 0-2.5 1.25 1.25 0 0 1 0 2.5Z"/>
                </svg>
                <div>
                    <span class="font-bold">Success!</span> <br>
                    <span class="text-base">Project created successfully.</span>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<style scoped>
    .badge-enter-active,
    .badge-leave-active {
    transition: all 0.3s ease;
    }
    .badge-enter-from,
    .badge-leave-to {
    opacity: 0;
    transform: scale(0.95);
    }
    .badge-enter-to,
    .badge-leave-from {
    opacity: 1;
    transform: scale(1);
    }
</style>
