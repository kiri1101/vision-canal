<template>
    <div>
        <Toast />

        <file-pond name="file" ref="pond" label-idle="Click or drop file here..." v-bind:allow-multiple="false"
            v-bind:files="myFiles" v-on:init="handleFilePondInit" min-file-size="10KB" max-file-size="3MB"
            :accepted-file-types="authorizedFileTypes" />
    </div>
</template>

<script setup>
import { ref } from "vue";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import vueFilePond, { setOptions } from "vue-filepond";
import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import FilePondPluginFileValidateSize from "filepond-plugin-file-validate-size";

const props = defineProps({
    url: String,
});

const emit = defineEmits(["updateFile"]);

const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview,
    FilePondPluginFileValidateSize
);

const toast = useToast();

const myFiles = ref([]);

const authorizedFileTypes = ["image/png", "image/jpeg", "image/jpg"];

const handleFilePondInit = () => {
    setOptions({
        server: {
            process: {
                url: props.url,
                method: "POST",
                onload: (response) => fileUploaded(response),
                // ondata: (formData) => {
                //   formData.append("uuid", props.id);
                //   return formData;
                // },
                onerror: (errors) => {
                    console.log(errors);
                    toast.add({
                        severity: "error",
                        summary: "Rejected",
                        detail: JSON.parse(errors).message,
                        life: 3000,
                    });
                },
            },
        },
    });
};

const fileUploaded = (response) => {
    let file = JSON.parse(response).data.file_path;
    console.log("file upload response: ", response, "file image path: ", file);
    toast.add({
        severity: "success",
        summary: "Success",
        detail: "File uploaded",
        life: 3000,
    });
    emit("updateFile", file);
};
</script>
