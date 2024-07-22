<template>
    <AppLayout title="Utilisateurs">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Liste d'utilisateurs
            </h2>
        </template>

        <div class="max-w-screen-xl mx-auto mt-6 sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                <div>
                    <div class="text-xs tracking-tight card">
                        <Toolbar class="mb-4 bg-gray-300 rounded-none">
                            <template #start>
                                <Button label="New" icon="pi pi-plus" severity="contrast"
                                    class="h-8 px-3 mr-2 text-white bg-gray-900 hover:bg-gray-700" @click="openNew" />
                            </template>
                        </Toolbar>

                        <Toast />

                        <DataTable ref="dt" :value="products" v-model:selection="selectedUsers" dataKey="id"
                            :paginator="true" :rows="10" :filters="filters"
                            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                            :rowsPerPageOptions="[5, 10, 25]"
                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords} products">
                            <template #header>
                                <div
                                    class="flex flex-wrap items-center justify-between gap-2 align-items-center justify-content-between">
                                    <h4 class="m-0 text-sm capitalize sm:text-base">
                                        Manage user accounts
                                    </h4>
                                    <IconField iconPosition="left">
                                        <InputIcon>
                                            <i class="pi pi-search" />
                                        </InputIcon>
                                        <InputText v-model="filters['global'].value"
                                            class="text-sm border-gray-300 rounded-md shadow-sm h-9 focus:border-indigo-500 focus:ring-indigo-500 indent-5"
                                            placeholder="Search..." />
                                    </IconField>
                                </div>
                            </template>

                            <Column field="full_name" header="Name" sortable style="min-width: 16rem"></Column>
                            <Column field="role.name" header="Role" sortable style="min-width: 5rem">
                            </Column>
                            <Column field="mail" header="Email" sortable style="min-width: 12rem"></Column>
                            <Column field="phone_number" header="Tel" sortable style="min-width: 12rem"></Column>
                            <Column field="home_address" header="Address" sortable style="min-width: 12rem"></Column>
                            <Column field="city" header="City" sortable style="min-width: 10rem"></Column>
                            <Column field="country_of_origin" header="Country" sortable style="min-width: 10rem">
                            </Column>
                            <Column field="profession" header="Profession" sortable style="min-width: 12rem">
                            </Column>
                            <Column field="created" header="Created At" sortable style="min-width: 12rem">
                            </Column>
                            <Column :exportable="false" style="min-width: 8rem">
                                <template #body="slotProps">
                                    <Button icon="pi pi-pencil" outlined rounded
                                        class="mr-2 text-white bg-gray-900 hover:bg-gray-700"
                                        @click="editUser(slotProps.data)" />
                                    <Button icon="pi pi-trash" outlined rounded
                                        class="text-white bg-red-500 hover:bg-red-400"
                                        @click="confirmDeleteUser(slotProps.data)" />
                                </template>
                            </Column>
                        </DataTable>
                    </div>

                    <Dialog v-model:visible="userDialog" :style="{ width: '450px' }" header="User's Information"
                        :modal="true" class="p-fluid">
                        <div class="field">
                            <label for="role" class="text-sm">Role</label>
                            <Dropdown v-model="user.role" :options="roles" optionLabel="name"
                                placeholder="Choose a role" :pt="{
                                    root: {
                                        class:
                                            'text-sm border border-gray-300 rounded-md shadow-sm h-9 focus:border-indigo-500 focus:ring-indigo-500 indent-5',
                                    },
                                }" />
                        </div>
                        <div class="field">
                            <label for="full_name" class="text-sm">Name</label>
                            <InputText type="text" id="name" v-model.trim="user.full_name"
                                class="border-gray-300 rounded-md shadow-sm h-9 focus:border-indigo-500 focus:ring-indigo-500"
                                required="true" autofocus />
                            <InputError class="mt-2" :message="form.errors.full_name" />
                        </div>
                        <div v-if="isEditingUser" class="field">
                            <label for="mail" class="text-sm">Email</label>
                            <InputText type="text" id="email" v-model.trim="user.mail"
                                class="border-gray-300 rounded-md shadow-sm h-9 focus:border-indigo-500 focus:ring-indigo-500" />
                            <InputError class="mt-2" :message="form.errors.mail" />
                        </div>
                        <div class="field">
                            <label for="phone" class="text-sm">Phone Number</label>
                            <InputText type="text" id="phone" v-model.trim="user.phone_number"
                                class="border-gray-300 rounded-md shadow-sm h-9 focus:border-indigo-500 focus:ring-indigo-500"
                                required="true" v-maska data-maska="6 ## ## ## ##" />
                            <InputError class="mt-2" :message="form.errors.phone_number" />
                        </div>
                        <div v-if="isEditingUser" class="field">
                            <label for="address" class="text-sm">Address</label>
                            <InputText type="text" id="address" v-model.trim="user.home_address"
                                class="border-gray-300 rounded-md shadow-sm h-9 focus:border-indigo-500 focus:ring-indigo-500" />
                            <InputError class="mt-2" :message="form.errors.home_address" />
                        </div>
                        <div v-if="isEditingUser" class="field">
                            <label for="city" class="text-sm">City</label>
                            <InputText type="text" id="city" v-model.trim="user.city"
                                class="border-gray-300 rounded-md shadow-sm h-9 focus:border-indigo-500 focus:ring-indigo-500" />
                            <InputError class="mt-2" :message="form.errors.city" />
                        </div>
                        <div v-if="isEditingUser" class="field">
                            <label for="country" class="text-sm">Country</label>
                            <InputText type="text" id="country" v-model.trim="user.country_of_origin"
                                class="border-gray-300 rounded-md shadow-sm h-9 focus:border-indigo-500 focus:ring-indigo-500" />
                            <InputError class="mt-2" :message="form.errors.country_of_origin" />
                        </div>
                        <div v-if="isEditingUser" class="field">
                            <label for="profession" class="text-sm">Profession</label>
                            <InputText type="text" id="profession" v-model.trim="user.profession"
                                class="border-gray-300 rounded-md shadow-sm h-9 focus:border-indigo-500 focus:ring-indigo-500" />
                            <InputError class="mt-2" :message="form.errors.profession" />
                        </div>
                        <div class="field">
                            <label for="password" class="text-sm">Password</label>
                            <InputText type="text" id="password" v-model.trim="user.password"
                                class="border-gray-300 rounded-md shadow-sm h-9 focus:border-indigo-500 focus:ring-indigo-500" />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>
                        <div class="field">
                            <label for="password_confirmation" class="text-sm">
                                Password Confirmation
                            </label>
                            <InputText type="text" id="password_confirmation" v-model.trim="user.password_confirmation"
                                class="border-gray-300 rounded-md shadow-sm h-9 focus:border-indigo-500 focus:ring-indigo-500" />
                            <InputError class="mt-2" :message="form.errors.password_confirmation" />
                        </div>

                        <template #footer>
                            <div class="space-x-2">
                                <Button label="Cancel" icon="pi pi-times" severity="danger" text
                                    class="px-2 py-1 text-gray-900 bg-transparent border border-gray-900 hover:bg-gray-900 hover:text-gray-50"
                                    @click="hideDialog" />
                                <Button label="Save" icon="pi pi-check" text :loading="isLoading"
                                    class="px-2 py-1 text-gray-900 border border-green-500 hover:bg-green-400 hover:text-gray-50"
                                    @click="saveUser" />
                            </div>
                        </template>
                    </Dialog>

                    <Dialog v-model:visible="deleteUserDialog" :style="{ width: '450px' }" header="Confirm"
                        :modal="true">
                        <div class="flex items-center">
                            <i class="mr-3 pi pi-exclamation-triangle" style="font-size: 2rem" />
                            <span v-if="user">
                                Are you sure you want to delete <b>{{ user.full_name }}</b>?
                            </span>
                        </div>
                        <template #footer>
                            <div class="space-x-2">
                                <Button label="No" severity="danger" icon="pi pi-times" text
                                    class="px-2 py-0.5 border text-gray-900 border-gray-900 hover:bg-gray-900 hover:text-gray-50"
                                    @click="deleteUserDialog = false" />
                                <Button label="Yes" icon="pi pi-check" text :loading="isLoading"
                                    class="px-2 py-0.5 border text-gray-900 border-red-500 hover:bg-red-500 hover:text-gray-50"
                                    @click="deleteUser" />
                            </div>
                        </template>
                    </Dialog>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import { FilterMatchMode } from "primevue/api";
import Button from "primevue/button";
import Toolbar from "primevue/toolbar";
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import InputText from "primevue/inputtext";
import InputError from "@/Components/InputError.vue";
import Dropdown from "primevue/dropdown";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Dialog from "primevue/dialog";
import Toast from "primevue/toast";
import AppLayout from "@/Layouts/AppLayout.vue";
import { vMaska } from "maska";
import { useToast } from "primevue/usetoast";
import { axiosInstance } from "@/mixins/axiosInstance";

onMounted(() => (products.value = props.users.data));

const props = defineProps({
    users: Object,
});

const toast = useToast();

const toastTimeout = ref(3000);

const form = useForm({});

const dt = ref();
const products = ref();
const userDialog = ref(false);
const deleteUserDialog = ref(false);
const isLoading = ref(false);
const isEditingUser = ref(false);
const user = ref({});
const selectedUsers = ref();
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const roles = [
    { id: 1, name: "Admin" },
    { id: 2, name: "User" },
];

const userSubmitRoute = computed(() =>
    isEditingUser.value ? route("users.update.account") : route("users.create.new")
);

const openNew = () => {
    user.value = {};
    isEditingUser.value = false;
    userDialog.value = true;
};

const hideDialog = () => userDialog.value = false;;

const saveUser = () => {
    isLoading.value = true;
    Object.keys(user.value).map((key) => {
        if (user.value[key] === 'N/A') {
            user.value[key] = '';
        }
    });
    axiosInstance
        .post(userSubmitRoute.value, user.value)
        .then((response) => {
            console.log('users list: ', response.data);
            products.value = response.data.data;
            userDialog.value = false;
            user.value = {};
            isLoading.value = false;
            isEditingUser.value = false;
            toast.add({
                severity: "success",
                summary: "Success",
                detail: "User infos updated",
                life: toastTimeout.value,
            });
        }).catch((error) => {
            isLoading.value = false;
            Object.values(error).forEach((message) => {
                toast.add({
                    severity: "error",
                    summary: "Error",
                    detail: message,
                    life: toastTimeout.value,
                });
            });
        })
};

const editUser = (prod) => {
    user.value = { ...prod };
    isEditingUser.value = true;
    userDialog.value = true;
};

const confirmDeleteUser = (prod) => {
    user.value = prod;
    deleteUserDialog.value = true;
};

const deleteUser = () => {
    isLoading.value = true;

    axiosInstance
        .post(route("users.delete.account", user.value.id))
        .then((response) => {
            isLoading.value = false;
            products.value = products.value.filter((val) => val.id !== user.value.id);
            deleteUserDialog.value = false;
            user.value = {};
            toast.add({
                severity: "success",
                summary: "Success",
                detail: response.data.message,
                life: toastTimeout.value,
            });
        }).catch((error) => {
            isLoading.value = false;
        });
};
</script>
