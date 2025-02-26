

<script setup>
        import 'preline';
        import { ref, onMounted, defineProps, watch, defineEmits   } from 'vue';
        import { useForm, router, Head, Link  } from '@inertiajs/vue3';
        import { useEventBus } from "@/eventBus";

        import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
        import CreateProject from '@/Pages/Projects/Create.vue';
        import SearchFilter from '@/Pages/Projects/SearchFilter.vue';


        const eventBus = useEventBus();

        const props = defineProps({
            projects: Object
        });

        const projects = ref(props.projects);

        const searchQuery = ref('');

        const editForm = useForm({
            id: '',
            project_name: '',
            coverage_segment: '',
            description: ''
        });

        const openEditModal = (project) => {
            editForm.id = project.id;
            editForm.project_name = project.project_name;
            editForm.coverage_segment = project.coverage_segment;
            editForm.description = project.description;

            document.getElementById('edit-modal').classList.remove('hidden');
        };

        const closeEditModal = () => {
            document.getElementById('edit-modal').classList.add('hidden');
        };

        const updateProject = () => {
            editForm.put(route('projects.update', editForm.id), {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    closeEditModal();
                    fetchProjects(); // Reloads the updated list after update
                    eventBus.emit("projectCreated");
                },
                onError: (errors) => {
                    console.error("Update failed:", errors);
                }
            });
        };

        const deleteProject = (id) => {
            if (!confirm("Are you sure you want to delete this project?")) {
                return;
            }

            router.delete(route("projects.destroy", id), {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    fetchProjects(); // Reloads the updated list after update
                    eventBus.emit("projectCreated");
                    alert("Project deleted successfully");
                },
            });
        };

        const fetchProjects = (page = 1) => {
            router.get(route('projects.index'), {
                search: searchQuery.value,
                page: page // Ensures it fetches the correct paginated data
            }, {
                preserveScroll: true,
                preserveState: true,
                onSuccess: (response) => {
                    projects.value = response.props.projects; // Updates projects data
                }
            });
        };

        watch(searchQuery, () => {
            fetchProjects();
        });


        onMounted(() => {
            fetchProjects();
        });
</script>


<template>
    <AuthenticatedLayout>
        <Head title="Projects" />

        <div class="w-full lg:ps-64">
            <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
                <!-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> -->
                <div class="mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="px-6 py-4 flex justify-between items-center border-b border-gray-200 dark:border-neutral-700">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                                Projects
                            </h2>
                            <div class="flex gap-x-2 items-center">
                                <SearchFilter v-model="searchQuery" class="w-64" />
                                <CreateProject  @refreshTable="fetchProjects()"/>
                            </div>
                        </div>


                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
                                <thead class="bg-gray-100 border-b">
                                    <tr>
                                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">#</th>
                                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Project Name</th>
                                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Coverage/Segment</th>
                                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Status</th>
                                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Start Date</th>
                                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">End Date</th>
                                        <!-- <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Description</th> -->
                                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="!projects?.data">
                                        <td colspan="5" class="text-center py-3">Loading...</td>
                                    </tr>
                                    <tr v-else v-for="(project, index) in projects.data" :key="project.id" class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4 text-sm text-gray-700">{{ index + 1 }}</td>
                                        <td class="py-3 px-4">
                                            <!-- <a
                                                class="text-teal-500 underline decoration-teal-500 hover:opacity-80
                                                focus:outline-none focus:opacity-80"
                                                href="#">
                                                {{ project.project_name }}
                                            </a> -->

                                            <Link
                                                :href="route('overview.index')"
                                                class="text-teal-500 underline decoration-teal-500 hover:opacity-80
                                                focus:outline-none focus:opacity-80">
                                                {{ project.project_name }}
                                            </Link>

                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-700">{{ project.coverage_segment }}</td>
                                        <td class="py-3 px-4">
                                            <span v-if="project.project_status === 'pending'"
                                                class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500">
                                                <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                                </svg>
                                                Pending
                                            </span>
                                            <span v-if="project.project_status === 'in progress'"
                                            class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-500">
                                                <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                                </svg>
                                                In Progress
                                            </span>
                                            <span v-if="project.project_status === 'completed'"
                                            class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-teal-100 text-teal-800 dark:bg-teal-800/30 dark:text-teal-500">
                                                <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                                </svg>
                                                Completed
                                            </span>
                                            <!-- {{ project.project_status }} -->
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-700">{{ project.start_date }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-700">{{ project.end_date }}</td>
                                        <!-- <td class="py-3 px-4 text-sm text-gray-700">{{ project.description }}</td> -->
                                        <td class="py-3 px-4">
                                            <button  @click="openEditModal(project)" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 mr-2">
                                                Edit
                                            </button>
                                            <button @click="deleteProject(project.id)" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="flex justify-between items-center mt-6">
                                <button
                                    @click="fetchProjects(projects.prev_page_url ? projects.current_page - 1 : projects.current_page)"
                                    :disabled="!projects.prev_page_url"
                                    class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-md shadow-sm bg-white text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Previous
                                </button>
                                <div class="space-x-2">
                                    <button
                                        v-for="page in projects.last_page"
                                        :key="page"
                                        @click="fetchProjects(page)"
                                        :class="[
                                            'px-4 py-2 text-sm font-medium border rounded-md shadow-sm',
                                            projects.current_page === page
                                                ? 'bg-blue-600 text-white hover:bg-blue-700'
                                                : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                                        ]">
                                        {{ page }}
                                    </button>
                                </div>
                                <button
                                    @click="fetchProjects(projects.next_page_url ? projects.current_page + 1 : projects.current_page)"
                                    :disabled="!projects.next_page_url"
                                    class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-md shadow-sm bg-white text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Next
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="edit-modal" class="hs-overlay hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
            <div class="bg-white w-96 p-6 rounded-lg shadow-lg">
                <h3 class="text-lg font-semibold mb-4">Edit Project</h3>

                <form @submit.prevent="updateProject">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Project Name</label>
                        <input v-model="editForm.project_name" type="text" class="w-full px-3 py-2 border rounded-lg">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Coverage/Segment</label>
                        <select v-model="editForm.coverage_segment" class="w-full px-3 py-2 border rounded-lg">
                            <option value="EXPERTALK ONLINE">EXPERTALK ONLINE</option>
                            <option value="SIYENSIKAT">SIYENSIKAT</option>
                            <option value="RADYO SIYENSYA">RADYO SIYENSYA</option>
                            <option value="BALITANG RAPIDOST">BALITANG RAPIDOST</option>
                            <option value="BANTAY BULKAN">BANTAY BULKAN</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea v-model="editForm.description" class="w-full px-3 py-2 border rounded-lg"></textarea>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button @click="closeEditModal" type="button" class="bg-gray-300 px-4 py-2 rounded-lg">Cancel</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                            Update
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
