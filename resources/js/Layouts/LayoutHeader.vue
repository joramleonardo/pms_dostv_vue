<script setup>
    import 'preline';
    import { ref, onMounted, watch } from 'vue';
    import { usePage, router } from '@inertiajs/vue3';
    import Dropdown from '@/Components/Dropdown.vue';
    import DropdownLink from '@/Components/DropdownLink.vue';
    import Sidebar from './Sidebar.vue';



    const showingNavigationDropdown = ref(false);
    const isDarkMode = ref(false);

    const page = usePage(); // Access page props
    const authUser = page.props.auth?.user ?? {}; // Ensure authUser is not undefined

    const toggleDarkMode = () => {
        isDarkMode.value = !isDarkMode.value;

        if (isDarkMode.value) {
            document.documentElement.classList.add('dark');
            document.body.classList.add('dark:bg-gray-900');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            document.body.classList.remove('dark:bg-gray-900');
            localStorage.setItem('theme', 'light');
        }
    };

    // Function to reinitialize Preline
    const initializePreline = () => {
        setTimeout(() => {
            if (window.HSStaticMethods) {
                window.HSStaticMethods.autoInit();
            }
        }, 100);
    };

    onMounted(() => {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark');
            document.body.classList.add('dark:bg-gray-900');
            isDarkMode.value = true;
        } else {
            document.documentElement.classList.remove('dark');
            document.body.classList.remove('dark:bg-gray-900');
            isDarkMode.value = false;
        }

        initializePreline();
    });

    // Watch for route changes and reinitialize Preline
    watch(() => page.url, () => {
        initializePreline();
    });

</script>

<template>

    <header class="sticky top-0 inset-x-0 flex flex-wrap md:justify-start md:flex-nowrap z-[48] w-full bg-white border-b text-sm py-2.5 lg:ps-[260px] dark:bg-neutral-800 dark:border-neutral-700">
        <nav class="px-4 sm:px-6 flex basis-full items-center w-full mx-auto">
            <div class="me-5 lg:me-0 lg:hidden bg-gray-800  p-3">
            <a class="flex-none rounded-md text-xl inline-block font-semibold focus:outline-none focus:opacity-80" href="#" aria-label="Preline">
                <img src="/img/logo1.png" alt="Application Logo" class="w-40 h-auto">
            </a>

            <div class="lg:hidden ms-1">

            </div>
            </div>

            <div class="w-full flex items-center justify-end ms-auto md:justify-between gap-x-1 md:gap-x-3">

                <div class="hidden md:block">
                    <div class="relative">

                    </div>
                </div>

                <div class="flex flex-row items-center justify-end gap-1">
                    <button
                        class="inline-flex items-center gap-x-2 py-2 px-3 rounded-full text-sm focus:outline-none transition-colors duration-300"
                        :class="isDarkMode ? 'bg-gray-900 text-white hover:bg-gray-800' : 'bg-gray-100 text-gray-900 hover:bg-gray-200'"
                        @click="toggleDarkMode">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle v-if="!isDarkMode" cx="12" cy="12" r="4"></circle>
                            <path v-if="!isDarkMode" d="M12 2v2"></path>
                            <path v-if="!isDarkMode" d="M12 20v2"></path>
                            <path v-if="!isDarkMode" d="m4.93 4.93 1.41 1.41"></path>
                            <path v-if="!isDarkMode" d="m17.66 17.66 1.41 1.41"></path>
                            <path v-if="!isDarkMode" d="M2 12h2"></path>
                            <path v-if="!isDarkMode" d="M20 12h2"></path>
                            <path v-if="!isDarkMode" d="m6.34 17.66-1.41 1.41"></path>
                            <path v-if="!isDarkMode" d="m19.07 4.93-1.41 1.41"></path>
                            <path v-if="isDarkMode" d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
                        </svg>

                    </button>
                    <!-- <button type="button" class="size-[38px] relative inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                        <span class="sr-only">Notifications</span>
                    </button> -->

                    <div class="hs-dropdown [--placement:bottom-right] relative inline-flex">
                        <button id="hs-dropdown-account" type="button" class="size-[38px] inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 focus:outline-none disabled:opacity-50 disabled:pointer-events-none dark:text-white" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                            <img class="inline-block size-8 rounded-full ring-2 ring-white dark:ring-neutral-900"
                                :src="'https://ui-avatars.com/api/?name=' + $page.props.auth.user.name + '&background=random'"
                                :alt="$page.props.auth.user.name">
                        </button>

                        <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg mt-2 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700 after:h-4 after:absolute after:-bottom-4 after:start-0 after:w-full before:h-4 before:absolute before:-top-4 before:start-0 before:w-full" role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-account">
                            <div class="py-3 px-5 bg-gray-100 rounded-t-lg dark:bg-neutral-700">
                            <p class="text-sm text-gray-500 dark:text-neutral-500">Signed in as</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-neutral-200">
                                {{ $page.props.auth.user.name }}
                            </p>
                            </div>
                            <div class="p-1.5 space-y-0.5">

                                <DropdownLink :href="route('profile.edit')"
                                            class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800
                                            hover:bg-gray-100 focus:outline-none focus:bg-gray-100
                                            dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700 dark:focus:text-neutral-300"
                                            >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="shrink-0 size-4">
                                            <circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/></svg>
                                    Profile
                                </DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button"
                                            class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800
                                            hover:bg-gray-100 focus:outline-none focus:bg-gray-100
                                            dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700 dark:focus:text-neutral-300"
                                            >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="shrink-0 size-4">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                                    Log Out
                                </DropdownLink>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
</template>
