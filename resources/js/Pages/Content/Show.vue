<script setup>
import VideoPlayer from "@/UI/VideoPlayer.vue";
import VideoCard from "@/UI/VideoCard.vue";
import ChannelSubscribeWidget from "@/UI/ChannelSubscribeWidget.vue";
import moment from "moment/moment";
import {toast} from "vue3-toastify";
import axios from 'axios';
import Avatar from "@/UI/Avatar.vue";
import {onMounted} from "vue";

const props = defineProps({
    content: Object,
});
const formatData = (data) => {
    return moment(data).fromNow()
}

const addReview = (data) => {
    axios.post(route('review.store', props.content.id), data)
        .then(resp => {
            props.content.reviews = resp.data.reviews
            toast.success(resp.data.success);

        }).catch(e => {
        toast.error(e.response?.data?.error || "Review failed!");
    })

}
const ucfirst = (str) => {
    if (!str) return "";
    return str[0].toUpperCase() + str.slice(1);
}
const ucwords = (str) => {
    return str.split(" ").map(word => ucfirst(word)).join(" ");
}
const view = () => {
    axios.post(route('views.view', props.content.id),{time:0}).then(response => {
        console.log('View created:', response.data.message);
    }).catch(e => {
        console.error('View creation error:', e.messages);
    })
}

onMounted(()=>{
   if (props.content.type === 'article'){
       view()
   }
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
        <main class="mx-10">
            <Head :title="content.title"/>
            <template v-if="content.type==='video'">
                <VideoPlayer :content="content" @review="addReview"/>
                <div class="flex justify-between items-center mt-2">
                    <h2 class="text-2xl font-bold my-3">{{ content.title }}</h2>
                    <ChannelSubscribeWidget :channel="content.channel"></ChannelSubscribeWidget>
                </div>
                <div class="border rounded-lg p-3 my-3 bg-indigo-50 ">
                    <h3 class="text-md font-bold mb-3">Description</h3>
                    <div>{{ content.views_count ?? 0 }} views {{ formatData(content.created_at) }}</div>
                    <div v-html="content.content"/>
                </div>
            </template>
            <template v-if="content.type==='article'">
                <div class="my-2">
                    <div class="flex justify-between items-center mt-2">
                        <h1 class="text-3xl font-bold my-3">{{ content.title }}</h1>
                        <ChannelSubscribeWidget :channel="content.channel"></ChannelSubscribeWidget>
                    </div>

                    <div><i class="text-gray-500">{{ content.views_count ?? 0 }} views {{ formatData(content.created_at) }}</i></div>
                    <div class="my-3" v-html="content.content"/>
                </div>

            </template>
            <section class="skills">
                <h3 class="text-xl font-bold my-3">Skills <span
                    v-if="props.content.active_skills.length">({{ props.content.active_skills.length }})</span></h3>
                <span v-if="props.content.active_skills.length"
                      class="bg-indigo-50 text-indigo-500 text-xs font-medium mr-2 px-1.5 py-1 rounded-full"
                      v-for="skill in props.content.active_skills">
                        {{ ucwords(skill.name) }} - Depth: {{ skill.pivot.depth }}
                </span>
                <div v-else>
                    No assigned skills
                </div>
            </section>
            <section class="reviews">
                <h3 class="text-xl font-bold my-3">Reviews <span
                    v-if="props.content.reviews.length">({{ props.content.reviews.length }})</span></h3>
                <div>
                    <div v-if="props.content.reviews.length" class="border rounded-lg p-3 my-3 bg-indigo-50 "
                         v-for="review in props.content.reviews">
                        <div class="flex items-center content-start gap-3">
                            <Avatar :user="review.user"/>
                            <div>{{ review.user.name }}</div>
                            <div>
                                <span v-for="star in 10" :key="star" class="cursor-pointer text-2xl"
                                      :class="{'text-yellow-500': star <= review.rating, 'text-gray-300': star > review.rating}">★</span>
                            </div>
                            <div>{{ review.rating }}/10</div>
                        </div>

                        <div class="mt-2" v-html="review.content"/>
                    </div>
                    <div v-else>
                        No reviews yet
                    </div>
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
