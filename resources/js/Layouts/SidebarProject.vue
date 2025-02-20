
<script setup>
import { Link } from '@inertiajs/vue3';

</script>

<template>
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Projects</h2>

    </div>

    <!-- Recent Projects -->
    <div class="mt-4 space-y-2">
    <button class="flex items-center w-full px-3 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">
        Kanban-Project A
    </button>
    <button class="flex items-center w-full px-3 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">
        Support 1
    </button>
    <button class="flex items-center w-full px-3 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">
        Support
    </button>
    <button class="flex items-center w-full px-3 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">
        Project A
    </button>
    </div>

    <!-- View All Projects -->
    <!-- <button class="mt-4 w-full px-3 py-2 text-sm font-semibold text-blue-600 hover:bg-gray-100 dark:hover:bg-gray-800 dark:text-blue-400">
    View all projects
    </button>
     -->
    <Link
        :href="route('projects.index')"
        :class="`flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg
                hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-500
                dark:text-white
                ${route().current('projects.index') ? 'bg-gray-700 text-white hover:bg-gray-600 ' : ''}`">
        View all projects
    </Link>


</template>
