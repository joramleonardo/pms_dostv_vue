<script setup>
    import { ref, onMounted, defineEmits  } from 'vue';
    import { useForm, usePage } from '@inertiajs/vue3';
    import 'preline';
    import { useEventBus } from "@/eventBus";

    const emitRefresh = defineEmits(['refreshTable']);
    const page = usePage();
    const authUser = page.props.auth.user;
    const eventBus = useEventBus();

    const form = useForm({
        project_name: '',
        coverage_segment: '',
        description: '',
        start_date: '',
        end_date: '',
        created_by: authUser ? authUser.id : ''
    });

    const errors = ref({
        project_name: '',
        coverage_segment: '',
        description: '',
        start_date: '',
        end_date: ''
    });

    const validateForm = () => {
        let valid = true;

        errors.value = { project_name: '', coverage_segment: '', description: '', start_date: '', end_date: '' };

        if (!form.project_name.trim()) {
            errors.value.project_name = "Project Name is required.";
            valid = false;
        }

        if (!form.coverage_segment.trim()) {
            errors.value.coverage_segment = "Coverage/Segment is required.";
            valid = false;
        }

        if (!form.description.trim()) {
            errors.value.description = "Description is required.";
            valid = false;
        }

        if (!form.start_date) {
            errors.value.start_date = "Start Date is required.";
            valid = false;
        }

        if (!form.end_date) {
            errors.value.end_date = "End Date is required.";
            valid = false;
        }

        return valid;
    };

    const submitForm = () => {
        if (!validateForm()) return;

        form.post(route('projects.store'), {
            onSuccess: () => {
                eventBus.emit("projectCreated");
                closeModal();
                form.reset();
                emitRefresh('refreshTable');
            },
            onError: (err) => {
                errors.value = err;
            }
        });
    };

    const closeModal = () => {
        const modal = document.getElementById("modal-create-project");

        if (modal && window.HSOverlay) {
            window.HSOverlay.close(modal);
        }

        form.reset();
        errors.value = { project_name: '', coverage_segment: '', description: '' };
    };

    onMounted(() => {
        setTimeout(() => {
            if (window.HSOverlay) {
                window.HSOverlay.autoInit();
            }
        }, 100);
    });
</script>


<template>
    <button type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent
                                bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50
                                disabled:pointer-events-none" aria-haspopup="dialog" aria-expanded="false" aria-controls="modal-create-project"
                                data-hs-overlay="#modal-create-project">
        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/>
        </svg>
        Create Project
    </button>

    <div id="modal-create-project" class="hs-overlay  hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="modal-create-project-label" data-hs-overlay-keyboard="false">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">

            <div class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                <h3 id="modal-create-project-label" class="font-bold text-gray-800 dark:text-white">
                Create new project
                </h3>
                <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-400 dark:focus:bg-neutral-600" aria-label="Close" data-hs-overlay="#modal-create-project">
                <span class="sr-only">Close</span>
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <form @submit.prevent="submitForm">
                    <div class="p-4 overflow-y-auto">
                        <div class="space-y-4 sm:space-y-6">

                            <div class="space-y-2">
                                <label for="project_name" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Project Name
                                </label>
                                <input v-model="form.project_name" id="project_name" type="text"
                                    class="py-2 px-3 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Enter project name">
                                <span class="text-red-500 text-sm" v-if="errors.project_name">{{ errors.project_name }}</span>
                            </div>

                            <div class="space-y-2">
                                <label for="start_date" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Start Date
                                </label>
                                <input v-model="form.start_date" id="start_date" type="date"
                                    class="py-2 px-3 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="text-red-500 text-sm" v-if="errors.start_date">{{ errors.start_date }}</span>
                            </div>

                            <div class="space-y-2">
                                <label for="end_date" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                    End Date
                                </label>
                                <input v-model="form.end_date" id="end_date" type="date"
                                    class="py-2 px-3 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="text-red-500 text-sm" v-if="errors.end_date">{{ errors.end_date }}</span>
                            </div>

                            <div class="space-y-2">
                                <label for="coverage_segment" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Coverage/Segment
                                </label>
                                <select v-model="form.coverage_segment" id="coverage_segment"
                                    class="py-2 px-3 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="" disabled>Select a Coverage/Segment</option>
                                    <option>EXPERTALK ONLINE</option>
                                    <option>SIYENSIKAT</option>
                                    <option>RADYO SIYENSYA</option>
                                    <option>BALITANG RAPIDOST</option>
                                    <option>BANTAY BULKAN</option>
                                </select>
                                <span class="text-red-500 text-sm" v-if="errors.coverage_segment">{{ errors.coverage_segment }}</span>
                            </div>

                            <div class="space-y-2">
                                <label for="description" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Description
                                </label>
                                <textarea v-model="form.description" id="description"
                                    class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500"
                                    rows="6" placeholder="Enter project description"></textarea>
                                <span class="text-red-500 text-sm" v-if="errors.description">{{ errors.description }}</span>
                            </div>

                        </div>
                    </div>

                    <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                        <button
                            type="button"
                            class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none"
                            aria-label="Close"
                            @click="closeModal">

                            Close
                        </button>
                        <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                        Save changes
                        </button>
                    </div>
                </form>
            </div>

            </div>
        </div>
    </div>

</template>


