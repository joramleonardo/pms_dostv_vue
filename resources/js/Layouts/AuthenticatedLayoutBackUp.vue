<script setup>
    import 'preline';
    import { ref, onMounted } from 'vue';
    import Dropdown from '@/Components/Dropdown.vue';
    import DropdownLink from '@/Components/DropdownLink.vue';
    import Sidebar from './Sidebar.vue';

const showingNavigationDropdown = ref(false);
const isDarkMode = ref(false);

// Function to toggle dark mode globally
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

// Apply dark mode on page load
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
});
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
        <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 transition-colors duration-300">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- <div class="shrink-0 flex items-center">
                            <Link :href="route('dashboard')">
                                <ApplicationLogo
                                    class="block h-9 w-auto fill-current text-gray-800 dark:text-white"
                                />
                            </Link>
                        </div>

                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                Dashboard
                            </NavLink>
                        </div> -->
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <!-- Dark Mode Toggle Button -->
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
                            {{ isDarkMode ? "Light Mode" : "Dark Mode" }}
                        </button>

                        <!-- Settings Dropdown -->
                        <div class="ms-3 relative">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-300 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-100 focus:outline-none transition ease-in-out duration-150"
                                        >
                                            {{ $page.props.auth.user.name }}
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        <header class="bg-white dark:bg-gray-800 shadow" v-if="$slots.header">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <Sidebar />

        <main  class="mt-16">
            <slot />
        </main>
    </div>
</template>
