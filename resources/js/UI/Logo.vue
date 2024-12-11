<script setup>
import {Link} from '@inertiajs/vue3'
import {defineComponent} from "vue";
defineComponent({
    Link
})
</script>

<template>
    <Link :href="route('videos')" class="flex items-center space-x-2">
        <img src="/images/logo.svg" alt="Colibri">
        <span class="font-bold text-xl">Colibri</span>
    </Link>
</template>
