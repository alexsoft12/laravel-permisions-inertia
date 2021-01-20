<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
               Users
            </h2>
        </template>


        <div class="p-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- This example requires Tailwind CSS v2.0+ -->
                    <div class="flex flex-col">

                        <div class="py-2 text-right align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <a href="#" class="inline-block bg-blue-500 hover:bg-blue-700 p-2 rounded text-white"
                               @click="addUser">
                                New user
                            </a>
                        </div>
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Name
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Email
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Role
                                            </th>
                                            <th scope="col" class="relative px-6 py-3">
                                                <span class="sr-only">Edit</span>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="user in users" :key="user.id">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <!--                                                        <img class="h-10 w-10 rounded-full" src="#" alt="">-->
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ user.name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ user.email }}</div>

                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <span v-for="role in user.roles" :key="role.id">
                                                   {{ role.name }}
                                               </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="#" @click="editUser(user)"
                                                   class="inline-block bg-blue-500 hover:bg-blue-700 p-2 rounded text-white ">edit</a>
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
            <jet-dialog-modal :show="managingUser" @close="managingUser = null">
                <template #title>
                    {{ editMode ? 'Edit' : 'New' }} user
                </template>

                <template #content>
                    <div class="w-full mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                        <div>
                            <jet-label for="name" value="Name"/>
                            <jet-input id="name" type="text" class="mt-1 block w-3/4" v-model="form.name" required
                                       autofocus autocomplete="name"/>
                            <jet-input-error :message="form.errors.name" class="mt-2"/>
                        </div>
                        <div class="mt-4">
                            <jet-label for="email" value="Email"/>
                            <jet-input id="email" type="email" class="mt-1 block w-3/4" v-model="form.email" required/>
                            <jet-input-error :message="form.errors.email" class="mt-2"/>
                        </div>
                        <div class="mt-4">
                            <jet-label for="password" value="Password"/>
                            <jet-input id="password" type="password" class="mt-1 block w-3/4" v-model="form.password"
                                        autocomplete="new-password"/>
                            <jet-input-error :message="form.errors.password" class="mt-2"/>
                        </div>

                        <div class="mt-4">

                            <jet-label for="password_confirmation" value="Confirm Password"/>
                            <jet-input id="password_confirmation" type="password" class="mt-1 block w-3/4"
                                       v-model="form.password_confirmation"  autocomplete="new-password"/>
                            <jet-input-error :message="form.errors.password_confirmation" class="mt-2" />
                        </div>

                        <div class="mt-4">

                            <label for="role_id">Role</label>
                            <select v-model="form.role_id" id="role_id" class="mt-1 block w-3/4">
                                <option v-for="role in roles" :value="role.id"> {{ role.name }}</option>
                            </select>
                            <jet-input-error :message="form.errors.role_id" class="mt-2" />
                        </div>


                    </div>
                </template>

                <template #footer>
                    <jet-secondary-button @click.native="managingUser = null">
                        Cancel
                    </jet-secondary-button>

                    <jet-button class="ml-2" @click.native="saveUser" v-show="!editMode"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing">
                        Save
                    </jet-button>
                    <jet-button class="ml-2" @click.native="updateUser" v-show="editMode"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing">
                        Update
                    </jet-button>
                </template>
            </jet-dialog-modal>
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
import JetLabel from '@/Jetstream/Label'
import Label from "@/Jetstream/Label"



export default {
    props: ['users', 'roles'],

    components: {
        Label,
        AppLayout,
        JetDialogModal,
        jetInput,
        jetInputError,
        jetButton,
        jetSecondaryButton,
        JetConfirmationModal,
        JetDangerButton,
        JetLabel,

    },
    data() {
        return {
            editMode: false,
            form: this.$inertia.form({
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                role_id: ''
            }),

            managingUser: null,

        }
    },
    methods: {
        addUser(){
            this.editMode = false
            this.managingUser = []
        },
        editUser(user) {
            this.editMode = true
            this.form.name = user.name
            this.form.email = user.email
            this.form.role_id = user.roles.length !== 0 ? user.roles[0].id : null
            this.managingUser = user
        },
        updateUser() {
/*            if (this.form.password_confirmation === this.form.password) {
                password_confirmation.setCustomValidity('');
            } else {
                password_confirmation.setCustomValidity('Passwords do not match');
                return 0;
            }*/

            this.form.put(route('users.update', this.managingUser), {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => (this.managingUser = null),
            })
        },
        saveUser() {
            this.form.post(route('users.store'), {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => (this.managingUser = null),
            })
        },
    }
}
</script>
