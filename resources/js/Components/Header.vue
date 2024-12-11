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
                v-if="!$page.props.user"
                :href="route('loginForm')"
                class="flex items-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors"
            >
                <LogIn class="mr-2 h-4 w-4" />
                Login
            </Link>
            <template v-else>
                <Dropdown>
                    <template #button>
                        <Avatar :user="user"/>
                    </template>
                    <div class="p-2 flex flex-col gap-2 rounded-2">
                        <Link :href="route('account')">
                            <div class="flex flex-row items-center">
                                <Avatar :user="user" class="mr-2"/>
                                <div class="flex flex-col">
                                    <b class="text-xl font-medium">{{user.name}}</b>
                                    <span class="text-xs font-light mt-[-6px]">{{user.email}}</span>
                                </div>
                            </div>
                        </Link>
                        <Link href="#channels" class="group cursor-pointer block hover:bg-gray-100 bg-transparent rounded p-1" >
                            <CircleUserRound class="mr-2 inline"/>
                            <span>Channels</span>
                        </Link>
                        <Link href="#wallet" class="group cursor-pointer block hover:bg-gray-100 bg-transparent rounded p-1" >
                            <Wallet class="mr-2 inline"/>
                            <span>Wallet</span>
                            <span class="rounded float-right bg-[#1763F6] text-white font-medium px-2">$0</span>
                        </Link>
                        <a class="group cursor-pointer block hover:bg-red-100 bg-transparent rounded p-1"  @click="logout">
                            <DoorClosed class="mr-2 inline group-hover:hidden"/>
                            <DoorOpen class="mr-2 hidden group-hover:inline"/>
                            <span>Logout</span>
                        </a>
                    </div>
                </Dropdown>
            </template>

        </div>
    </header>
</template>

<script setup>
import { Search, LogIn, DoorOpen, DoorClosed, CircleUserRound, Wallet } from 'lucide-vue-next'
import Logo from "@/UI/Logo.vue";
import {Link} from '@inertiajs/vue3'
import {defineComponent} from "vue";
import { router } from '@inertiajs/vue3'
import Dropdown from "@/UI/Dropdown.vue";
import Icon from "@/UI/Icon.vue";
import Avatar from "@/UI/Avatar.vue";
defineComponent({
    Link
})

function logout(){
    router.delete('/logout')
}

// Define props
const props = defineProps({
    user: {
        type: Object,
        required: true
    }
})
</script>
