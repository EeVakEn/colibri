<script setup>
import LinkWithIcon from "@/UI/LinkWithIcon.vue";
import { usePage } from '@inertiajs/vue3';
import {DoorClosed, DoorOpen} from "lucide-vue-next";
import { router } from '@inertiajs/vue3'

function logout(){
    router.delete('/logout')
}
const page = usePage();
function isActive(rt) {
    return page.props.url === route(rt);
}

</script>

<template>
    <aside class="max-w-[200px] w-[200px] py-8 border-r-1">
        <div class="flex flex-col gap-2 px-4">
            <LinkWithIcon
                :link="route('account')"
                icon="circle-user-round"
                :class="{'font-bold':isActive('account')}"
                class=" cursor-pointer block hover:bg-gray-100 bg-transparent rounded p-1"
            >
                Account
            </LinkWithIcon>
            <LinkWithIcon
                :link="route('channels.index')"
                icon="video"
                class=" cursor-pointer block hover:bg-gray-100 bg-transparent rounded p-1"
                :class="{'font-bold':isActive('channels.index')}"
            >
                Channels
            </LinkWithIcon>
            <a class="group cursor-pointer block hover:bg-red-100 bg-transparent rounded p-1"  @click="logout">
                <DoorClosed class="mr-2 inline group-hover:hidden"/>
                <DoorOpen class="mr-2 hidden group-hover:inline"/>
                <span>Logout</span>
            </a>
        </div>
    </aside>
</template>
