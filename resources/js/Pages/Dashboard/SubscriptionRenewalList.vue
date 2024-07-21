<template>
    <div class="px-0 tracking-tight md:px-4 lg:px-6">
        <div class="py-4 bg-white border-b border-gray-200 mdx1:flex sm:justify-between lg:px-8">
            <h1 class="text-xl font-semibold text-gray-800">Subscription Renewal List</h1>

            <form @submit.prevent="filteringSubscriptions" class="flex-wrap items-end gap-3 mt-2 sm:flex">
                <div class="gap-3 sm:flex">
                    <div>
                        <label for="date-start" class="block text-sm"> Start Date </label>

                        <Calendar v-model="query.start" showIcon iconDisplay="input" inputId="startDate"
                            date-format="dd/mm/yy" :pt="{
                                root: {
                                    class: 'w-full rounded h-9',
                                },
                                input: {
                                    class: 'w-full rounded h-9 border border-gray-300 shadow-sm',
                                },
                            }" />
                    </div>
                    <div>
                        <label for="end-date" class="block text-sm"> End Date </label>

                        <Calendar v-model="query.end" showIcon iconDisplay="input" inputId="endDate"
                            date-format="dd/mm/yy" :pt="{
                                root: {
                                    class: 'w-full rounded h-9',
                                },
                                input: {
                                    class: 'w-full rounded h-9 border border-gray-300 rounded-md shadow-sm',
                                },
                            }" />
                    </div>
                </div>

                <PrimaryButton class="w-full mt-5 sm:w-auto h-9">
                    <span>Search</span>
                    <!-- <i class="block sm:hidden fa-solid fa-magnifying-glass"></i> -->
                </PrimaryButton>
            </form>
        </div>

        <div class="overflow-hidden">
            <div class="text-xs card">
                <Toast />

                <div v-if="isLoading" class="min-h-80">
                    <Skeleton class="my-2 rounded" height="4rem"></Skeleton>

                    <Skeleton class="my-2 rounded" height="16rem"></Skeleton>
                </div>

                <div v-else class="min-h-80">
                    <div v-if="products.length === 0" class="grid place-items-center h-96">
                        <div class="text-center">
                            <i class="text-2xl fa-regular fa-folder-open"></i>
                            <p>No subscription renewals</p>
                        </div>
                    </div>

                    <DataTable v-else ref="dt" :value="products" v-model:selection="selectedUsers" dataKey="id"
                        :paginator="true" :rows="10" :filters="filters"
                        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                        :rowsPerPageOptions="[5, 10, 25]"
                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Transactions"
                        class="font-futura">
                        <template #header>
                            <div
                                class="flex flex-wrap items-center justify-between gap-2 align-items-center justify-content-between">
                                <!-- <h4 class="m-0 text-sm capitalize sm:text-base">
                                Transactions
                            </h4> -->
                                <IconField iconPosition="left">
                                    <InputIcon>
                                        <i class="pi pi-search" />
                                    </InputIcon>
                                    <InputText v-model="filters['global'].value"
                                        class="w-full border border-gray-300 rounded shadow-sm h-9 indent-5"
                                        placeholder="Search..." />
                                </IconField>
                            </div>
                        </template>

                        <Column selectionMode="multiple" class="w-12" :exportable="false"> </Column>
                        <Column field="user" header="Agent" sortable class="min-w-[10rem] whitespace-nowrap"></Column>
                        <Column field="name" header="Subscriber" sortable class="min-w-[10rem] whitespace-nowrap">
                        </Column>
                        <Column field="decoder_no" header="Decoder No" sortable class="min-w-[10rem] whitespace-nowrap">
                        </Column>
                        <Column field="formula" header="Formula" sortable style="min-width: 5rem"></Column>
                        <Column field="duration" header="Duration" sortable class="min-w-[10rem] whitespace-nowrap">
                        </Column>
                        <Column field="method" header="Payment Method" sortable class="min-w-[10rem] whitespace-nowrap">
                        </Column>
                        <Column field="amount" header="Amount" sortable class="min-w-[10rem] whitespace-nowrap">
                        </Column>
                        <Column field="tel" header="Phone Number" sortable class="min-w-[10rem] whitespace-nowrap">
                        </Column>
                        <Column field="created" header="Created At" sortable class="min-w-[10rem] whitespace-nowrap">
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Calendar from "primevue/calendar";
import { FilterMatchMode } from "primevue/api";
import Skeleton from "primevue/skeleton";
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import InputText from "primevue/inputtext";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import moment from "moment";
import { axiosInstance } from "@/mixins/axiosInstance";

onMounted(() => {
    filteringSubscriptions();
});

const toast = useToast();

const isLoading = ref(false);

const query = ref({
    start: "",
    end: "",
});

const dt = ref();
const products = ref([]);
const selectedUsers = ref();
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const formatQueryDate = (value) =>
    value.toString().length > 0 ? moment(value).format("YYYY-MM-DD") : "";

const filteringSubscriptions = () => {
    let startDate = query.value.start === null ? "" : query.value.start;
    let endDate = query.value.end === null ? "" : query.value.end;

    isLoading.value = true;
    axiosInstance
        .post(route("settings.subscriptions.renewals"), {
            start: formatQueryDate(startDate),
            end: formatQueryDate(endDate),
        })
        .then((response) => {
            isLoading.value = false;
            products.value = response.data.data.list;
        })
        .catch((error) => {
            isLoading.value = false;
            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Cannot load subscriptions",
                life: 3000,
            });
        });
};
</script>
