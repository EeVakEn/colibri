<script setup>
import VideoPlayer from "@/UI/VideoPlayer.vue";
import VideoCard from "@/UI/VideoCard.vue";

const props = defineProps({
    content: Object,
});
</script>

<script>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";

export default {
    layout: DefaultLayout
}
</script>

<template>
    <div class="grid grid-cols-[5fr_1fr] gap-10">
        <main>
            <Head :title="content.title"/>
            <template v-if="content.type==='video'">
                <VideoPlayer :videoUrl="content.video_url" :videoId="content.id"/>
                <h2 class="text-2xl font-bold my-3">{{ content.title }}</h2>
                <h3 class="text-xl font-bold my-3">Description</h3>
                <div v-html="content.content"/>
            </template>
            <template v-if="content.type==='article'">
                <!-- Article content goes here -->
            </template>
            <section class="reviews">
                <h3 class="text-xl font-bold my-3">Reviews</h3>
                <div>
                    <!-- Display reviews here -->
                </div>
            </section>
        </main>

        <aside class="similarContent flex flex-col gap-2">
            <h2 class="text-2xl font-bold mb-4">Similar {{ content.type }}s</h2>
            <VideoCard v-for="video in content.similar" :key="video.id" :video="video"></VideoCard>
        </aside>
    </div>
</template>

<style scoped>
</style>
