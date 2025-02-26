
<script setup>
    import 'preline';
    import { Link } from '@inertiajs/vue3';
    import { ref, onMounted } from 'vue';
    import { useEventBus } from "@/eventBus";
    import axios from 'axios';


    const eventBus = useEventBus();
    const recentProjects = ref([]);

    onMounted(() => {
        fetchRecentProjects();
    });

    const fetchRecentProjects = async () => {
        try {
            const response = await axios.get("/projects/recent");
            recentProjects.value = response.data;
        } catch (error) {
            console.error("❌ Error fetching recent projects:", error);
        }
    };

    eventBus.on("projectCreated", () => {
        fetchRecentProjects();
    });

    onMounted(() => {
        fetchRecentProjects();
    });

</script>

<template>
    <li>
        <Link
            :href="route('projects.index')"
            :class="`flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg
                    hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-500
                    dark:text-white
                    ${route().current('projects.index') ? 'bg-cyan-600 text-white hover:bg-cyan-700 hover:text-white ' : ''}`">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-align-justify"><path d="M3 12h18"/><path d="M3 18h18"/><path d="M3 6h18"/></svg>
        Projects
        </Link>
    </li>


    <div class="mt-4 space-y-2">
        <span class="font-medium text-xs">Recent Projects</span>
        <ul>
            <li v-for="project in recentProjects" :key="project.id" class="mb-2">
                <Link
                    :href="route('overview.index')"
                    :class="`text-xs flex items-center gap-x-3.5 py-2 px-2.5 text-gray-800 rounded-lg
                            hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-500
                            dark:text-white
                            `">
                            <!-- ${route().current('dashboard') ? 'bg-gray-700 text-white hover:bg-gray-600 ' : ''}`"> -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-folder-kanban"><path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z"/><path d="M8 10v4"/><path d="M12 10v2"/><path d="M16 10v6"/></svg>
                            {{ project.project_name }}
                </Link>
            </li>
        </ul>
    </div>

</template>
