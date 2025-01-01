<template>
    <div
        class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 flex flex-col items-center justify-center text-center hover:border-blue-400 focus-within:border-blue-400"
        @dragover.prevent
        @dragenter.prevent="isDragging = true"
        @dragleave.prevent="isDragging = false"
        @drop.prevent="handleDrop"
    >
        <input
            type="file"
            accept="video/*"
            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
            @change="handleFileChange"
        />

        <div v-if="internalVideoUrl" class="relative group">
            <video
                class="w-full h-auto rounded-lg cursor-pointer"
                :src="internalVideoUrl"
                controls
            ></video>
            <button
                class="absolute top-5 right-5 bg-red-700 bg-opacity-50 hover:bg-opacity-100 text-white rounded-full p-1 shadow-lg group-hover:opacity-100 opacity-0 duration-300"
                @click.stop="removeVideo"
            >
                <X />
            </button>
        </div>

        <div v-else>
            <div v-if="isDragging" class="text-blue-400">
                Drop your video here
            </div>
            <div v-else>
                <p class="text-gray-500">Drag and drop a video file here, or click to upload.</p>
                <p class="text-sm text-gray-400 mt-2">Accepted formats: MP4, AVI, MOV</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import { X } from "lucide-vue-next";

// Поддержка v-model
const props = defineProps({
    modelValue: String, // Получение значения из родителя
});
const emit = defineEmits(["update:modelValue"]); // Эмит для обновления значения

const internalVideoUrl = ref(props.modelValue || null);
const isDragging = ref(false);

watch(() => props.modelValue, (newValue) => {
    internalVideoUrl.value = newValue;
});

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        previewVideo(file);
    }
};

const handleDrop = (event) => {
    const file = event.dataTransfer.files[0];
    if (file) {
        previewVideo(file);
    }
    isDragging.value = false;
};

const previewVideo = (file) => {
    URL.createObjectURL(file);
    emit("update:modelValue", file); // Обновление значения через v-model
};

const removeVideo = () => {
    internalVideoUrl.value = null;
    emit("update:modelValue", null); // Сброс значения через v-model
};
</script>
