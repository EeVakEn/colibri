<script setup>
import LinkWithIcon from "@/UI/LinkWithIcon.vue";
</script>

<template>
    <aside class="max-w-[200px] w-[200px] py-8 border-r-1">
        <div class="flex flex-col gap-2 px-4">
            <LinkWithIcon :link="route('videos')" icon="video">Video</LinkWithIcon>
            <LinkWithIcon :link="route('articles')" icon="newspaper">Articles</LinkWithIcon>
        </div>
    </aside>
</template>

<style scoped>

</style>
