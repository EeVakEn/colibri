<script setup>
import Table from "@/UI/Table.vue";

import {defineComponent, ref, useTemplateRef} from "vue";
import {Link, router} from "@inertiajs/vue3";
import {X, Check, ChevronDown} from 'lucide-vue-next'
import Avatar from "@/UI/Avatar.vue";
import Dropdown from "@/UI/Dropdown.vue";

const tableRef = useTemplateRef('table');
defineComponent({
    Link
})
const props = defineProps({
    tableMeta: Object
})
const deleteChannel = (url) => {
    router.delete(url, {
        onSuccess: function () {
            tableRef.value.refresh()
        }
    })
}
const descriptionShow = ref(null);

const toggleDescription = (id) => {
    descriptionShow.value = descriptionShow.value === id ? null : id;
};
</script>
<script>
import AccountLayout from "@/Layouts/AccountLayout.vue";

export default {
    layout: AccountLayout
}
</script>
<template>
    <div class="container">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Channels</h1>
            <Link class="btn-primary" :href="route('account.channels.create')">Create New</Link>
        </div>

        <Table
            ref="table"
            :tableMeta="tableMeta"
        >
            <template #cell(avatar)="{data}">
                <Link :href="route('account.channels.show', [data.id])">
                    <Avatar :user='data'></Avatar>
                </Link>
            </template>
            <template #cell(name)="{data}">
                <Link :href="route('account.channels.show', [data.id])">{{ data.name }}</Link>
            </template>
            <template #cell(description)="{data}">
                <template v-if="descriptionShow === data.id">
                    <p class="text-wrap overflow-ellipsis" v-html="data.description"></p>
                </template>
                <span @click="toggleDescription(data.id)" class="text-indigo-600 cursor-pointer">
                {{ data.id === descriptionShow ? 'Hide' : 'Show' }}
            </span>
            </template>
            <template #cell(is_free)="{item}">
                <Check class="text-green-700" v-if="item"/>
                <X class="text-red-700" v-else/>
            </template>
            <template #cell(subscription_price)="{item}">
                <b v-if="item">${{ item }}</b>
            </template>
            <template #cell(actions)="{data}">
                <Dropdown>
                    <template #button><b class="cursor-pointer">Actions
                        <ChevronDown class="inline" :size="16"></ChevronDown>
                    </b></template>
                    <div class="m-2">
                        <Link :href="route('account.channels.show', [data.id])" class="hover-li font-bold">Go to channel
                        </Link>
                        <Link :href="route('account.channels.edit', [data.id])" class="hover-li font-bold">Edit</Link>
                        <Link @click="deleteChannel(route('account.channels.destroy', [data.id]))"
                              class="hover-li hover:bg-red-100 font-bold">Delete
                        </Link>
                    </div>
                </Dropdown>
            </template>
        </Table>

    </div>
</template>

<style scoped>

</style>
