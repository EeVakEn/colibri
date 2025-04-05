<template>
    <section>
        <h2 class="text-2xl font-bold mb-4">Search for "{{query}}"</h2>
        <p class="text-gray-700 mb-4">{{title}}</p>

        <InfiniteScroller :api-url="apiUrl" :skeleton-count="10" wrapper-class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 3xl:grid-cols-5 gap-6">
            <template #default="{ item }">
                <VideoCard :video="item" />
            </template>
        </InfiniteScroller>
    </section>
</template>

<script>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";

export default {
    layout: DefaultLayout
}
</script>
<script setup>
import InfiniteScroller from "@/UI/InfiniteScroller.vue";
import VideoCard from "@/UI/VideoCard.vue";
import {computed, onMounted} from "vue";
import {route} from "ziggy-js";
const apiUrl = computed(() => {
    return route('api.content', { q: props.query , type: props.type});
});
const props = defineProps({
    type: String,
    title: String,
    query: String,
})
</script>
