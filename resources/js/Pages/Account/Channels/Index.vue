<script setup>
import Table from "@/UI/Table.vue";

import {reactive, defineComponent} from "vue";
import {Link} from "@inertiajs/vue3";
import {X, Check} from 'lucide-vue-next'
import Avatar from "@/UI/Avatar.vue";

defineComponent({
    Link
})
const props = defineProps({
    tableMeta: Object
})
</script>
<script>
import AccountLayout from "@/Layouts/AccountLayout.vue";

export default {
    layout: AccountLayout
}
</script>
<template>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Channels</h1>
        <Link class="btn-primary" :href="route('account.channels.create')">Create New</Link>
    </div>

    <Table
        :tableMeta="tableMeta"
    >
        <template #cell(avatar)="{data}">
            <Avatar :user='data'></Avatar>
        </template>
        <template #cell(description)="{data}">
            <p class="max-w-[300px] overflow-hidden" v-html="data.description"/>
        </template>
        <template #cell(is_free)="{item}">
            <Check class="text-green-700" v-if="item"/>
            <X class="text-red-700" v-else/>
        </template>
    </Table>
</template>

<style scoped>

</style>
