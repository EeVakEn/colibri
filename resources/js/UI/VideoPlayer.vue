<script setup>
import {ref, onMounted, onBeforeUnmount, computed, watch} from 'vue';
import axios from 'axios';
import 'video.js/dist/video-js.css';
import {VideoPlayer} from '@videojs-player/vue';
const emit = defineEmits(['review']);
const props = defineProps({
    content: Object,
});

const showPopup = ref(false);
const rating = ref(0);
const comment = ref('');
const actualWatchTime = ref(0);
const lastRecordedTime = ref(0);
const videoDuration = ref(0);
const player = ref(null);
let watchTimer = null;
const is75PercentWatched = computed(() => actualWatchTime.value >= videoDuration.value * 0.75);

const videoOptions = {
    autoplay: true,
    controls: true,
    fluid: true,
    playbackRates: [0.5, 1, 1.25, 1.5, 1.75, 2],
    sources: [{src: props.content.video_url, type: 'video/mp4'}],
    poster: props.content.preview_url,
    experimentalSvgIcons: true,
};

const handleVideoEnd = () => {
    showPopup.value = true;
};


const handleVideoStart = () => {
    axios.post(route('views.view', props.content.id),{time:0}).then(response => {
        console.log('View created:', response.data.message);
    }).catch(e => {
        console.error('View creation error:', e.messages);
    })
}

const submitReview = () => {
    emit('review', {
        rating: rating.value,
        content: comment.value,
    });
    showPopup.value = false;
};

const startWatchTimer = () => {
    if (watchTimer) clearInterval(watchTimer);

    watchTimer = setInterval(() => {
        if (isPlaying.value) {
            const currentTime = player.value?.player?.currentTime() || 0;

            if (currentTime > lastRecordedTime.value) {
                actualWatchTime.value += currentTime - lastRecordedTime.value;
            }

            lastRecordedTime.value = currentTime;
        }
    }, 1000);
};

const stopWatchTimer = () => {
    if (watchTimer) {
        clearInterval(watchTimer);
        watchTimer = null;
    }
};


const handlePlay = () => {
    isPlaying.value = true;
    startWatchTimer();
};

const handlePause = () => {
    isPlaying.value = false;
    stopWatchTimer();
};

onMounted(() => {
    const waitForPlayer = setInterval(() => {
        if (player.value?.player) {
            clearInterval(waitForPlayer);

            player.value.player.on('play', handlePlay);
            player.value.player.on('pause', handlePause);
            player.value.player.on('ended', handleVideoEnd);
        }
    }, 100);
});

onBeforeUnmount(() => {
    if (player.value?.player) {
        player.value.player.off('play', handlePlay);
        player.value.player.off('pause', handlePause);
        player.value.player.off('ended', handleVideoEnd);
    }
    stopWatchTimer();
});

</script>

<template>
    <div class="relative w-full h-auto rounded-lg ">
        <VideoPlayer
            class="video-js shadow-lg shadow-indigo-500/50" preload="none"
            data-setup='{ "html5" : { "nativeTextTracks" : false } }'
            ref="player"
            :options="videoOptions"
            @play="handleVideoStart"
            @ended="handleVideoEnd"
        />

        <div v-if="showPopup" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 mt-2 rounded-lg">
                <h3 class="text-xl font-bold mb-4">Rate and Comment</h3>
                <div class="mb-4">
                    <label class="block mb-2">Rating:</label>
                    <div>
                        <span v-for="star in 10" :key="star" @click="rating = star" class="cursor-pointer text-2xl"
                              :class="{'text-yellow-500': star <= rating, 'text-gray-300': star > rating}">★</span>
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

<style lang="scss">
.video-js {
    border-radius: 20px !important;
    font-size: 16px;

    .vjs-progress-holder {
        height: 1.3em;
    }

    .vjs-big-play-button {
        line-height: 1.85em;
        height: 2em;
        width: 2em;
        border-radius: 50%;
    }

    .vjs-control-bar {
        border-radius: 0 0 20px 20px !important;
        opacity: 0;
    }

    .vjs-tech, .vjs-poster {
        border-radius: 20px !important;
    }

    &:hover {
        .vjs-big-play-button {
            opacity: 1;
            transition: ease-in-out 0.5s;
        }

        .vjs-control-bar {
            opacity: 1;
            transition: ease-in-out 0.5s;
        }
    }
}

</style>
