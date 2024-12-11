<template>
    <header class="flex items-center justify-between px-6 py-4 bg-background border-b">
        <!-- Logo -->
        <div class="flex items-center">
          <Logo></Logo>
        </div>

        <!-- Search Bar -->
        <div class="flex-1 max-w-sm mx-auto">
            <div class="relative">
                <Search class="absolute left-2 top-2.5 h-4 w-4 text-gray-400" />
                <input
                    type="search"
                    placeholder="Search..."
                    class="w-full pl-8 pr-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
            </div>
        </div>

        <!-- Login Button or Avatar -->
        <div>
            <Link
                v-if="!user"
                :href="route('videos')"
                class="flex items-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors"
            >
                <LogIn class="mr-2 h-4 w-4" />
                Login
            </Link>
            <div v-else class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                <img :src="user.avatar" :alt="user.name" class="w-full h-full object-cover" />
            </div>
        </div>
    </header>
</template>

<script setup>
import { Search, LogIn } from 'lucide-vue-next'
import Logo from "@/UI/Logo.vue";
import {Link} from '@inertiajs/vue3'
import {defineComponent} from "vue";
defineComponent({
    Link
})


// Define props
const props = defineProps({
    user: {
        type: Object,
        required: true
    }
})
</script>
