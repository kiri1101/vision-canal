<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight tracking-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <Toast />

        <div class="py-12 tracking-tight">
            <div class="mx-auto space-y-4 max-w-7xl sm:px-6 lg:px-8">
                <section id="stats-center">
                    <ul class="grid gap-3 grid-cols-auto-fill-20">
                        <li class="grid px-3 py-5 bg-white rounded-md shadow h-80 grid-rows-auto-frame">
                            <div class="flex justify-between">
                                <div>
                                    <h2 class="text-xs font-semibold text-gray-500 uppercase">
                                        Total budget
                                    </h2>

                                    <p class="text-2xl font-bold">
                                        {{ budget.total }}
                                    </p>

                                    <div class="space-x-1">
                                        <i class="text-green-700 rotate-45 fa-solid fa-arrow-up-long"></i>

                                        <span class="text-sm text-green-700">+ 2.5%</span>

                                        <span class="text-sm">last year</span>
                                    </div>
                                </div>

                                <div>
                                    <span class="px-3 py-1.5 rounded font-bold text-lg text-white bg-gray-900">$</span>
                                </div>
                            </div>

                            <div>
                                <PolarChart :label="polaLabels" :series="polarSeries" />
                            </div>
                        </li>
                        <li class="grid px-3 py-5 bg-white rounded-md shadow h-80 grid-rows-auto-frame">
                            <div class="flex justify-between">
                                <div>
                                    <h2 class="text-xs font-semibold text-gray-500 uppercase">
                                        Total Abonnement
                                    </h2>

                                    <p class="text-2xl font-bold">
                                        {{ subs.number }}
                                    </p>

                                    <div class="space-x-1">
                                        <i class="text-green-700 rotate-45 fa-solid fa-arrow-up-long"></i>

                                        <span class="text-sm text-green-700">+ 2.5%</span>

                                        <span class="text-sm">last year</span>
                                    </div>
                                </div>

                                <div>
                                    <span class="px-3 py-1.5 rounded font-bold text-lg text-white bg-gray-900">
                                        <i class="fa-solid fa-satellite-dish"></i>
                                    </span>
                                </div>
                            </div>

                            <div>
                                <LineChart :x-axis="subscriptionAxis" :series="props.subs.list" />
                            </div>
                        </li>
                        <li class="grid px-3 py-5 bg-white rounded-md shadow h-80 grid-rows-auto-frame">
                            <div class="flex justify-between">
                                <div>
                                    <h2 class="text-xs font-semibold text-gray-500 uppercase">
                                        Total Reabonnement
                                    </h2>

                                    <p class="text-2xl font-bold">FCFA10,000,000</p>

                                    <div class="space-x-1">
                                        <i class="text-green-700 rotate-45 fa-solid fa-arrow-up-long"></i>

                                        <span class="text-sm text-green-700">+ 2.5%</span>

                                        <span class="text-sm">last year</span>
                                    </div>
                                </div>

                                <div>
                                    <span class="px-3 py-1.5 rounded font-bold text-lg text-white bg-gray-900">
                                        <i class="fa-solid fa-rotate"></i>
                                    </span>
                                </div>
                            </div>

                            <div>
                                <LineChart :x-axis="axis" :series="area1ChartSeries" />
                            </div>
                        </li>
                    </ul>
                </section>

                <section id="transactions" class="overflow-hidden bg-white shadow card sm:rounded-lg">
                    <TransactionIndex />
                </section>

                <section id="money-transfer">
                    <div class="space-y-6 overflow-hidden leading-tight">
                        <div class="space-y-4">
                            <ul class="grid gap-6 md:grid-cols-2" aria-label="Send money form">
                                <li class="px-6 py-4 space-y-4 bg-white shadow lg:px-8 sm:rounded-lg">
                                    <h2 class="text-xl font-semibold text-gray-800 underline">
                                        Make a deposit
                                    </h2>

                                    <form @submit.prevent="makeDeposit">
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <InputLabel for="user" value="User" />

                                                <Dropdown v-model="deposit.user" filter :options="userList"
                                                    optionLabel="name" placeholder="Choose a user" :pt="{
                                                        root: {
                                                            class: {
                                                                'w-full mt-1 h-9 border border-gray-300 rounded-md shadow-sm': true,
                                                                'ring-2 ring-red-500 ring-offset-1':
                                                                    deposit.errors.user?.length > 0,
                                                            },
                                                        },
                                                        filterInput: {
                                                            class: 'w-full h-8 rounded-md border border-gray-300',
                                                        },
                                                    }">
                                                    <template #option="slotProps">
                                                        <div class="flex text-sm align-items-center">
                                                            {{ slotProps.option.name }} ({{ slotProps.option.tel }})
                                                        </div>
                                                    </template>
                                                </Dropdown>
                                            </div>

                                            <div>
                                                <InputLabel for="account" value="Account" />
                                                <Dropdown v-model="deposit.account" :options="accounts"
                                                    optionLabel="name" placeholder="Choose an account" :pt="{
                                                        root: {
                                                            class: {
                                                                'w-full mt-1 h-9 border border-gray-300 rounded-md shadow-sm': true,
                                                                'ring-2 ring-red-500 ring-offset-1':
                                                                    deposit.errors.account?.length > 0,
                                                            },
                                                        },
                                                    }" />
                                            </div>
                                        </div>

                                        <div>
                                            <InputLabel for="amount" value="Amount" />
                                            <TextInput v-model="deposit.amount" type="number" :class="{
                                                'block w-full mt-1': true,
                                                'ring-2 ring-red-500 ring-offset-1':
                                                    deposit.errors.amount?.length > 0,
                                            }" min="0" required />
                                        </div>

                                        <div class="grid mt-6">
                                            <PrimaryButton :class="{ 'opacity-25': deposit.processing }"
                                                :disabled="deposit.processing">
                                                <span>Submit</span>
                                            </PrimaryButton>
                                        </div>
                                    </form>
                                </li>
                                <li class="px-6 py-4 space-y-4 bg-white shadow lg:px-8 sm:rounded-lg">
                                    <h2 class="text-xl font-semibold text-gray-800 underline">
                                        Withdrawal
                                    </h2>

                                    <form @submit.prevent="makeWithdrawal">
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <InputLabel for="user" value="User" />
                                                <Dropdown v-model="withdraw.user" filter :options="userList"
                                                    optionLabel="name" placeholder="Choose a user" :pt="{
                                                        root: {
                                                            class: {
                                                                'w-full mt-1 h-9 border border-gray-300 rounded-md shadow-sm': true,
                                                                'ring-2 ring-red-500 ring-offset-1':
                                                                    withdraw.errors.user?.length > 0,
                                                            },
                                                        },
                                                        filterInput: {
                                                            class: 'w-full h-8 rounded-md border border-gray-300',
                                                        },
                                                    }">
                                                    <template #option="slotProps">
                                                        <div class="flex text-sm align-items-center">
                                                            {{ slotProps.option.name }} ({{ slotProps.option.tel }})
                                                        </div>
                                                    </template>
                                                </Dropdown>
                                            </div>

                                            <div>
                                                <InputLabel for="account" value="Account" />
                                                <Dropdown v-model="withdraw.account" :options="accounts"
                                                    optionLabel="name" placeholder="Choose an account" :pt="{
                                                        root: {
                                                            class: {
                                                                'w-full mt-1 h-9 border border-gray-300 rounded-md shadow-sm': true,
                                                                'ring-2 ring-red-500 ring-offset-1':
                                                                    deposit.errors.account?.length > 0,
                                                            },
                                                        },
                                                    }" />
                                            </div>
                                        </div>

                                        <div>
                                            <InputLabel for="amount" value="Amount" />
                                            <TextInput v-model="withdraw.amount" type="number" :class="{
                                                'block w-full mt-1': true,
                                                'ring-2 ring-red-500 ring-offset-1':
                                                    withdraw.errors.amount?.length > 0,
                                            }" min="0" required />
                                        </div>

                                        <div class="grid mt-6">
                                            <PrimaryButton :class="{ 'opacity-25': withdraw.processing }"
                                                :disabled="withdraw.processing">
                                                <span>Submit</span>
                                            </PrimaryButton>
                                        </div>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>

                <section id="subscriptions" class="overflow-hidden bg-white shadow card sm:rounded-lg">
                    <SubscriptionList />
                </section>

                <section id="subscriptions-renewal" class="overflow-hidden bg-white shadow card sm:rounded-lg">
                    <SubscriptionRenewalList />
                </section>

                <section id="support" class="overflow-hidden bg-white shadow card sm:rounded-lg">
                    <SupportList />
                </section>

                <section id="orders" class="overflow-hidden bg-white shadow card sm:rounded-lg">
                    <OrderList />
                </section>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import TransactionIndex from "@/Components/Transactions/TransactionIndex.vue";
import SubscriptionList from "./SubscriptionList.vue";
import SubscriptionRenewalList from "./SubscriptionRenewalList.vue";
import SupportList from "./SupportList.vue";
import OrderList from "./OrderList.vue";
import LineChart from "@/Components/Charts/LineChart.vue";
import PolarChart from "@/Components/Charts/PolarChart.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Dropdown from "primevue/dropdown";
import TextInput from "@/Components/TextInput.vue";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";

const props = defineProps({
    userList: Object,
    accounts: Object,
    subs: Object,
    budget: Object,
});

const toast = useToast();

const deposit = useForm({
    user: {},
    account: {},
    amount: "",
});

const withdraw = useForm({
    user: {},
    account: {},
    amount: "",
});

const polarSeries = ref([42, 68, 52, 78, 65, 75, 38, 90, 26, 58, 95, 75]);

const polaLabels = ref([
    "Jan",
    "Fev",
    "Mars",
    "Avr",
    "Mai",
    "Ju",
    "Jui",
    "Aou",
    "Sep",
    "Oct",
    "Nov",
    "Dec",
]);

const area1ChartSeries = [
    {
        name: "series1",
        data: [31, 40, 28, 51, 42, 109, 100, 0, 0, 300, 0, 0],
    },
];

const subscriptionAxis = {
    type: "category",
    categories: [
        "Jan",
        "Fev",
        "Mars",
        "Avr",
        "Mai",
        "Ju",
        "Jui",
        "Aou",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
    ],
};

const axis = {
    type: "category",
    categories: [
        "Jan",
        "Fev",
        "Mars",
        "Avr",
        "Mai",
        "Ju",
        "Jui",
        "Aou",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
    ],
};

const makeDeposit = () => {
    deposit.post(route("deposit.store"), {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Success",
                detail: "Deposit successful",
                life: 3000,
            });
            setTimeout(() => {
                router.visit(route("dashboard"));
            }, 3100);
        },
        onError: (error) => {
            Object.values(error).every((err) => {
                toast.add({
                    severity: "error",
                    summary: "Error",
                    detail: err,
                    life: 3000,
                });
            });
        },
        preserveScroll: true,
    });
};

const makeWithdrawal = () => {
    withdraw.post(route("withdraw.store"), {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Success",
                detail: "Withdrawal successful",
                life: 3000,
            });
            setTimeout(() => {
                router.visit(route("dashboard"));
            }, 3100);
        },
        onError: (errors) => {
            Object.values(errors).every((err) => {
                toast.add({
                    severity: "error",
                    summary: "Error",
                    detail: err,
                    life: 3000,
                });
            });
        },
        preserveScroll: true,
    });
};
</script>
