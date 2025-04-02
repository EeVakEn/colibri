<script setup>
import {ref, reactive} from "vue";
import {usePage, router, Link} from '@inertiajs/vue3';
import moment from "moment";
import axios from "axios";
import Avatar from "@/UI/Avatar.vue";
import Modal from "@/UI/Modal.vue";
import {QuillEditor} from "@vueup/vue-quill";
import {ChevronDown, Info, Newspaper, Video} from "lucide-vue-next";
import Dropdown from "@/UI/Dropdown.vue";
import Table from "@/UI/Table.vue";
import VideoCard from "@/UI/VideoCard.vue";
import ProgressBar from "@/UI/ProgressBar.vue";

defineProps([
    'skillsTableMeta',
    'historyTableMeta',
    'skill_max_score',
]);
const page = usePage();

const avatarInputRef = ref(null);
const loading = ref(false);
const error = ref(null);
const successMessage = ref(null);
const isEditModalOpen = ref(false);

const form = reactive({
    name: page.props.user.name,
    description: page.props.user.description,
});

const ucfirst = (str) => {
    if (!str) return "";
    return str[0].toUpperCase() + str.slice(1);
}
const ucwords = (str) => {
    return str.split(" ").map(word => ucfirst(word)).join(" ");
}

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

            const response = await axios.post(route('account.upload.avatar'), formData, {
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

const formatData = (data) => {
    return moment(data).fromNow()
}


const onSave = () => {
    router.put(route('account.update'), form)
    isEditModalOpen.value = false
};
</script>

<script>
import AccountLayout from "@/Layouts/AccountLayout.vue";

export default {
    layout: AccountLayout,
};
</script>

<template>
    <div class="container">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold mb-6">Account</h1>
            <button @click="isEditModalOpen=true" class="btn-primary mt-5">Edit</button>
        </div>

        <!-- Avatar -->
        <div class="flex flex-row  gap-10">
            <div class="w-auto">
                <div
                    class="relative cursor-pointer group w-32 h-32 rounded-full "
                    @click="onAvatarClick"
                >
                    <!-- Avatar Image -->
                    <Avatar
                        :user="page.props.user"
                        class-name="w-full h-full border-2 border-indigo-700  rounded-full overflow-hidden"
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
            </div>
            <div class="w-auto">
                <p class="font-bold text-xl"> {{ page.props.user.name }} </p>
                <p class="font-light text-sm"> {{ page.props.user.email }} </p>
                <div class="mt-4" v-if="page.props.user.description" v-html="page.props.user.description"/>

                <p class="font-light text-sm mt-7">Created:
                    {{ moment(page.props.user.created_at).format('Do MMM YYYY') }} </p>
            </div>

        </div>

        <!--Skills-->
        <h2 class="text-2xl font-bold my-4">Skills</h2>
        <Table
            ref="table"
            :tableMeta="skillsTableMeta"
        >
            <template #cell(name)="{data}">
                <b>{{ucwords(data.name)}}</b>
            </template>
            <template #cell(total_score)="{data}">
                <ProgressBar :value="parseInt(data.total_score)" :max="skill_max_score" />
            </template>
        </Table>

        <!--History-->
        <h2 class="text-2xl font-bold my-4">History</h2>
        <Table
            ref="table"
            :tableMeta="historyTableMeta"
        >
            <template #cell(content)="data">
                <Link :href="route('contents.show', data.item.id)">
                    <div class="flex gap-2 items-center">
                        <img :src="data.item.preview_url" class="h-14"/>
                        {{ data.item.title }}
                    </div>
                </Link>
            </template>

            <template #cell(skills)="data">
            <span class="bg-indigo-50 text-indigo-500 text-xs font-medium mr-2 px-1.5 py-1 rounded-full"
                  v-for="skill in data.data.content.active_skills">{{
                    ucwords(skill.name)
                }} - Depth: {{ skill.pivot.depth }}</span>
            </template>
            <template #cell(created_at)="data">
                {{ formatData(data.item) }}
            </template>
        </Table>


        <!-- Form -->
        <teleport to="body">
            <Modal v-model="isEditModalOpen">
                <h2>Edit Account</h2>
                <form @submit.prevent="onSave" class="w-full max-w-md mt-6">
                    <div class="mb-4">
                        <label
                            for="name"
                            class="input-label"
                        >
                            Name
                        </label>
                        <input
                            type="text"
                            id="name"
                            class="input-field"
                            v-model="form.name"
                            placeholder="name"
                        />

                    </div>
                    <div class="mb-4">
                        <label for="description" class="input-label">Description</label>
                        <QuillEditor theme="bubble" class="input-field p-0" contentType="html"
                                     v-model:content="form.description"/>
                    </div>

                    <div class="flex items-center justify-between">
                        <button
                            type="submit"
                            class="btn-primary mt-5 float-right"
                        >
                            Save
                        </button>
                        <p v-if="successMessage" class="text-green-500">{{ successMessage }}</p>
                    </div>
                </form>
            </Modal>
        </teleport>
    </div>
</template>
