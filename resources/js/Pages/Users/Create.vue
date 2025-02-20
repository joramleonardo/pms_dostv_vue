<script setup>
    import { ref, onMounted, defineEmits  } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import 'preline';

    const emitRefresh = defineEmits(['refreshTable']);

    const form = useForm({
        name: '',
        username: '',
        role: '',
        email: '',
        password: ''
    });

    const errors = ref({
        name: '',
        username: '',
        role: '',
        email: '',
        password: ''
    });

    const validateForm = () => {
        let valid = true;

        errors.value = {
            name: '',
            username: '',
            role: '',
            email: '',
            password: ''
        };

        if (!form.name.trim()) {
            errors.value.name = "Name is required.";
            valid = false;
        }

        if (!form.username.trim()) {
            errors.value.username = "Username is required.";
            valid = false;
        }

        if (!form.role.trim()) {
            errors.value.role = "Role is required.";
            valid = false;
        }

        if (!form.email.trim()) {
            errors.value.email = "Email is required.";
            valid = false;
        }

        if (!form.password.trim()) {
            errors.value.password = "Password is required.";
            valid = false;
        }

        return valid;
    };

    const createUser = () => {
        if (!validateForm()) return;

        form.post(route('users.store'), {
            onSuccess: () => {
                closeModal();
                form.reset();
                emitRefresh('refreshTable');
            },
            onError: (err) => {
                errors.value = err;
            }
        });
    };

    const closeModal = () => {
        const modal = document.getElementById("create-modal");

        if (modal && window.HSOverlay) {
            window.HSOverlay.close(modal);
        }

        form.reset();
        errors.value = {
            name: '',
            username: '',
            role: '',
            email: '',
            password: ''
        };
    };

    onMounted(() => {
        setTimeout(() => {
            if (window.HSOverlay) {
                window.HSOverlay.autoInit();
            }
        }, 100);
    });

</script>

<template>
    <button type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent
                                bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50
                                disabled:pointer-events-none" aria-haspopup="dialog" aria-expanded="false" aria-controls="create-modal"
                                data-hs-overlay="#create-modal">
        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/>
        </svg>
        Create User
    </button>


    <div id="create-modal" class="hs-overlay  hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="create-modal-label" data-hs-overlay-keyboard="false">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">

            <div class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                <h3 id="create-modal-label" class="font-bold text-gray-800 dark:text-white">
                Create new User
                </h3>
                <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-400 dark:focus:bg-neutral-600" aria-label="Close" data-hs-overlay="#create-modal">
                <span class="sr-only">Close</span>
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <form @submit.prevent="createUser">
                    <div class="p-4 overflow-y-auto">
                        <div class="space-y-4 sm:space-y-6">

                            <div class="space-y-2">
                                <label for="name" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Name
                                </label>
                                <input v-model="form.name" id="name" type="text"
                                    class="py-2 px-3 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Enter name">
                                <span class="text-red-500 text-sm" v-if="errors.name">{{ errors.name }}</span>
                            </div>
                            <div class="space-y-2">
                                <label for="username" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Username
                                </label>
                                <input v-model="form.username" id="username" type="text"
                                    class="py-2 px-3 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Enter username">
                                <span class="text-red-500 text-sm" v-if="errors.username">{{ errors.username }}</span>
                            </div>

                            <div class="space-y-2">
                                <label for="role" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Role
                                </label>
                                <select v-model="form.role" id="role"
                                    class="py-2 px-3 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="" disabled>Select a Role</option>
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                    <option value="editor">Editor</option>
                                    <option value="viewer">Viewer</option>
                                </select>
                                <span class="text-red-500 text-sm" v-if="errors.role">{{ errors.role }}</span>
                            </div>

                            <div class="space-y-2">
                                <label for="email" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Email
                                </label>
                                <input v-model="form.email" id="email" type="text"
                                    class="py-2 px-3 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Enter email">
                                <span class="text-red-500 text-sm" v-if="errors.email">{{ errors.email }}</span>
                            </div>

                            <div class="space-y-2">
                                <label for="password" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Password
                                </label>
                                <input v-model="form.password" id="password" type="password"
                                    class="py-2 px-3 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Enter password">
                                <span class="text-red-500 text-sm" v-if="errors.password">{{ errors.password }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                        <button
                            type="button"
                            class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none"
                            aria-label="Close"
                            @click="closeModal">

                            Close
                        </button>
                        <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                        Create
                        </button>
                    </div>
                </form>
            </div>

            </div>
        </div>
    </div>

        <!-- <div class="bg-white w-96 p-6 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold mb-4">Create User</h3>

            <form @submit.prevent="createUser">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input v-model="form.name" type="text" class="w-full px-3 py-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Username</label>
                    <input v-model="form.username" type="text" class="w-full px-3 py-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Role</label>
                    <select v-model="form.role" class="w-full px-3 py-2 border rounded-lg">
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input v-model="form.email" type="email" class="w-full px-3 py-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input v-model="form.password" type="password" class="w-full px-3 py-2 border rounded-lg">
                </div>

                <div class="flex justify-end space-x-2">
                    <button @click="$emit('close')" type="button" class="bg-gray-300 px-4 py-2 rounded-lg">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        Create
                    </button>
                </div>
            </form>
        </div> -->
</template>
