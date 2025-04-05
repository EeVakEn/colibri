<script setup>
import LinkWithIcon from "@/UI/LinkWithIcon.vue";
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';

const page = usePage();
const isSidebarCollapsed = ref(false);

const toggleSidebar = () => {
    isSidebarCollapsed.value = !isSidebarCollapsed.value;
};

function isActive(rt) {
    return page.props.url === route(rt);
}
</script>

<template>
    <aside
        :class="[
            'transition-all duration-300 ease-in-out border-r text-xl bg-white h-[calc(100vh-78px)] sticky top-[78px]',
            isSidebarCollapsed ? 'w-16' : 'w-64'
        ]"
    >
        <div class="flex flex-col gap-4 p-4 ">
            <LinkWithIcon
                :link="route('videos')"
                icon="video"
                :class="['hover-li', { 'font-bold': isActive('videos'), 'justify-center': isSidebarCollapsed }]"
            >
                <span v-if="!isSidebarCollapsed">Video</span>
            </LinkWithIcon>

            <LinkWithIcon
                :link="route('articles')"
                icon="newspaper"
                :class="['hover-li', { 'font-bold': isActive('articles'), 'justify-center': isSidebarCollapsed }]"
            >
                <span v-if="!isSidebarCollapsed">Articles</span>
            </LinkWithIcon>
        </div>

        <button
            @click="toggleSidebar"
            class="fixed bottom-4 left-4 z-50 flex items-center justify-center text-gray-600 hover:text-black hover:bg-gray-100 rounded-lg p-2 transition bg-white shadow"
        >
            <ChevronLeft v-if="!isSidebarCollapsed" />
            <ChevronRight v-else />
        </button>
    </aside>
</template>
