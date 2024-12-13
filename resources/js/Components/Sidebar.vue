<script setup>
import LinkWithIcon from "@/UI/LinkWithIcon.vue";
import { usePage } from '@inertiajs/vue3';

const page = usePage();
function isActive(rt) {
    return page.props.url === route(rt);
}
</script>

<template>
    <aside class="max-w-[200px] w-[200px] py-8 border-r-1">
        <div class="flex flex-col gap-2 px-4">

            <LinkWithIcon
                :link="route('videos')"
                icon="video"
                class="hover-li"
                :class="{'font-bold':isActive('videos')}"
            >
                Video
            </LinkWithIcon>
            <LinkWithIcon
                :link="route('articles')"
                icon="newspaper"
                class="hover-li"
                :class="{'font-bold':isActive('articles')}"
            >
                Articles
            </LinkWithIcon>
        </div>
    </aside>
</template>
