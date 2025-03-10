
<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import axios from 'axios';

const props = defineProps({
    videoUrl: String,
    videoId: [String, Number],
});

const showPopup = ref(false);
const rating = ref(0);
const comment = ref('');
const videoWatchedHalfway = ref(false);
const actualWatchTime = ref(0);
let watchTimeInterval = null;
let lastSentTime = 0;
const videoElement = ref(null);

const handleVideoEnd = () => {
    showPopup.value = true;
};

const handleTimeUpdate = () => {
    const currentTime = videoElement.value.currentTime;

    // Check if the user is skipping forward
    if (currentTime - actualWatchTime.value > 1) {
        actualWatchTime.value = currentTime;
    }

    const halfwayPoint = videoElement.value.duration / 2;
    if (actualWatchTime.value >= halfwayPoint) {
        videoWatchedHalfway.value = true;
    }

    // Send progress update every minute
    if (currentTime - lastSentTime >= 60) {
        sendProgressUpdate(currentTime);
        lastSentTime = currentTime;
    }
};

const sendProgressUpdate = (currentTime) => {
    axios.post('/api/video-progress', {
        videoId: props.videoId,
        progress: currentTime,
    })
        .then(response => {
            console.log('Progress updated:', response.data);
        })
        .catch(error => {
            console.error('Error updating progress:', error);
        });
};

const submitReview = () => {
    if (videoWatchedHalfway.value) {
        // Handle the submission of the review (e.g., send to server)
        console.log('Rating:', rating.value);
        console.log('Comment:', comment.value);
        showPopup.value = false;
    } else {
        alert('Please watch at least half of the video without skipping to submit a comment.');
    }
};

onMounted(() => {
    watchTimeInterval = setInterval(handleTimeUpdate, 1000);
});

onBeforeUnmount(() => {
    clearInterval(watchTimeInterval);
});
</script>

<template>
    <div class="relative w-full h-auto rounded-lg cursor-pointer">
        <video
            class="w-full h-full"
            :src="videoUrl"
            controls
            @ended="handleVideoEnd"
            @timeupdate="handleTimeUpdate"
            ref="videoElement"
        ></video>
        <!-- Popup for Rating and Commenting -->
        <div v-if="showPopup" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg">
                <h3 class="text-xl font-bold mb-4">Rate and Comment</h3>
                <div class="mb-4">
                    <label class="block mb-2">Rating:</label>
                    <div>
                        <span v-for="star in 10" :key="star" @click="rating = star" class="cursor-pointer text-2xl" :class="{'text-yellow-500': star <= rating, 'text-gray-300': star > rating}">★</span>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block mb-2">Comment:</label>
                    <textarea v-model="comment" class="w-full p-2 border rounded"></textarea>
                </div>
                <div class="flex justify-end">
                    <button @click="submitReview" class="px-4 py-2 bg-blue-500 text-white rounded">Comment</button>
                </div>
            </div>
        </div>
    </div>
</template>


<style scoped>
/* Add any scoped styles here */
</style>
