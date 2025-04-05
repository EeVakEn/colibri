<template>
    <top-message/>
    <div class="flex flex-col min-h-screen divide-y divide-slate-300">
        <!-- Header -->
        <Header :user="page.props.user"></Header>

        <!-- Main Content -->
        <main class="flex-grow container-full flex flex-row mt-[78px]">
            <Sidebar class="border-slate-100 border-r-2"/>
            <div class="py-8 px-4 w-full">
                <slot/>
            </div>
        </main>

        <!-- Footer -->
        <Footer></Footer>
    </div>
</template>

<script setup>
import Header from "@/Components/Header.vue";
import Footer from "@/Components/Footer.vue";
import Sidebar from "@/Components/Account/Sidebar.vue";

import {usePage} from "@inertiajs/vue3";
import TopMessage from "@/UI/TopMessage.vue";

const page = usePage();
</script>
