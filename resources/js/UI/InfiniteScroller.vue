<template>
    <div>
        <slot name="header" />

        <div :class="wrapperClass">
            <slot v-for="item in items" :item="item" :key="item.id" />

            <div
                v-if="isLoading"
                v-for="n in skeletonCount"
                :key="'skeleton-' + n"
                class="bg-gray-200 animate-pulse h-40 rounded-xl"
            ></div>
        </div>

        <div ref="observer" class="h-10 mt-4" v-if="!isFinished"></div>

        <p v-if="isFinished && items.length" class="text-center text-sm text-gray-500 mt-4">
            No content more 🐦
        </p>
    </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from "vue";

const props = defineProps({
    apiUrl: { type: String, required: true },
    skeletonCount: { type: Number, default: 6 },
    wrapperClass: { type: String, default: 'flex flex-row flex-wrap gap-5 mt-4' }
});

const items = ref([]);
const page = ref(1);
const isLoading = ref(false);
const isFinished = ref(false);
const observer = ref(null);

const loadMore = async () => {
    if (isLoading.value || isFinished.value) return;

    isLoading.value = true;

    try {
        const separator = props.apiUrl.includes('?') ? '&' : '?';
        const res = await fetch(`${props.apiUrl}${separator}page=${page.value}`);
        const data = await res.json();

        if (data.data.length === 0 || page.value >= data.last_page) {
            isFinished.value = true;
        }

        items.value.push(...data.data);
        page.value++;
    } catch (e) {
        console.error("Data load error:", e);
    } finally {
        isLoading.value = false;
        await nextTick();
        await ensureScrollableOrFinished();
    }
};

const ensureScrollableOrFinished = async () => {
    // Ждём пока DOM обновится
    await nextTick();

    const scrollHeight = document.documentElement.scrollHeight;
    const clientHeight = window.innerHeight;

    // Если нет скролла и ещё есть данные — грузим дальше
    if (scrollHeight <= clientHeight && !isFinished.value) {
        loadMore();
    }
};

const setupObserver = () => {
    const io = new IntersectionObserver(([entry]) => {
        if (entry.isIntersecting) {
            loadMore();
        }
    });

    if (observer.value) {
        io.observe(observer.value);
    }
};

onMounted(async () => {
    await loadMore(); // первая загрузка
    setupObserver();
});

defineExpose({ items });
</script>


