<template>
    <Head :title="title"></Head>
    <section>
        <h2 class="text-2xl font-bold mb-4">Recommendations</h2>
        <p class="text-gray-700">Videos</p>
    </section>
</template>

<script>
import {defineComponent, defineProps} from "vue";
import Head from "@inertiajs/inertia-vue3";

const props = defineProps({
    title: String
})

export default defineComponent({
    components: {Head}
})
</script>
