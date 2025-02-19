
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {  Head, Link, useForm  } from '@inertiajs/vue3';

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>

        <!-- Content -->
        <div class="w-full lg:ps-64">
            <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">WELCOME to DASHBOARD PAGE!</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Content -->

    </AuthenticatedLayout>
</template>
