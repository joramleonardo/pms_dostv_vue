<script setup>

    import 'preline';
    import { Link } from '@inertiajs/vue3';

    import SidebarProject from './SidebarProject4.vue';
</script>

<template>
    <!-- <div id="hs-application-sidebar" class="hs-overlay lg:fixed inset-y-0 start-0 z-10 w-[260px] h-full bg-white border-e border-gray-200 dark:bg-neutral-800 dark:border-neutral-700"> -->

    <div id="hs-application-sidebar" class="hs-overlay  [--auto-close:lg]
        hs-overlay-open:translate-x-0
        -translate-x-full transition-all duration-300 transform
        w-[260px] h-full
        hidden
        fixed inset-y-0 start-0 z-[60]
        bg-white border-e border-gray-200
        lg:block lg:translate-x-0 lg:end-auto lg:bottom-0
        dark:bg-neutral-800 dark:border-neutral-700" role="dialog" tabindex="-1" aria-label="Sidebar">
        <div class="relative flex flex-col h-full max-h-full">
            <div class="px-6 pt-4 flex items-center bg-gray-800  p-3">
            <!-- Logo -->
            <a class="flex-none rounded-xl text-xl inline-block font-semibold focus:outline-none focus:opacity-80" href="#" aria-label="Preline">
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
                                    ${route().current('dashboard') ? 'bg-cyan-600 text-white hover:bg-cyan-700 hover:text-white ' : ''}`">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                        Dashboard
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
</template>
