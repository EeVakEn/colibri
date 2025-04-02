<script setup>
import {ref, defineProps} from 'vue';

const props = defineProps({
    value: {
        type: Number,
        required: true,
        default: 0,
    },
    max: {
        type: Number,
        required: true,
        default: 100,
    },
});

const value = ref(props.value);
const max = ref(props.max);
const color = ref(props.color);
</script>

<template>
    <div class="relative w-full h-8 bg-gray-200 rounded-full overflow-hidden">
        <div
            class="absolute top-0 left-0 h-full transition-all duration-300 bg-indigo-600"
            :style="{ width: `${(value / max) * 100}%`}"
        ></div>
        <div class="text-right absolute right-2 top-1/2 -translate-y-2/3 text-sm text-gray-500 mt-1">
            {{ value }}
        </div>
    </div>
</template>

<style scoped>

</style>
