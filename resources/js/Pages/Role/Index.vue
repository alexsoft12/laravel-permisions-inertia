<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Roles
            </h2>
        </template>

        <div class="p-5">


            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- This example requires Tailwind CSS v2.0+ -->
                    <div class="flex flex-col">

                        <div class="py-2 text-right align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <a href="#" class="inline-block bg-blue-500 hover:bg-blue-700 p-2 rounded text-white"
                               @click="addRole">
                                New role
                            </a>
                        </div>

                        <div class=" overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                        <tr class="bg-gray-400 text-white font-bold">
                                            <th scope="col" class="px-6 py-3 text-left  uppercase tracking-wider">
                                                Role
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left uppercase tracking-wider">
                                                Action
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="role in roles" :key="role.id">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <!--                                                        <img class="h-10 w-10 rounded-full" src="#" alt="">-->
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ role.name }}

                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                                <a href="#" @click="editRole(role)"
                                                   class="inline-block bg-blue-500 hover:bg-blue-700 p-2 rounded text-white ">edit</a>
                                                <a href="#" @click="confirmRoleDeletion(role)"
                                                   class="inline-block bg-red-500 hover:bg-red-700 p-2 rounded text-white ">Remove</a>
                                            </td>
                                        </tr>

                                        <!-- More items... -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- New and Edit Role Confirmation Modal -->
            <jet-dialog-modal :show="openingModel" @close="closeModal">
                <template #title>
                    {{ editMode ? 'Edit' : 'New' }} role
                </template>

                <template #content>
                    <div class="mt-4">
                        <label class="mt-1 block w-3/4" for="name">Role name</label>
                        <jet-input type="text" class=" block w-3/4" placeholder="Role name"
                                   ref="name"
                                   id="name"
                                   :value="form.name"
                                   v-model="form.name"/>

                        <label class="my-5 block w-3/4">Permissions :</label>
                        <div v-for="permission in availablePermissions" :key="permission.id" class="my-3 block w-3/4">
                            <label class="flex items-center">
                                <input type="checkbox" :value="permission.name" v-model="form.permissions"
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-gray-600">{{ permission.name }}</span>
                            </label>
                        </div>

                        <jet-input-error :message="form.errors.name" class="mt-2"/>
                    </div>
                </template>

                <template #footer>
                    <jet-secondary-button @click.native="closeModal">
                        Cancel
                    </jet-secondary-button>

                    <jet-button class="ml-2" @click.native="saveRole" v-show="!editMode"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing">
                        Save
                    </jet-button>
                    <jet-button class="ml-2" @click.native="updateRole" v-show="editMode"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing">
                        Update
                    </jet-button>
                </template>
            </jet-dialog-modal>

            <!-- Delete role Confirmation Modal -->
            <jet-confirmation-modal :show="roleBeingDeleted" @close="roleBeingDeleted = null">
                <template #title>
                    Delete role
                </template>

                <template #content>
                    Are you sure you would like to delete this role?
                </template>

                <template #footer>
                    <jet-secondary-button @click.native="roleBeingDeleted = null">
                        Cancel
                    </jet-secondary-button>

                    <jet-danger-button class="ml-2" @click.native="deleteRole" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Delete
                    </jet-danger-button>
                </template>
            </jet-confirmation-modal>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetDialogModal from '@/Jetstream/DialogModal'
import jetInput from '@/Jetstream/Input'
import jetInputError from '@/Jetstream/InputError'
import jetButton from '@/Jetstream/Button'
import jetSecondaryButton from '@/Jetstream/SecondaryButton'
import JetConfirmationModal from '@/Jetstream/ConfirmationModal'
import JetDangerButton from '@/Jetstream/DangerButton'


export default {
    props: ['roles', 'availablePermissions'],


    components: {
        AppLayout,
        JetDialogModal,
        jetInput,
        jetInputError,
        jetButton,
        jetSecondaryButton,
        JetConfirmationModal,
        JetDangerButton,

    },
    data() {
        return {
            openingModel: false,
            editMode: false,
            form: this.$inertia.form({
                name: '',
                permissions: [],
                id: null
            }),
            roleBeingDeleted: false
        }
    },
    methods: {
        openModal() {
            this.openingModel = true;
            setTimeout(() => this.$refs.name.focus(), 250)
        },
        addRole(){
            this.openModal()
            this.editMode = false;
        },
        saveRole() {
            this.form.post(route('roles.store'), {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    this.openingModel = true
                    this.closeModal()
                    this.form.reset()
                }
            })
        },
        closeModal() {
            this.openingModel = false
            this.form.reset()
            this.form.permissions = []
        },
        editRole(role) {
            role.permissions.forEach(element => this.form.permissions.push(element.name))
            this.form.name = role.name
            this.form.id = role.id
            this.openingModel = true;
            this.editMode = true;
        },
        updateRole() {

            this.form.put(route('roles.update', this.form.id), {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {

                    this.closeModal()
                    this.form.reset()
                },
            })
        },
        confirmRoleDeletion(role) {
            this.roleBeingDeleted = role
        },
        deleteRole() {
            this.form.delete(route('roles.destroy', this.roleBeingDeleted.id), {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => (this.roleBeingDeleted = null),
            })
        },

    },

}
</script>
