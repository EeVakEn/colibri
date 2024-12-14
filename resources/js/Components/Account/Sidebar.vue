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
                :link="route('account.index')"
                icon="circle-user-round"
                :class="{'font-bold':isActive('account.index')}"
                class="hover-li"
            >
                Account
            </LinkWithIcon>
            <LinkWithIcon
                :link="route('account.channels.index')"
                icon="video"
                class="hover-li"
                :class="{'font-bold':isActive('account.channels.index')}"
            >
                Channels
            </LinkWithIcon>
            <a class="group hover-li hover:bg-red-100"  @click="logout">
                <DoorClosed class="mr-2 inline group-hover:hidden"/>
                <DoorOpen class="mr-2 hidden group-hover:inline"/>
                <span>Logout</span>
            </a>
        </div>
    </aside>
</template>
