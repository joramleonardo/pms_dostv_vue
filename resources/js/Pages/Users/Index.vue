<script setup>
        import { ref, onMounted, defineProps, watch } from 'vue';
        import { useForm, router, Head  } from '@inertiajs/vue3';
        import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
        import CreateUser from '@/Pages/Users/Create.vue';
        import SearchFilter from '@/Pages/Users/SearchFilter.vue';
        import axios from 'axios';


        const props = defineProps({
            users: Object
        });

        const users = ref(props.users || { data: [] });


        const searchQuery = ref('');

        const statusFilter = ref('all');
        const editForm = useForm({
            id: '',
            name: '',
            username: '',
            password: '',
            role: ''
        });

        const fetchUsers = (page = 1) => {
            router.get(route('users.index'), {
                search: searchQuery.value,
                status: statusFilter.value,
                page: page
            }, {
                preserveScroll: true,
                preserveState: true,
                onSuccess: (response) => {
                    users.value = response.props.users ? { ...response.props.users } : { data: [] };
                }
            });
        };

        const openEditModal = (user) => {
            editForm.id = user.id;
            editForm.name = user.name;
            editForm.username = user.username;
            editForm.role = user.role;
            document.getElementById('edit-modal').classList.remove('hidden');
        };

        const updateUser = () => {
            const payload = {
                name: editForm.name,
                username: editForm.username,
                role: editForm.role
            };

            if (editForm.password) {
                payload.password = editForm.password;
            }

            editForm.put(route('users.update', editForm.id), {
                data: payload,
                preserveScroll: true,
                onSuccess: () => {
                    closeEditModal();
                    fetchUsers();
                }
            });
        };

        const closeEditModal = () => {
            document.getElementById('edit-modal').classList.add('hidden');
        };

        const deactivateUser = (id) => {

            if (confirm("Are you sure you want to deactivate this user?" + id)) {

                axios.put(route("users.deactivate", id)).then(res=>{
                    if (res.data.status === "deactivated"){
                        alert(res.data.message);
                        fetchUsers();
                    }
                });

                // router.put(route("users.deactivate", id), {

                //     onSuccess: (data) => {
                //         console.log(data);
                //         console.log("ACCOUNT SUCCESSFULLY DEACTIVATED!!");
                //         fetchUsers();
                //     }
                // });
            }
        };

        // const deleteUser = (id) => {
        //     if (confirm("Are you sure you want to delete this user?")) {
        //         axios.delete(route("users.destroy", id)).then(res => {
        //             if (res.data.status === "deleted") {
        //                 alert(res.data.message);
        //                 fetchUsers();
        //             }
        //         }).catch(error => {
        //             console.error("There was an error deleting the user:", error);
        //         });
        //     }
        // };

        const activateUser = (id) => {
            if (confirm("Are you sure you want to activate this user?" + id)) {

                axios.put(route("users.activate", id)).then(res=>{
                    if (res.data.status === "activated"){
                        alert(res.data.message);
                        fetchUsers();
                    }
                });
            }
        };

        watch(searchQuery, () => {
            fetchUsers();
        });

        onMounted(() => {
            fetchUsers();
        });

</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>

        <div class="w-full lg:ps-64">
            <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="px-6 py-4 flex justify-between items-center border-b border-gray-200 dark:border-neutral-700">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                                Users
                            </h2>
                            <div class="flex flex-col gap-x-2 items-center sm:flex-row">
                                <select v-model="statusFilter" @change="fetchUsers" class="px-3 py-2 border rounded-lg w-full">
                                    <option value="all">All Users</option>
                                    <option value="active">Active Users</option>
                                    <option value="deactivated">Deactivated Users</option>
                                </select>
                                <div class="w-full" >
                                    <SearchFilter v-model="searchQuery" />
                                </div>
                                <div class="w-full">
                                    <CreateUser  @refreshTable="fetchUsers()" />
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto mt-4">
                            <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">#</th>
                                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Name</th>
                                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Username</th>
                                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Role</th>
                                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Account Status</th>
                                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Actions</th>
                                    </tr>
                                </thead>
                                <tbody v-if="users && users.data">
                                    <tr v-for="(user, index) in users.data" :key="user.id" class="border-b">
                                        <td class="py-3 px-4 text-sm text-gray-700">{{ index + 1 }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-700">{{ user.name }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-700">{{ user.username }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-700">{{ user.role }}</td>
                                        <td class="py-3 px-4 text-sm font-semibold"
                                            :class="user.is_active ? 'text-green-600' : 'text-red-600'">
                                            {{ user.is_active ? 'Active' : 'Not Active' }}
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-700">
                                            <button @click="openEditModal(user)" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                                Edit
                                            </button>

                                            <button v-if="user.is_active" @click="deactivateUser(user.id)"
                                                class="ml-2 px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                                Deactivate
                                            </button>

                                            <button v-else @click="activateUser(user.id)"
                                                class="ml-2 px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                                                Activate
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="5" class="text-center py-4">Loading users...</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="flex justify-between items-center mt-6">
                                <button
                                    @click="fetchUsers(users.current_page - 1)"
                                    :disabled="users.current_page === 1"
                                    class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-md shadow-sm bg-white text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Previous
                                </button>

                                <div class="space-x-2">
                                    <button
                                        v-for="page in users.last_page"
                                        :key="page"
                                        @click="fetchUsers(page)"
                                        :class="[
                                            'px-4 py-2 text-sm font-medium border rounded-md shadow-sm',
                                            users.current_page === page
                                                ? 'bg-blue-600 text-white hover:bg-blue-700'
                                                : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                                        ]">
                                        {{ page }}
                                    </button>
                                </div>

                                <button
                                    @click="fetchUsers(users.current_page + 1)"
                                    :disabled="users.current_page === users.last_page"
                                    class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-md shadow-sm bg-white text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="edit-modal" class="hs-overlay hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
            <div class="bg-white w-96 p-6 rounded-lg shadow-lg">
                <h3 class="text-lg font-semibold mb-4">Edit User</h3>
                <form @submit.prevent="updateUser">
                    <div class="space-y-2">
                        <label for="name" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                            Full Name
                        </label>
                        <input v-model="editForm.name" type="text" placeholder="Full Name" class="w-full px-3 py-2 border rounded-lg mb-2">
                    </div>
                    <div class="space-y-2">
                        <label for="username" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                            Username
                        </label>
                        <input v-model="editForm.username" type="text" placeholder="Username" class="w-full px-3 py-2 border rounded-lg mb-2">
                    </div>
                    <div class="space-y-2">
                        <label for="password" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                            Password (optional)
                        </label>
                        <input v-model="editForm.password" type="password" placeholder="New Password" class="w-full px-3 py-2 border rounded-lg mb-2">
                    </div>
                    <div class="space-y-2">
                        <label for="role" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                            Role
                        </label>
                        <select v-model="editForm.role" class="w-full px-3 py-2 border rounded-lg mb-2">
                            <option value="" disabled>Select a Role</option>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                            <option value="editor">Editor</option>
                            <option value="viewer">Viewer</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 w-full mt-2">Update</button>
                    </div>

                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
