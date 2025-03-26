<script setup>
import LinkWithIcon from "@/UI/LinkWithIcon.vue";
import { usePage } from '@inertiajs/vue3';
import {DoorClosed, DoorOpen} from "lucide-vue-next";
import { router } from '@inertiajs/vue3'

function logout(){
    if (confirm('Are you sure to logout?')){
        router.delete('/logout')
    }
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
            <LinkWithIcon
                :link="route('account.studio.index')"
                icon="pen"
                class="hover-li"
                :class="{'font-bold':isActive('account.studio.index')}"
            >
                Creation Studio
            </LinkWithIcon>
            <Link class="group hover-li hover:bg-red-100"  @click="logout">
                <DoorClosed size="16" class="mr-2 inline group-hover:hidden"/>
                <DoorOpen size="16" class="mr-2 hidden group-hover:inline"/>
                Logout
            </Link>
        </div>
    </aside>
</template>
