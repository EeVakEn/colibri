<script>
import AccountLayout from "@/Layouts/AccountLayout.vue";

export default {
    layout: AccountLayout
}
</script>
<script setup>
import {useForm} from "@inertiajs/vue3";
import VideoUpload from "@/UI/VideoUpload.vue";
import {QuillEditor} from "@vueup/vue-quill";
import ImageUpload from "@/UI/ImageUpload.vue";

const props = defineProps({
    types: Object,
    channels: Object,
})
const form = useForm({
    title: '',
    channel_id: '',
    type: 'video',
    content: '',
    preview: null,
    video: null,
})
const urlParams = new URLSearchParams(new URL(window.location.href).search);
const channelId = urlParams.get('channel_id');
if (channelId in props.channels) {
    form.channel_id = +channelId;
}
</script>

<template>
    <h1 class="font-bold text-lg">Content Creation</h1>
    <div class="my-2">
        <label class="input-label">Title</label>
        <input class="input-field" v-model="form.title" placeholder="Enter a title"/>
    </div>
    <div class="my-2">
        <label class="input-label">Channel</label>
        <select class="select-field" v-model="form.channel_id">
            <option value="">Select a channel</option>
            <option v-for="(channel, id) in props.channels" :key="id" :value="id">{{ channel }}</option>
        </select>
    </div>
    <div class="my-2">
        <label class="input-label">Type</label>
        <select class="select-field" v-model="form.type">
            <option v-for="(type, id) in props.types" :key="id" :value="id">{{ type }}</option>
        </select>
    </div>
    <template v-if="form.type === 'video'">
        <div class="my-2">
            <label class="input-label">Video</label>
            <VideoUpload class="max-w-[600px]"/>
        </div>

        <div class="my-2">
            <label class="input-label">Preview</label>
            <ImageUpload class="max-w-[400px]" />
        </div>
    </template>
    <template v-else>
        <div class="my-2">
            <label class="input-label">Content</label>
            <QuillEditor placeholder="Content..." theme="snow"
                         contentType="html"
                         v-model:content="form.content"/>
        </div>
    </template>

</template>

