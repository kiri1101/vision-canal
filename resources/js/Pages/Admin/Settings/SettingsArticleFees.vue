<template>
    <div class="max-w-screen-xl px-5 py-3 mx-2 mt-2 tracking-tight bg-white rounded shadow sm:mx-auto">
        <Toast />

        <p class="py-2 text-lg font-medium leading-tight text-gray-800 border-b border-gray-300">
            Articles Fees
        </p>

        <ul class="mt-4 space-y-2">
            <li v-for="(article, index) in props.articles" :key="index" class="grid items-center grid-cols-3">
                <p>{{ article.title }}</p>

                <p>{{ article.image }}</p>

                <div class="flex justify-end">
                    <Button icon="fa-solid fa-pen-to-square" severity="contrast" label="Edit"
                        class="h-8 px-3 text-white bg-gray-900 hover:bg-gray-700"
                        @click.prevent="openModal(article.id)" />
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
                    Update the following article:
                    <span class="font-bold underline capitalize">{{ activeArticle.title }}</span>
                </span>
                <div class="space-y-1">
                    <div class="grid">
                        <label for="title" class="text-sm font-semibold"> Title </label>
                        <InputText id="title" v-model="form.title"
                            class="border-gray-300 rounded-md shadow-sm h-9 focus:border-indigo-500 focus:ring-indigo-500"
                            autocomplete="off" />
                    </div>
                    <div class="grid">
                        <label for="description" class="text-sm font-semibold"> Description </label>
                        <InputText id="description" v-model="form.desc"
                            class="border-gray-300 rounded-md shadow-sm h-9 focus:border-indigo-500 focus:ring-indigo-500" />
                    </div>
                    <div class="grid">
                        <label for="prize" class="text-sm font-semibold"> Prize/fcfa </label>
                        <InputText id="prize" v-model="form.prize"
                            class="border-gray-300 rounded-md shadow-sm h-9 focus:border-indigo-500 focus:ring-indigo-500" />
                    </div>
                    <div class="grid space-y-2">
                        <label for="image" class="text-sm font-semibold"> Image upload </label>
                        <SettingsFileUpload :url="`articles/${activeArticle.id}/upload`"
                            @update-file="updatingArticlePreview" />
                        <!-- https://primefaces.org/cdn/primevue/images/galleria/galleria10.jpg -->
                        <label for="image" class="text-sm font-semibold"> Image preview </label>
                        <Skeleton v-if="showImagePreviewLoader" width="15.7rem" height="14.5rem"
                            class="justify-self-center"></Skeleton>

                        <Image v-else :src="imagePreview" alt="Image" width="250" class="justify-self-center" preview />
                    </div>
                </div>
                <template #footer>
                    <div class="mt-2 space-x-2">
                        <Button label="Cancel" class="h-8 px-3 border border-red-300 hover:bg-red-500/40"
                            @click.prevent="closeModal" autofocus />
                        <Button label="Save" class="h-8 px-3 border border-teal-300 hover:bg-green-500/40"
                            :loading="form.processing" @click.prevent="updateSocial(activeArticle.id)" autofocus />
                    </div>
                </template>
            </Dialog>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import Button from "primevue/button";
import Avatar from "primevue/avatar";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import Image from "primevue/image";
import Skeleton from "primevue/skeleton";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import SettingsFileUpload from "./SettingsFileUpload.vue";


const props = defineProps({
    articles: Array,
});

const toast = useToast();

const visible = ref(false);

const imagePreview = ref('');

const showImagePreviewLoader = ref(false);

const activeArticle = ref({});

const form = useForm({
    title: "",
    desc: "",
    prize: "",
});

const openModal = (articleId) => {
    let result = props.articles.find((article) => article.id === articleId);
    if (result !== undefined) {
        activeArticle.value = result;
        updateForm(result);
        updatingArticlePreview(result.image);
        visible.value = true;
    }
};

const closeModal = () => {
    visible.value = false;
    activeArticle.value = {};
};

const updateForm = (data) => {
    form.title = data.title;
    form.desc = data.description;
    form.prize = data.prize.toString();
};

const updatingArticlePreview = (path) => {
    showImagePreviewLoader.value = true;
    imagePreview.value = path;
    setTimeout(() => {
        showImagePreviewLoader.value = false;
    }, 2000);
};

const updateSocial = (articleId) => {
    form.post(route('settings.store.article', articleId), {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Success",
                detail: "Article data updated",
                life: 3000,
            });
        },
        onError: () => {
            toast.add({
                severity: "error",
                summary: "Rejected",
                detail: "Update failed! Try again",
                life: 3000,
            });
        }
    })
};
</script>
