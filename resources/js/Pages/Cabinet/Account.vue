<script setup>
import { ref } from "vue";
import { usePage } from '@inertiajs/vue3';
import axios from "axios";
import Avatar from "@/UI/Avatar.vue";

const page = usePage();

const avatarInputRef = ref(null);
const loading = ref(false);
const error = ref(null);
const successMessage = ref(null);

const form = ref({
    name: page.props.user.name,
    email: page.props.user.email,
    description: page.props.user.description,
});

const onAvatarClick = () => {
    if (avatarInputRef.value) {
        avatarInputRef.value.click();
    }
};

const onAvatarChange = async (e) => {
    const file = e.target.files[0];
    if (file) {
        const formData = new FormData();
        formData.append("avatar", file);

        try {
            loading.value = true;
            error.value = null;

            const response = await axios.post(route('upload.avatar'), formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });
            page.props.user.avatar = `/storage/${response.data.path}`;
        } catch (e) {
            error.value = e.message;
        } finally {
            loading.value = false;
        }
    }
};

const onSave = async () => {
    try {
        loading.value = true;
        error.value = null;
        successMessage.value = null;

        await axios.put(route('user.update'), form.value);

        successMessage.value = "Profile updated successfully!";
    } catch (e) {
        error.value = e.response?.data?.message || e.message;
    } finally {
        loading.value = false;
    }
};
</script>

<script>
import CabinetLayout from "@/Layouts/CabinetLayout.vue";

export default {
    layout: CabinetLayout,
};
</script>

<template>
    <h1 class="text-2xl font-bold mb-6">Edit Account</h1>
    <div class="flex flex-col items-center">
        <!-- Avatar -->
        <div
            class="relative cursor-pointer group w-32 h-32 rounded-full "
            @click="onAvatarClick"
        >
            <!-- Avatar Image -->
            <Avatar
                :user="page.props.user"
                class-name="w-full h-full rounded-full overflow-hidden"
            />

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

        <!-- Form -->
        <form @submit.prevent="onSave" class="w-full max-w-md mt-6">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input
                    id="name"
                    type="text"
                    v-model="form.name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                />
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input
                    id="email"
                    type="email"
                    v-model="form.email"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                />
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea
                    id="description"
                    v-model="form.description"
                    rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                ></textarea>
            </div>

            <div class="flex items-center justify-between">
                <button
                    type="submit"
                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Save
                </button>
                <p v-if="successMessage" class="text-green-500">{{ successMessage }}</p>
            </div>
        </form>
    </div>
</template>
