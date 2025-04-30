<script setup>
    import { ref, defineProps, defineEmits } from "vue";
    import { useForm } from "@inertiajs/vue3";
    import { useEventBus } from "@/eventBus";
    import Modal from '@/Components/Modal.vue';

    const eventBus = useEventBus();

    const props = defineProps({
        project: Object,
    });

    const emit = defineEmits(["refresh"]);

    const showAlert = ref(false);

    const editForm = useForm({
        id: props.project?.id || "",
        project_name: props.project?.project_name || "",
        start_date: props.project?.start_date || "",
        end_date: props.project?.end_date || "",
        coverage_segment: props.project?.coverage_segment || "",
        description: props.project?.description || "",
    });

    const updateProject = () => {
        editForm.put(route("projects.update", editForm.id), {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                emit("refresh");
                closeSubtaskModal();
                eventBus.emit("projectCreated");

                showAlert.value = true;
                setTimeout(() => {
                    showAlert.value = false;
                }, 3000);
            },
            onError: (errors) => {
                console.error("Update failed:", errors);
            },
        });
    };

    const subtaskModal = ref(false);

    const openSubtaskModal = () => {
        subtaskModal.value = true;
    };

    const closeSubtaskModal = () => {
        subtaskModal.value = false;

    };

</script>

<template>


    <!-- EDIT ALERT -->
    <div v-if="showAlert" class="fixed bottom-5 right-5 z-50 w-[400px]">
        <div class="flex items-center p-6 text-lg text-green-900 rounded-lg bg-green-200 shadow-2xl dark:bg-green-800 dark:text-green-100" role="alert">
            <svg class="w-6 h-6 me-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0ZM9 5a1 1 0 1 1 2 0v4a1 1 0 0 1-2 0V5Zm1 8.75a1.25 1.25 0 1 1 0-2.5 1.25 1.25 0 0 1 0 2.5Z"/>
            </svg>
            <div>
                <span class="font-bold">Success!</span> <br>
                <span class="text-base">Changes have been saved successfully.</span>
            </div>
        </div>
    </div>

    <button @click="openSubtaskModal" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 mr-2">
        Edit
    </button>

    <Modal :show="subtaskModal" @close="closeSubtaskModal">
        <div class="p-6 ">
            <div class="flex justify-between items-center border-b dark:border-neutral-700">
                <h3 class="text-lg font-semibold mb-4">Edit Project</h3>
            </div>
            <div class="overflow-y-auto">
                <form @submit.prevent="updateProject">
                    <div class="p-4 overflow-y-auto">
                        <div class="space-y-4 sm:space-y-6">
                            <div class="space-y-2">
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
                    <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                        <button type="button" @click="closeSubtaskModal" class="bg-gray-300 px-4 py-2 rounded-lg">
                            Cancel
                        </button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </Modal>

</template>
