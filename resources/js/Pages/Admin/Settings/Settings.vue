<template>
    <AppLayout title="Settings">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight tracking-tight text-gray-800">
                Settings
            </h2>
        </template>

        <Toast />

        <div class="space-y-4">
            <SettingsSocialLink :settings="settings" />

            <SettingsArticleFees :articles="articles" />
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Toast from "primevue/toast";
import SettingsSocialLink from "./SettingsSocialLink.vue";
import SettingsArticleFees from "./SettingsArticleFees.vue";
import SettingsFileUpload from "./SettingsFileUpload.vue";

const props = defineProps({
    settings: Object,
    articles: Array,
});
</script>
