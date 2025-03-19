<script setup>
import { ref, defineProps, defineEmits } from "vue";
import { useForm } from "@inertiajs/vue3";
import { useEventBus } from "@/eventBus";

const eventBus = useEventBus();

// Props: Receives project details
const props = defineProps({
    project: Object,
});

// Emits: Notify parent component after updating
const emit = defineEmits(["refresh"]);

// Track modal state inside this component
const isEditModalOpen = ref(false);

// Open the modal
const openEditModal = () => {
    isEditModalOpen.value = true;
};

// Close the modal
const closeEditModal = () => {
    isEditModalOpen.value = false;
};

// Form for editing
const editForm = useForm({
    id: props.project?.id || "",
    project_name: props.project?.project_name || "",
    start_date: props.project?.start_date || "",
    end_date: props.project?.end_date || "",
    coverage_segment: props.project?.coverage_segment || "",
    description: props.project?.description || "",
});

// Update project function
const updateProject = () => {
    editForm.put(route("projects.update", editForm.id), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            emit("refresh"); // Notify parent to reload data
            closeEditModal(); // Close modal
            eventBus.emit("projectCreated");
        },
        onError: (errors) => {
            console.error("Update failed:", errors);
        },
    });
};
</script>

<template>
    <!-- 🟢 Edit Button -->
    <button @click="openEditModal" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 mr-2">
        Edit
    </button>

    <!-- 🔹 Edit Project Modal -->
    <div v-if="isEditModalOpen" class="fixed top-0 left-0 w-full h-full flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
            <div class="flex justify-between items-center border-b dark:border-neutral-700">

                <h3 class="text-lg font-semibold mb-4">Edit Project</h3>
            </div>
            <div class="overflow-y-auto">
                <form @submit.prevent="updateProject">
                    <div class="p-4 overflow-y-auto">
                        <div class="space-y-4 sm:space-y-6">
                            <div class="space-y-2">
                                <!-- Project Name -->
                                <label class="block text-sm font-medium">Project Name</label>
                                <input v-model="editForm.project_name" type="text" class="w-full px-3 py-2 border rounded-lg mb-4" />
                            </div>
                            <div class="space-y-2">
                                <label for="start_date" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Start Date
                                </label>
                                <input v-model="editForm.start_date" id="start_date" type="date"
                                    class="py-2 px-3 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="space-y-2">
                                <label for="end_date" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                    End Date
                                </label>
                                <input v-model="editForm.end_date" id="end_date" type="date"
                                    class="py-2 px-3 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="space-y-2">
                                <!-- Coverage Segment -->
                                <label class="block text-sm font-medium">Coverage/Segment</label>
                                <select v-model="editForm.coverage_segment" class="w-full px-3 py-2 border rounded-lg mb-4">
                                    <option>EXPERTALK ONLINE</option>
                                    <option>SIYENSIKAT</option>
                                    <option>RADYO SIYENSYA</option>
                                    <option>BALITANG RAPIDOST</option>
                                    <option>BANTAY BULKAN</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label for="description" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Description
                                </label>
                                <textarea v-model="editForm.description" id="description"
                                    class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500"
                                    rows="6" placeholder="Enter project description"></textarea>

                            </div>
                            <div class="space-y-2">

                            </div>

                        </div>
                    </div>


                    <!-- 🟢 Buttons -->
                    <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                        <button type="button" @click="closeEditModal" class="bg-gray-300 px-4 py-2 rounded-lg">
                            Cancel
                        </button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
