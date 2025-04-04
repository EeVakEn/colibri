<script setup>
import { ref, watch } from "vue";
import { X } from "lucide-vue-next";

const props = defineProps({
    modelValue: File, // Храним сам файл
});
const emit = defineEmits(["update:modelValue"]);

const isDragging = ref(false);
const previewUrl = ref(null); // URL для отображения видео

// Следим за modelValue и обновляем previewUrl
watch(() => props.modelValue, (newValue) => {
    previewUrl.value = newValue ? URL.createObjectURL(newValue) : null;
});

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file && isValidVideo(file)) {
        emit("update:modelValue", file); // Передаем файл
    }
};

const handleDrop = (event) => {
    event.preventDefault();
    const file = event.dataTransfer.files[0];
    if (file && isValidVideo(file)) {
        emit("update:modelValue", file); // Передаем файл
    }
    isDragging.value = false;
};

const removeVideo = () => {
    previewUrl.value = null;
    emit("update:modelValue", null);
};

// Проверка типа видео
const isValidVideo = (file) => {
    return ["video/mp4", "video/ogg", "video/webm"].includes(file.type);
};
</script>

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

        <div v-if="previewUrl" class="relative group">
            <video
                class="w-full h-auto rounded-lg cursor-pointer"
                :src="previewUrl"
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
                <p class="text-sm text-gray-400 mt-2">Accepted formats: MP4, OGG, WEBM</p>
            </div>
        </div>
    </div>
</template>
