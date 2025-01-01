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
import {route} from "ziggy-js";
import {computed} from "vue";

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
const contentLabel = computed(() => {
    return form.type === 'video' ? 'Description' : 'Content'
})
const urlParams = new URLSearchParams(new URL(window.location.href).search);
const channelId = urlParams.get('channel_id');
if (channelId in props.channels) {
    form.channel_id = +channelId;
}
const submit = () => {
    form.post(route('contents.store'))
}
</script>

<template>
    <h1 class="font-bold text-lg">Content Creation</h1>
    <form @submit.prevent="submit">
        <div class="flex md:flex-row-reverse flex-col gap-4">
            <div class="lg:w-1/4 md:w-1/2 w-full">
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
                <div class="my-2">
                    <label class="input-label">Preview</label>
                    <ImageUpload class="aspect-video" v-model="form.preview"/>
                </div>

            </div>
            <div class="lg:w-3/4 md:w-1/2 w-full">
                <div class="my-2">
                    <label class="input-label">Video</label>
                    <VideoUpload v-model="form.video" class="aspect-video"/>
                </div>
                <div class="my-2">
                    <label class="input-label">{{contentLabel}}</label>
                    <QuillEditor :placeholder="`${contentLabel}...`" theme="snow"
                                 contentType="html"
                                 v-model:content="form.content"/>
                </div>
            </div>
        </div>


        <button type="submit" class="btn-primary float-end">Save</button>
    </form>

</template>

