<script setup>
import Avatar from "@/UI/Avatar.vue";
import LinkWithIcon from "@/UI/LinkWithIcon.vue";
import humanFormat from "human-format";
import {Tab, Tabs} from "vue3-tabs-component";

const props = defineProps({
    channel: Object,
    subsCount: Number,
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
            <Avatar class-name="cursor-pointer border-2 border-indigo-700 w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden" :user="channel">
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
        <LinkWithIcon class="btn-primary" size="md" icon="diamond-plus" :link="route('account.channels.index')">Add Content</LinkWithIcon>
    </div>

    <hr class="border-2">

    <tabs>
        <tab name="Video">
           Video
        </tab>
        <tab name="Articles">
            Articles
        </tab>
    </tabs>
</template>
