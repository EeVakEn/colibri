<script setup>
const props = defineProps({
    user: {
        type: Object,
        required: true
    },
    className: {
        type: String,
        default: 'cursor-pointer border-2 border-indigo-700 w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden'
    }
})
</script>

<template>
    <div :class="className">
        <img :src="user.avatar ?? '/images/avatar.svg'" :alt="user.name" class="w-full h-full object-cover" />
    </div>
</template>
