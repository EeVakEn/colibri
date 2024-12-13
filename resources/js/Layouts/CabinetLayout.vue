<template>
    <top-message/>

    <!-- Header -->
    <Header :user="page.props.user"></Header>

    <!-- Main Content -->
    <main class="container-full mx-auto">
        <div class="flex flex-row  gap-10">
            <Sidebar></Sidebar>
            <div class="py-8 container">
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

import {usePage} from "@inertiajs/vue3";
import TopMessage from "@/UI/TopMessage.vue";
const page = usePage();
</script>
