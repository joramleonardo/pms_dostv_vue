

<script setup>
        import 'preline';
        import { Head, Link, usePage } from '@inertiajs/vue3';

        import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
        import CreateTask from '@/Pages/Projects/Sections/CreateTask.vue';



        const project = usePage().props.project || {};

        const props = defineProps({
            project: {
                type: Object,
                required: true
            }
        });

        console.log("Project Data:", project);

</script>


<template>
    <AuthenticatedLayout>
        <Head title="Project" />



        <!-- Secondary Navbar class="mt-2 md:pl-[260px]"-->
        <!-- <div class="relative z-20 w-full bg-white border-b border-gray-200 dark:bg-neutral-800 dark:border-neutral-700 md:pl-[260px]"> -->

        <div class="relative z-10 w-full bg-white border-b border-gray-200 dark:bg-neutral-800 dark:border-neutral-700 mt-2 md:pl-[260px]">

            <div class="w-full mx-auto md:flex md:items-center md:gap-3 px-4 sm:px-6 lg:px-8">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <!-- DISPLAY PROJECT ID HERE -->
                    {{ project.project_name}}
                </h1>
            </div>
        <nav class="w-full mx-auto md:flex md:items-center md:gap-3 px-4 sm:px-6 lg:px-8">

            <!-- Collapse -->
            <!-- <div id="hs-secondaru-navbar" class="hs-collapse hidden overflow-hidden transition-all duration-300 basis-full grow md:block" aria-labelledby="hs-secondaru-navbar-collapse"> -->
            <div id="hs-secondaru-navbar" class="hs-collapse block md:block overflow-hidden transition-all duration-300 basis-full grow">

                <div class="overflow-hidden overflow-y-auto max-h-[75vh] [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500">
                <div class="py-2 md:py-0 flex flex-col md:flex-row md:items-center gap-y-0.5 md:gap-y-0 ">

                    <CreateTask :project-id="props.project.id" class="mr-1"/>

                    <Link
                        :href="route('overview.index', props.project.id)"
                        :class="`flex items-center gap-x-2 py-2 px-2 text-sm text-gray-800 rounded-lg
                                hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-500
                                dark:text-white m-2
                                ${route().current('overview.index') ? 'bg-cyan-600 text-white hover:bg-cyan-700 hover:text-white ' : ''}`">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-panels-top-left"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                        Overview
                    </Link>
                    <Link
                        :href="route('list.index', props.project.id)"
                        :class="`flex items-center gap-x-2 py-2 px-2 text-sm text-gray-800 rounded-lg
                                hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-500
                                dark:text-white m-2
                                ${route().current('list.index') ? 'bg-cyan-600 text-white hover:bg-cyan-800 hover:text-white ' : ''}`">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-align-justify"><path d="M3 12h18"/><path d="M3 18h18"/><path d="M3 6h18"/></svg>
                        List
                    </Link>

                    <Link
                        :href="route('timeline.index', props.project.id)"
                        :class="`flex items-center gap-x-2 py-2 px-2 text-sm text-gray-800 rounded-lg
                                hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-500
                                dark:text-white m-2
                                ${route().current('timeline.index') ? 'bg-cyan-600 text-white hover:bg-cyan-700 hover:text-white ' : ''}`">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-no-axes-gantt"><path d="M8 6h10"/><path d="M6 12h9"/><path d="M11 18h7"/></svg>
                        Timeline
                    </Link>

                    <Link
                        :href="route('kanban.index', props.project.id)"
                        :class="`flex items-center gap-x-2 py-2 px-2 text-sm text-gray-800 rounded-lg
                                hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-500
                                dark:text-white m-2
                                ${route().current('kanban.index') ? 'bg-cyan-600 text-white hover:bg-cyan-700 hover:text-white ' : ''}`">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-kanban"><path d="M6 5v11"/><path d="M12 5v6"/><path d="M18 5v14"/></svg>
                        Kanban
                    </Link>
                    <Link
                        :href="route('calendar.index', props.project.id)"
                        :class="`flex items-center gap-x-2 py-2 px-2 text-sm text-gray-800 rounded-lg
                                hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-500
                                dark:text-white m-2
                                ${route().current('calendar.index') ? 'bg-cyan-600 text-white hover:bg-cyan-700 hover:text-white ' : ''}`">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                        Calendar
                    </Link>
                    <Link
                        :href="route('attachments.index', props.project.id)"
                        :class="`flex items-center gap-x-2 py-2 px-2 text-sm text-gray-800 rounded-lg
                                hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-500
                                dark:text-white m-2ocus:bg-gray-100 dark:bg-neutral-500
                                dark:text-white
                                ${route().current('attachments.index') ? 'bg-cyan-600 text-white hover:bg-cyan-700 hover:text-white ' : ''}`">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-paperclip"><path d="M13.234 20.252 21 12.3"/><path d="m16 6-8.414 8.586a2 2 0 0 0 0 2.828 2 2 0 0 0 2.828 0l8.414-8.586a4 4 0 0 0 0-5.656 4 4 0 0 0-5.656 0l-8.415 8.585a6 6 0 1 0 8.486 8.486"/></svg>
                        Attachments
                    </Link>
                </div>
                </div>
            </div>
            <!-- End Collapse -->
            </nav>

        </div>
        <!-- End Secondary Navbar -->

        <main>
            <slot />
        </main>

    </AuthenticatedLayout>
</template>
