<script setup>
import Icon from "@/UI/Icon.vue";
import {Link} from '@inertiajs/vue3'
import {defineComponent} from "vue";
defineComponent({
    Link
})
const props = defineProps({
    icon: {
        type: String,
        default: 'icon',
    },
    link: {
        type: String,
        default: '/'
    },
    size: {
        type: String,
        default: 'medium'
    }
})
</script>

<template>
    <Link :href="link" class="flex items-center cursor-pointer space-x-2" :class="'text-'+size">
        <Icon :name="icon"/>
        <span ><slot></slot></span>
    </Link>
</template>
