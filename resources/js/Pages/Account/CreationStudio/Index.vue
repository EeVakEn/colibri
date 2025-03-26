
<script setup>
import moment from "moment";
import Table from "@/UI/Table.vue";

import {defineComponent, useTemplateRef} from "vue";
import {Link, router} from "@inertiajs/vue3";
import {X, Check, ChevronDown} from 'lucide-vue-next'
import Dropdown from "@/UI/Dropdown.vue";
const tableRef = useTemplateRef('table');
defineComponent({
    Link
})
const props = defineProps({
    tableMeta: Object,
})
const deleteChannel = (url) => {
    router.delete(url, {
        onSuccess: function (){
            tableRef.value.refresh()
        }
    })
}
const formatData = (data) =>{
    return moment(data).fromNow()
}

const getActivatedSkills = (skills) => {
    return skills.filter(skill => skill?.pivot?.activated_at )
}
</script>
<script>
import AccountLayout from "@/Layouts/AccountLayout.vue";

export default {
    layout: AccountLayout
}
</script>
<template>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Creation Studio</h1>
        <Link class="btn-primary" :href="route('contents.create')">Create New Post</Link>
    </div>

    <Table
        ref="table"
        :tableMeta="tableMeta"
    >
        <template #cell(preview_url)="{data}">
            <Link :href="route('contents.show', [data.id])"><img class="h-[50px]" :src="data.preview_url"/></Link>
        </template>
        <template #cell(type)="{data}">
            {{data.type}}
        </template>
        <template #cell(title)="{data}">
            <Link :href="route('contents.show', [data.id])">{{data.title}}</Link>
        </template>
        <template #cell(channel_name)="{data}">
            <Link :href="route('account.channels.show', [data.channel_id])">{{data.channel_name}}</Link>
        </template>
        <template #cell(created_at)="{data}">
            {{ formatData(data.created_at) }}
        </template>

        <template #cell(updated_at)="{data}">
            {{ formatData(data.updated_at) }}
        </template>
        <template #cell(processed_at)="{data}">
            <template v-if="data.processed_at && getActivatedSkills(data.skills).length">
                <span class="bg-indigo-50 text-indigo-500 text-xs font-medium mr-2 px-1.5 py-1 rounded-full" v-for="skill in getActivatedSkills(data.skills)">{{skill.name}}</span>
            </template>
            <template v-else>
                <span v-if="data.processed_at" class="bg-yellow-600 text-white text-xs font-medium mr-2 px-1.5 py-1 rounded-full">Select the skills</span>
                <span v-else class="bg-yellow-600 text-white text-xs font-medium mr-2 px-1.5 py-1 rounded-full">Processing</span>
            </template>
        </template>
        <template #cell(actions)="{data}">
            <Dropdown>
                <template #button><b class="cursor-pointer">Actions <ChevronDown class="inline" :size="16"></ChevronDown></b></template>
                <div class="m-2">
                    <Link :href="route('account.channels.show', [data.id])" class="hover-li font-bold">Go to channel</Link>
                    <Link :href="route('account.channels.edit', [data.id])" class="hover-li font-bold">Edit</Link>
                    <Link @click="deleteChannel(route('account.channels.destroy', [data.id]))" class="hover-li hover:bg-red-100 font-bold">Delete</Link>
                </div>
            </Dropdown>
        </template>
    </Table>
</template>

<style scoped>

</style>
