<template>
  <div class="tracking-tight">
    <div class="overflow-hidden">
      <div class="text-xs card">
        <Toast />

        <div v-if="transactions.data.length === 0" class="grid place-items-center h-96">
          <div class="text-center">
            <i class="text-2xl fa-regular fa-folder-open"></i>
            <p>No transactions</p>
          </div>
        </div>

        <DataTable
          v-else
          ref="dt"
          :value="products"
          v-model:selection="selectedUsers"
          dataKey="id"
          :paginator="true"
          :rows="10"
          :filters="filters"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 25]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Transactions"
        >
          <template #header>
            <div
              class="flex flex-wrap items-center justify-between gap-2 align-items-center justify-content-between"
            >
              <!-- <h4 class="m-0 text-sm capitalize sm:text-base">
                                Transactions
                            </h4> -->
              <IconField iconPosition="left">
                <InputIcon>
                  <i class="pi pi-search" />
                </InputIcon>
                <InputText v-model="filters['global'].value" placeholder="Search..." />
              </IconField>
            </div>
          </template>

          <Column selectionMode="multiple" style="width: 3rem" :exportable="false">
          </Column>
          <Column header="Status">
            <template #body="slotProps">
              <Tag
                v-if="slotProps.data.type === 'Deposit'"
                :value="slotProps.data.type"
                severity="success"
              />
              <Tag
                v-if="slotProps.data.type === 'Withdrawal'"
                :value="slotProps.data.type"
                severity="warn"
              />
            </template>
          </Column>
          <Column
            field="amount"
            header="Amount"
            sortable
            style="min-width: 5rem"
          ></Column>
          <Column
            field="commission"
            header="Commission"
            sortable
            style="min-width: 5rem"
          ></Column>
          <Column
            field="sender"
            header="Sender"
            sortable
            class="min-w-[10rem] whitespace-nowrap"
          ></Column>
          <Column
            field="sender_account"
            header="Sender Account"
            sortable
            class="min-w-[10rem] whitespace-nowrap"
          >
          </Column>
          <Column
            field="receiver"
            header="Receiver"
            sortable
            class="min-w-[10rem] whitespace-nowrap"
          >
          </Column>
          <Column
            field="receiver_account"
            header="Receiver Account"
            sortable
            class="min-w-[10rem] whitespace-nowrap"
          >
          </Column>
          <Column
            field="date_created"
            header="Created At"
            sortable
            class="min-w-[10rem] whitespace-nowrap"
          >
          </Column>
        </DataTable>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
// import { useForm, router } from '@inertiajs/vue3';
import { FilterMatchMode } from "primevue/api";
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import InputText from "primevue/inputtext";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Tag from "primevue/tag";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";

onMounted(() => (products.value = props.transactions.data));

const props = defineProps({
  transactions: Object,
});

const toast = useToast();

const dt = ref();
const products = ref();
const selectedUsers = ref();
const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
</script>
