<template>
    <top-message/>
    <slot/>
</template>

<script setup>
import {usePage} from "@inertiajs/vue3";
import TopMessage from "@/UI/TopMessage.vue";
const page = usePage();
</script>
