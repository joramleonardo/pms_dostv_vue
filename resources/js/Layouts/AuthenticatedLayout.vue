<script setup>
import 'preline';
import { ref, computed, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

import LayoutHeader from './LayoutHeader.vue';
import Sidebar1 from './Sidebar1.vue'; // For Admin (IT Support)
import Sidebar3 from './Sidebar3.vue'; // For Program Manager
import Sidebar4 from './Sidebar4.vue'; // For Assoc Producer & Editing Supervisor

const showingNavigationDropdown = ref(false);
const isDarkMode = ref(false);
const user = computed(() => usePage().props.auth.user); // Get user details

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
        <LayoutHeader />

        <!-- Dynamic Sidebar Rendering Based on User Role -->
        <Sidebar1 v-if="['Supervising Producer', 'admin'].includes(user.role)" />
        <Sidebar3 v-else-if="user.role === 'Program Manager'" />
        <Sidebar4 v-else-if="['Assoc Producer', 'Editing Supervisor'].includes(user.role)" />


        <main>
            <slot />
        </main>
    </div>
</template>
