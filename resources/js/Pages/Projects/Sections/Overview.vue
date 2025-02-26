<script setup>

    import 'preline';
    import { ref, onMounted, defineEmits  } from 'vue';
    import { useForm, usePage } from '@inertiajs/vue3';
    import ViewSingleProject from '@/Pages/Projects/ViewSingleProject.vue';

</script>

<template>
    <ViewSingleProject>
        <!-- Content -->
        <div class="w-full lg:ps-64">
            <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
                <div class="mx-auto sm:px-6 lg:px-8">
                    <!-- Grid -->
                    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                        <!-- Card -->
                        <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-900 dark:border-neutral-800">
                            <div class="p-4 md:p-5 flex justify-between gap-x-3">
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">
                                        Created
                                    </p>
                                    <div class="mt-1 flex items-center gap-x-2">
                                        <h3 class="text-xl sm:text-2xl font-medium text-gray-800 dark:text-neutral-200">
                                            0
                                        </h3>
                                    </div>
                                </div>
                                <span class="inline-flex justify-center items-center size-[46px] rounded-full border-4 border-blue-100 bg-blue-200 text-blue-800 dark:border-blue-900 dark:bg-blue-800 dark:text-blue-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-plus"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
                                </span>
                            </div>

                        </div>
                        <!-- End Card -->

                        <!-- Card -->
                        <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-900 dark:border-neutral-800">
                            <div class="p-4 md:p-5 flex justify-between gap-x-3">
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">
                                        In Progress
                                    </p>
                                    <div class="mt-1 flex items-center gap-x-2">
                                        <h3 class="mt-1 text-xl font-medium text-gray-800 dark:text-neutral-200">
                                            0
                                        </h3>
                                    </div>
                                </div>
                                <span class="inline-flex justify-center items-center size-[46px] rounded-full border-4 border-yellow-100 bg-yellow-200 text-yellow-800 dark:border-yellow-900 dark:bg-yellow-800 dark:text-yellow-400">
                                    <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 22h14"/><path d="M5 2h14"/><path d="M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22"/><path d="M7 2v4.172a2 2 0 0 0 .586 1.414L12 12l4.414-4.414A2 2 0 0 0 17 6.172V2"/></svg>
                                </span>
                            </div>

                        </div>
                        <!-- End Card -->

                        <!-- Card -->
                        <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-900 dark:border-neutral-800">
                            <div class="p-4 md:p-5 flex justify-between gap-x-3">
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">
                                        completed
                                    </p>
                                    <div class="mt-1 flex items-center gap-x-2">
                                        <h3 class="text-xl sm:text-2xl font-medium text-gray-800 dark:text-neutral-200">
                                            0
                                        </h3>
                                    </div>
                                </div>
                                <span class="inline-flex justify-center items-center size-[46px] rounded-full border-4 border-teal-100 bg-teal-200 text-teal-800 dark:border-teal-900 dark:bg-teal-800 dark:text-teal-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big"><path d="M21.801 10A10 10 0 1 1 17 3.335"/><path d="m9 11 3 3L22 4"/></svg>
                                </span>
                            </div>

                        </div>
                        <!-- End Card -->

                        <!-- Card -->
                        <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-900 dark:border-neutral-800">
                            <div class="p-4 md:p-5 flex justify-between gap-x-3">
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">
                                        Due Soon
                                    </p>
                                    <div class="mt-1 flex items-center gap-x-2">
                                        <h3 class="mt-1 text-xl font-medium text-gray-800 dark:text-neutral-200">
                                            0
                                        </h3>
                                    </div>
                                </div>
                                <span class="inline-flex justify-center items-center size-[46px] rounded-full border-4 border-red-100 bg-red-200 text-red-800 dark:border-red-900 dark:bg-red-800 dark:text-red-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-alert"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                                </span>
                            </div>

                        </div>
                        <!-- End Card -->
                    </div>

                </div>
            </div>
        </div>
        <!-- End Content -->
    </ViewSingleProject>



</template>
