<script setup>
const props = defineProps({
    name: {
        type: String,
        default: 'icon',
    },
})
</script>

<template>
    <span :class="'icon-' + name"/>
</template>


<style scoped>
@import "lucide-static/font/lucide.css";
</style>

