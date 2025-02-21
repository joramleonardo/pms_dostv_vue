
<script setup>
import { ref, onMounted } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import Sidebar from './Sidebar.vue';
import SidebarProject from './SidebarProject.vue';

</script>


<template>

    <!-- ========== MAIN CONTENT ========== -->
    <div class="-mt-px">
    <!-- Breadcrumb -->
    <div class="sticky top-0 inset-x-0 z-20 bg-white border-y px-4 sm:px-6 lg:px-8 lg:hidden dark:bg-neutral-800 dark:border-neutral-700">
        <div class="flex items-center py-2">
        <!-- Navigation Toggle -->
        <button type="button" class="size-8 flex justify-center items-center gap-x-2 border border-gray-200 text-gray-800 hover:text-gray-500 rounded-lg focus:outline-none focus:text-gray-500 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:text-neutral-200 dark:hover:text-neutral-500 dark:focus:text-neutral-500" aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-application-sidebar" aria-label="Toggle navigation" data-hs-overlay="#hs-application-sidebar">
            <span class="sr-only">Toggle Navigation</span>
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M15 3v18"/><path d="m8 9 3 3-3 3"/></svg>
        </button>
        <!-- End Navigation Toggle -->

        <!-- Breadcrumb -->
        <ol class="ms-3 flex items-center whitespace-nowrap">
            <li class="flex items-center text-sm text-gray-800 dark:text-neutral-400">
            Sidebar
            <svg class="shrink-0 mx-3 overflow-visible size-2.5 text-gray-400 dark:text-neutral-500" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            </li>
            <li class="text-sm font-semibold text-gray-800 truncate dark:text-neutral-400" aria-current="page">
            Dashboard
            </li>
        </ol>
        <!-- End Breadcrumb -->
        </div>
    </div>
    <!-- End Breadcrumb -->
    </div>



    <!-- Sidebar -->
    <div id="hs-application-sidebar" class="hs-overlay  [--auto-close:lg]
    hs-overlay-open:translate-x-0
    -translate-x-full transition-all duration-300 transform
    w-[260px] h-full
    hidden
    fixed inset-y-0 start-0 z-[60]
    bg-white border-e border-gray-200
    lg:block lg:translate-x-0 lg:end-auto lg:bottom-0
    dark:bg-gray-800 dark:border-gray-700" role="dialog" tabindex="-1" aria-label="Sidebar">
    <div class="relative flex flex-col h-full max-h-full">
        <div class="px-6 pt-4 flex items-center bg-gray-800 p-3">
            <!-- Logo -->
            <a class="flex-none rounded-xl text-xl inline-block font-semibold focus:outline-none focus:opacity-80 " href="#" aria-label="PMS">
                <img src="/img/logo1.png" alt="Application Logo" class="w-40 h-auto">
            </a>
            <!-- End Logo -->

            <div class="hidden lg:block ms-2">

            </div>
        </div>

        <!-- Content -->
        <div class="h-full overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500">
        <nav class="hs-accordion-group p-3 w-full flex flex-col flex-wrap" data-hs-accordion-always-open>
            <ul class="flex flex-col space-y-1">

            <!-- Dashboard -->
            <li>
                <Link
                    :href="route('dashboard')"
                    :active="route().current('dashboard')"


                    :class="`flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg
                            hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-500
                            dark:text-white
                            ${route().current('dashboard') ? 'bg-gray-700 text-white hover:bg-gray-600 ' : ''}`">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                Dashboard
                </Link>
            </li>
            <!-- Members -->
            <li>
                <Link
                    :href="route('users.index')"
                    :class="`flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg
                            hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-500
                            dark:text-white
                            ${route().current('users.index') ? 'bg-gray-700 text-white hover:bg-gray-600 ' : ''}`">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-round"><path d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="8" r="5"/><path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/></svg>
                Users
                </Link>
            </li>
            <hr style="margin: 15px;">

            <SidebarProject />

            </ul>
        </nav>
        </div>
        <!-- End Content -->
    </div>
    </div>
    <!-- End Sidebar -->





</template>
