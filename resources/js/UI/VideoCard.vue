<script setup>
import {Link} from '@inertiajs/vue3'
import moment from "moment";
import {route} from "ziggy-js";
import Avatar from "@/UI/Avatar.vue";


defineProps({
    video: {
        type: String,
        required: true,
    }
})
</script>

<template>
    <Link :href="route('contents.show', video.id)">
        <article class="overflow-hidden rounded-lg shadow transition hover:shadow-lg w-[300px]">
            <img
                :alt="video.title"
                :src="'/storage/' + video.preview"
                class="h-56 object-cover aspect-video"
            />
            <div class="bg-white p-2 sm:p-3 flex flex-col gap-5">
                <div class="flex flex-row gap-5">
                    <Avatar class-name="cursor-pointer border-2 border-indigo-700 !w-[30px] rounded-full bg-gray-200 flex items-center justify-center overflow-hidden" :user="video.channel"/>
                    <h3 class="mt-0.5 text-lg text-gray-900 truncate">{{ video.title }}</h3>
                </div>
                <div class="flex flex-1 items-end justify-between">
                    <span class="w-1/2 block text-xs text-gray-500">{{ video.views ?? 0 }} Views</span>
                    <time datetime="2022-10-10" class="w-1/2 text-right block text-xs text-gray-500">{{ moment(video.created_at).fromNow() }}</time>
                </div>
            </div>
        </article>
    </Link>
</template>

<style scoped>

</style>
