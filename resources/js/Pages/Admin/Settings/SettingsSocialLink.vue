<template>
    <div class="max-w-screen-xl px-5 py-3 mx-2 mt-2 tracking-tight bg-white rounded shadow sm:mx-auto">
        <Toast />

        <p class="py-2 text-lg font-medium leading-tight text-gray-800 border-b border-gray-300">
            Social Links
        </p>

        <ul class="mt-4 space-y-2">
            <li class="grid items-center grid-cols-3">
                <p>Home youtube video</p>

                <p>{{ settings.homeBanner }}</p>

                <div class="flex justify-end">
                    <Button @click.prevent="openModal('youtube')" icon="fa-solid fa-pen-to-square" severity="contrast"
                        label="Edit" class="h-8 px-3 text-white bg-gray-900 hover:bg-gray-700" />
                </div>
            </li>
            <li class="grid items-center grid-cols-3">
                <p>WhatsApp</p>

                <p>{{ settings.whatsappLink }}</p>

                <div class="flex justify-end">
                    <Button @click.prevent="openModal('whatsapp')" icon="fa-solid fa-pen-to-square" severity="contrast"
                        label="Edit" class="h-8 px-3 text-white bg-gray-900 hover:bg-gray-700" />
                </div>
            </li>
            <li class="grid items-center grid-cols-3">
                <p>Facebook</p>

                <p>{{ settings.facebookLink }}</p>

                <div class="flex justify-end">
                    <Button @click.prevent="openModal('facebook')" icon="fa-solid fa-pen-to-square" severity="contrast"
                        label="Edit" class="h-8 px-3 text-white bg-gray-900 hover:bg-gray-700" />
                </div>
            </li>
            <li class="grid items-center grid-cols-3">
                <p>Instagram</p>

                <p>{{ settings.instagramLink }}</p>

                <div class="flex justify-end">
                    <Button @click.prevent="openModal('instagram')" icon="fa-solid fa-pen-to-square" severity="contrast"
                        label="Edit" class="h-8 px-3 text-white bg-gray-900 hover:bg-gray-700" />
                </div>
            </li>
        </ul>

        <div class="flex card justify-content-center">
            <Dialog v-model:visible="visible" modal header="Edit Profile" :style="{ width: '25rem' }">
                <template #header>
                    <div class="inline-flex gap-2 align-items-center justify-content-center">
                        <Avatar image="https://primefaces.org/cdn/primevue/images/avatar/amyelsner.png"
                            shape="circle" />
                        <span class="font-bold white-space-nowrap">
                            {{ $page.props.auth.user.name }}
                        </span>
                    </div>
                </template>
                <span class="block mb-5 p-text-secondary">
                    Update the {{ activeSocialLink }} account link.
                </span>
                <div class="grid">
                    <label :for="activeSocialLink" class="text-sm font-semibold w-6rem">Link URL</label>
                    <InputText :id="activeSocialLink" v-model="form[activeSocialLink]" autocomplete="off"
                        class="border-gray-300 rounded-md shadow-sm h-9 focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                <template #footer>
                    <Button label="Cancel" text severity="secondary"
                        class="h-8 px-3 border border-red-300 hover:bg-red-500/40" @click.prevent="closeModal"
                        autofocus />
                    <Button label="Save" outlined severity="severity"
                        class="h-8 px-3 border border-teal-300 hover:bg-green-500/40" :loading="api.processing"
                        @click.prevent="updateSocial(activeSocialLink)" autofocus />
                </template>
            </Dialog>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import Toast from "primevue/toast";
import Avatar from "primevue/avatar";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import { useToast } from "primevue/usetoast";

const props = defineProps({
    settings: Object,
});

const toast = useToast();

const visible = ref(false);

const activeSocialLink = ref("");

const api = useForm({});

const form = ref({
    whatsapp: "",
    facebook: "",
    instagram: "",
    youtube: "",
});

const openModal = (activeLink) => {
    activeSocialLink.value = activeLink;
    visible.value = true;
};

const closeModal = () => {
    activeSocialLink.value = "";
    visible.value = false;
};

const updateSocial = (type) => {
    api
        .transform((data) => ({
            ...data,
            type: type,
            content: form.value[type],
        }))
        .post(route("settings.store.social"), {
            onSuccess: () => {
                toast.add({
                    severity: "success",
                    summary: "Success",
                    detail: "Saved successfully",
                    life: 3000,
                });
                closeModal();
            },
            onError: () => {
                toast.add({
                    severity: "error",
                    summary: "Rejected",
                    detail: "Operation failed. Try again",
                    life: 3000,
                });
            },
        });
};
</script>
