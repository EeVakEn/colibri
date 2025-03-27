<script setup>
import Avatar from "@/UI/Avatar.vue";
import LinkWithIcon from "@/UI/LinkWithIcon.vue";
import humanFormat from "human-format";
import {Tab, Tabs} from "vue3-tabs-component";
import VideoCard from "@/UI/VideoCard.vue";


const props = defineProps({
    channel: Object,
    subsCount: Number,
    videos: Array,
})
</script>
<script>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";

export default {
    layout: DefaultLayout
}
</script>
<template>
    <div class="flex justify-between items-start">
        <div class="flex flex-row items-start mb-5 gap-5">
            <Avatar class-name="cursor-pointer border-2 border-indigo-700 !w-20 !h-20 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden aspect-square" :user="channel">
            </Avatar>
            <div class="flex flex-col">
                <h1 class="inline font-bold text-2xl">
                    {{channel.name}}
                </h1>
                <small>{{humanFormat(subsCount)}} Subscribers</small>
                <h3 class="font-bold my-2">Description</h3>
                <div class="text-sm max-w-[800px]" v-html="channel.description"/>
            </div>

        </div>
        <LinkWithIcon class="btn-primary" size="md" icon="diamond-plus" :link="route('contents.create', {channel_id:channel.id})">Add Content</LinkWithIcon>
    </div>

    <hr class="border-2">

    <tabs>
        <tab name="Video">
            <div class="flex flex-row flex-wrap gap-5">
                <VideoCard v-for="video in videos" :key="video.id" :video="video"/>
            </div>
        </tab>
        <tab name="Articles">
            Articles
        </tab>
    </tabs>
</template>
