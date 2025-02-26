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
                    CALENDAR VIEW
                </div>
            </div>
        </div>
        <!-- End Content -->
    </ViewSingleProject>
</template>
