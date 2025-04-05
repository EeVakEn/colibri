<template>
    <header class="flex w-full fixed z-10 items-center justify-between px-6 py-4 bg-white border-b">
            <!-- Logo -->
            <div class="flex items-center">
                <Logo></Logo>
            </div>

            <!-- Search Bar -->
            <div class="flex-1 max-w-lg mx-auto">
               <Search/>
            </div>

            <!-- Login Button or Avatar -->
            <div>
                <Link
                    v-if="!$page.props.user"
                    :href="route('loginForm')"
                    class="btn-primary"
                >
                    <LogIn class="mr-2 h-4 w-4" />
                    Login
                </Link>
                <template v-else>
                    <Dropdown>
                        <template #button>
                            <Avatar :user="user"/>
                        </template>
                        <div class="p-4 flex flex-col gap-2 rounded-2">
                            <Link :href="route('account.index')" class="mb-2">
                                <div class="flex flex-row items-center">
                                    <Avatar :user="user" class="mr-2"/>
                                    <div class="flex flex-col">
                                        <b class="text-xl font-medium">{{user.name}}</b>
                                        <span class="text-xs font-light mt-[-6px]">{{user.email}}</span>
                                    </div>
                                </div>
                            </Link>
                            <Link :href="route('account.channels.index')" class="hover-li" >
                                <CircleUserRound class="mr-2 inline"/>
                                <span>Channels</span>
                            </Link>
                            <Link
                                class="hover-li"
                                :href="route('account.studio.index')"
                            >
                                <Brush class="mr-2 inline"/>
                                Studio
                            </Link>
                            <Link :href="route('account.wallet.index')" class="hover-li" >
                                <Wallet class="mr-2 inline"/>
                                <span>Wallet</span>
                                <span class="rounded float-right bg-[#1763F6] text-white font-medium px-2">${{user.balance}}</span>
                            </Link>
                            <a class="group hover-li hover:bg-red-100"  @click="logout">
                                <DoorClosed class="inline group-hover:hidden"/>
                                <DoorOpen class="hidden group-hover:inline"/>
                                <span class="text-medium ml-2">Logout</span>
                            </a>
                        </div>
                    </Dropdown>
                </template>

            </div>

    </header>
</template>

<script setup>
import Search from '@/UI/Search.vue'
import { LogIn, DoorOpen, DoorClosed, CircleUserRound, Wallet, Brush } from 'lucide-vue-next'
import Logo from "@/UI/Logo.vue";
import {Link} from '@inertiajs/vue3'
import {defineComponent} from "vue";
import { router } from '@inertiajs/vue3'
import Dropdown from "@/UI/Dropdown.vue";
import Avatar from "@/UI/Avatar.vue";
defineComponent({
    Link, Search
})

function logout(){
    if (confirm('Are you sure to logout?')){
        router.delete('/logout')
    }
}

// Define props
const props = defineProps({
    user: {
        type: Object,
        required: true
    }
})
</script>
