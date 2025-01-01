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
            accept="image/*"
            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
            @change="handleFileChange"
        />

        <div v-if="modelValue" class="relative group">
            <img
                :src="modelValue"
                alt="Preview"
                class="w-full h-auto rounded-lg cursor-pointer"
                @click.stop="removeImage"
            />
            <button
                class="absolute top-5 right-5 bg-gray-700 bg-opacity-50 text-white rounded-full p-1 shadow-lg group-hover:opacity-100 opacity-0 duration-300"
                @click.stop="removeImage"
            >
                <X />
            </button>
        </div>

        <div v-else>
            <div v-if="isDragging" class="text-blue-400">
                Drop your image here
            </div>
            <div v-else>
                <p class="text-gray-500">Drag and drop an image file here, or click to upload.</p>
                <p class="text-sm text-gray-400 mt-2">Accepted formats: JPG, PNG, GIF, WEBP</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref, computed} from "vue";
import {X} from "lucide-vue-next";

// Props для v-model
const props = defineProps({
    modelValue: String, // Передаваемое значение
});
const emit = defineEmits(['update:modelValue']); // Событие для обновления значения

const isDragging = ref(false);

// Обработка изменения файла через input
const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        URL.createObjectURL(file);
        emit('update:modelValue', file);
    } else {
        alert("Please upload a valid image file.");
    }
};

// Обработка перетаскивания файла
const handleDrop = (event) => {
    const file = event.dataTransfer.files[0];
    if (file) {
        const fileUrl = URL.createObjectURL(file);
        emit('update:modelValue', fileUrl);
    } else {
        alert("Please drop a valid image file.");
    }
};

// Удаление изображения
const removeImage = (event) => {
    event.stopPropagation();
    emit('update:modelValue', null);
};
</script>
