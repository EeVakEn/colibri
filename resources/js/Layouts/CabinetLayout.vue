<template>
    <p v-if="$page.props.message" class="bg-indigo-600 h-10 flex items-center justify-center text-sm font-medium text-white px-4 sm:px-6 lg:px-8">
        {{ $page.props.message }}
    </p>

    <!-- Header -->
    <Header :user="user"></Header>

    <!-- Main Content -->
    <main class="container-full mx-auto">
        <div class="flex flex-row">
            <Sidebar></Sidebar>
            <div class="py-8">
                <slot/>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <Footer></Footer>
</template>

<script setup>
import Header from "@/Components/Header.vue";
import Footer from "@/Components/Footer.vue";
import Sidebar from "@/Components/Cabinet/Sidebar.vue";

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
})
</script>
