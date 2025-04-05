<script setup>
import LinkWithIcon from "@/UI/LinkWithIcon.vue";
import { usePage, router } from '@inertiajs/vue3';
import { DoorClosed, DoorOpen, ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { ref } from 'vue';

const isSidebarCollapsed = ref(false);

function toggleSidebar() {
    isSidebarCollapsed.value = !isSidebarCollapsed.value;
}

function logout() {
    if (confirm('Are you sure to logout?')) {
        router.delete('/logout');
    }
}

const page = usePage();

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
        <div class="overflow-y-auto h-full px-4 pt-6 pb-20 flex flex-col gap-4">
            <LinkWithIcon
                :link="route('account.index')"
                icon="circle-user-round"
                :class="['hover-li', { 'font-bold': isActive('account.index'), 'justify-center': isSidebarCollapsed }]"
            >
                <span v-if="!isSidebarCollapsed">Account</span>
            </LinkWithIcon>

            <LinkWithIcon
                :link="route('account.channels.index')"
                icon="video"
                :class="['hover-li', { 'font-bold': isActive('account.channels.index'), 'justify-center': isSidebarCollapsed }]"
            >
                <span v-if="!isSidebarCollapsed">Channels</span>
            </LinkWithIcon>

            <LinkWithIcon
                :link="route('account.studio.index')"
                icon="brush"
                :class="['hover-li', { 'font-bold': isActive('account.studio.index'), 'justify-center': isSidebarCollapsed }]"
            >
                <span v-if="!isSidebarCollapsed">Studio</span>
            </LinkWithIcon>

            <LinkWithIcon
                :link="route('account.wallet.index')"
                icon="wallet"
                :class="['hover-li', { 'font-bold': isActive('account.wallet.index'), 'justify-center': isSidebarCollapsed }]"
            >
                <span v-if="!isSidebarCollapsed">Wallet</span>
            </LinkWithIcon>

            <LinkWithIcon
                class="hover:bg-red-100"
                :class="['hover-li', { 'justify-center': isSidebarCollapsed }]"
                icon="door-closed"
                @click="logout"
            >
                <span v-if="!isSidebarCollapsed">Logout</span>
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

