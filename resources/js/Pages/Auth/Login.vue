<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { vMaska } from 'maska';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    phone: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>

    <Head title="Log in" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="phone" value="Phone Number" />
                <TextInput id="phone" v-model="form.phone" type="text" class="block w-full mt-1" required autofocus
                    autocomplete="username" v-maska data-maska="6 ## ## ## ##" />
                <InputError class="mt-2" :message="form.errors.phone" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />
                <TextInput id="password" v-model="form.password" type="password" class="block w-full mt-1" required
                    autocomplete="current-password" />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox v-model:checked="form.remember" name="remember" />
                    <span class="text-sm text-gray-600 ms-2">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-between mt-4">
                <Link v-if="canResetPassword" :href="route('password.request')"
                    class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none">
                Forgot your password?
                </Link>


            </div>

            <div class="grid mt-6">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    <span>Log in</span>
                </PrimaryButton>
            </div>

            <div class="text-center">
                <Link :href="route('register')"
                    class="text-sm tracking-tight text-gray-600 rounded-md hover:underline hover:font-semibold hover:text-gray-900 focus:outline-none">
                Create an account
                </Link>
            </div>
        </form>
    </AuthenticationCard>
</template>
