<script setup>
const props = defineProps({
    user: {
        type: Object,
        required: true
    }
})
</script>

<template>
    <div class="cursor-pointer w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
        <img :src="user.avatar ?? '/images/avatar.svg'" :alt="user.name" class="w-full h-full object-cover" />
    </div>
</template>
