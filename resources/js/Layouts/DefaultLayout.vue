<template>
    <div>
        <!-- Header -->
        <Header :user="user"></Header>

        <!-- Main Content -->
        <main class="container mx-auto">
            <div class="flex flex-row">
                <Sidebar></Sidebar>
                <div class="py-8">
                    <slot/>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <Footer></Footer>
    </div>
</template>

<script setup>
import Header from "@/Components/Header.vue";
import Footer from "@/Components/Footer.vue";
import Sidebar from "@/Components/Sidebar.vue";
const props = defineProps({
    user: {
        type: Object,
        required: true
    }
})
</script>
