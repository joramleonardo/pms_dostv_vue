<script setup>

    import 'preline';
    import { defineProps, onMounted  } from 'vue';
    import ViewSingleProject from '@/Pages/Projects/ViewSingleProject.vue';


    const props = defineProps({
        project: {
            type: Object,
            required: true
        }
    });

    onMounted(() => {
        console.log('Component mount ', props.project.id);
    })

</script>

<template>
    <ViewSingleProject :project="props.project">
        <!-- Content -->
        <div class="w-full lg:ps-64">
            <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">

            </div>
        </div>
        <!-- End Content -->
    </ViewSingleProject>



</template>
