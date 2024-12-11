<template>
    <slot/>
</template>

<script setup>
import Header from "@/Components/Header.vue";
import Footer from "@/Components/Footer.vue";
import Sidebar from "@/Components/Sidebar.vue";
const props = defineProps({
    user: {
        type: Object,
        required: true
    }
})
</script>
