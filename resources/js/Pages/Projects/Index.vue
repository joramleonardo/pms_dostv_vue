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
const page = usePage(); // Access page props
const authUser = computed(() => page.props.auth.user); // Get logged-in user

const responseData = ref({
    data: [],
    current_page: 1,
    last_page: 1,
    prev_page_url: null,
    next_page_url: null,
});

const perPage = ref(10);
const selectedProject = ref(null);
const isEditModalOpen = ref(false);

// ✅ Open Edit Modal
const openEditModal = (project) => {
    selectedProject.value = project;
    isEditModalOpen.value = true;

    setTimeout(() => {
        const modal = document.getElementById("modal-edit-project");
        if (modal && window.HSOverlay) {
            window.HSOverlay.open(modal);
        }
    }, 100);
};

// ✅ Close Edit Modal
const closeEditModal = () => {
    isEditModalOpen.value = false;
    const modal = document.getElementById("modal-edit-project");
    if (modal && window.HSOverlay) {
        window.HSOverlay.close(modal);
    }
};

// ✅ Delete Project
const deleteProject = (id) => {
    if (!confirm("Are you sure you want to delete this project?")) {
        return;
    }

    router.delete(route("projects.destroy", id), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            fetchProjects(); // Ensure data updates
            eventBus.emit("projectCreated");
            alert("Project deleted successfully");
        },
    });
};

// ✅ Fetch all projects (For Program Manager)
const fetchProjects = (page = 1) => {
    axios.get(`get-projects?page=${page}`)
        .then(res => {
            responseData.value = res.data;
            responseData.value.current_page = page;
        })
        .catch(err => {
            console.error("Error fetching projects:", err);
        });
};

// ✅ Fetch assigned projects (For Assoc Producer, Editing Supervisor)
const fetchAssignedProjects = (page = 1) => {
    axios.get(`get-assigned-projects?page=${page}`)
        .then(res => {
            responseData.value = res.data;
            responseData.value.current_page = page;
        })
        .catch(err => {
            console.error("Error fetching assigned projects:", err);
        });
};

// ✅ Dynamically fetch based on role
onMounted(() => {
    if (authUser.value?.role === "Program Manager") {
        fetchProjects();
    } else if (["Assoc Producer", "Editing Supervisor"].includes(authUser.value?.role)) {
        fetchAssignedProjects();
    }
});

// ✅ Pagination - Automatically calls correct fetch function
const prevPage = () => {
    if (responseData.value.current_page > 1) {
        if (authUser.value?.role === "Program Manager") {
            fetchProjects(responseData.value.current_page - 1);
        } else {
            fetchAssignedProjects(responseData.value.current_page - 1);
        }
    }
};

const nextPage = () => {
    if (responseData.value.current_page < responseData.value.last_page) {
        if (authUser.value?.role === "Program Manager") {
            fetchProjects(responseData.value.current_page + 1);
        } else {
            fetchAssignedProjects(responseData.value.current_page + 1);
        }
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Projects" />
        <div class="w-full lg:ps-64">
            <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
                <div class="mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="px-6 py-4 flex justify-between items-center border-b border-gray-200 dark:border-neutral-700">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                                Projects
                            </h2>
                            <div class="flex gap-x-2 items-center">
                                <CreateProject @refreshTable="fetchProjects()" />
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
                                        <th v-if="authUser?.role === 'Program Manager'" class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in responseData.data" :key="item.id" class="hover:bg-gray-100 dark:hover:bg-neutral-700">
                                        <td class="py-3 px-4 text-sm text-gray-700">{{ index + 1 }}</td>
                                        <td class="py-3 px-4">
                                            <Link :href="route('list.index', item.id)"
                                                class="text-teal-500 underline decoration-teal-500 hover:opacity-80 focus:outline-none focus:opacity-80 font-semibold">
                                                {{ item.project_name }}
                                            </Link>
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-700">{{ item.coverage_segment }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-700">{{ item.project_status }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-700">{{ item.start_date }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-700">{{ item.end_date }}</td>
                                        <td v-if="authUser?.role === 'Program Manager'" class="py-3 px-4">
                                            <EditProject :project="item" @refresh="fetchProjects" />
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

        <EditProject v-show="isEditModalOpen" :project="selectedProject" :isOpen="isEditModalOpen" @close="closeEditModal" @refresh="fetchProjects" />
    </AuthenticatedLayout>
</template>
