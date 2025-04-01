<script setup>
import {Link, useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {QuillEditor} from "@vueup/vue-quill";
import {ref} from "vue";
import axios from "axios";

const avatarInputRef = ref(null);
const loading = ref(false);
const error = ref(null);
const previewUrl = ref(null);

const onAvatarClick = () => {
    if (avatarInputRef.value) {
        avatarInputRef.value.click();
    }
};
const onAvatarChange = async (e) => {
    form.avatar = e.target.files[0];
    if (form.avatar) {
        previewUrl.value = URL.createObjectURL(form.avatar);
    }
    console.log(form.avatar)
};

const form = useForm({
    avatar: '',
    name: '',
    description: '',
    is_free: true,
    subscription_price: null,
})

const submit = () => {
    form.post(route('account.channels.store'))
}
</script>

<script>
import AccountLayout from "@/Layouts/AccountLayout.vue";

export default {
    layout: AccountLayout
}
</script>

<template>
    <div class="flex justify-between items-center mb-7">
        <h1 class="text-2xl font-bold">Create Channel</h1>
        <Link :href="route('account.channels.index')" class="btn-primary bg-aqua-700">Back</Link>
    </div>
    <form @submit.prevent="submit" class="flex flex-col max-w-[600px] gap-4">

        <div class="w-auto">

            <label for="name" class="input-label">Avatar</label>
            <div
                class="relative cursor-pointer group w-32 h-32 rounded-full "
                @click="onAvatarClick"
            >
                <!-- Avatar Image -->
                <div class="w-full h-full border-2 border-indigo-700  rounded-full overflow-hidden">
                    <img :src="previewUrl ?previewUrl: '/images/avatar.svg'" :alt="form.name"
                         class="w-full h-full object-cover"/>
                </div>

                <!-- Overlay -->
                <div
                    class="w-full h-full rounded-full absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                >
                    <span class="text-white font-semibold">Load Photo</span>
                </div>
            </div>

            <!-- Hidden File Input -->
            <input
                type="file"
                ref="avatarInputRef"
                accept="image/*"
                class="hidden"
                @change="onAvatarChange"
            />

            <!-- Loading and Error Messages -->
            <p v-if="loading" class="text-gray-500 mt-4">Uploading...</p>
            <p v-if="error" class="text-red-500 mt-4">{{ error }}</p>
        </div>

        <div>
            <label for="name" class="input-label">Channel Name</label>
            <input id="name" placeholder="Name" type="text" class="input-field" v-model="form.name"/>
        </div>

        <div>
            <label for="description" class="input-label">Description</label>
            <QuillEditor placeholder="Description" theme="bubble" class="input-field p-0" contentType="html"
                         v-model:content="form.description"/>
        </div>

        <div class="space-y-2">
            <label for="is_free" class="flex cursor-pointer items-start gap-4">
                <div class="flex items-center">
                    &#8203;
                    <input type="checkbox" class="size-4 rounded border-gray-300" id="is_free" v-model="form.is_free"/>
                </div>
                Is Free
            </label>
        </div>

        <div v-if="!form.is_free">
            <label for="subscription_price" class="input-label">Subscription Price</label>
            <div class="relative">
                <input id="subscription_price" placeholder="Price" type="text" class="input-field pl-6" v-model="form.subscription_price">
                <strong class="absolute top-1/2 -translate-y-1/2 left-3">$</strong>
            </div>
        </div>

        <button type="submit" class="btn-primary ">Save</button>
    </form>
</template>

<style scoped>

</style>
