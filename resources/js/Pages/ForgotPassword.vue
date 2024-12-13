<script setup>
import {useForm} from "@inertiajs/vue3";
import Logo from "@/UI/Logo.vue";
import {route} from "ziggy-js";

const form = useForm({
    email: ''
})
const forgot = () => {
    form.post(route('forgotPassword'))
}
</script>
<script>
import EmptyLayout from "@/Layouts/EmptyLayout.vue";

export default {
    layout: EmptyLayout
}
</script>

<template>

    <div class="h-screen w-screen flex items-center justify-center flex-col gap-5">
        <Logo class="justify-center"></Logo>
        <h1>Forgot Password</h1>
        <form @submit.prevent="forgot" class="flex flex-col">
            <label for="email" class="input-label">Email</label>
            <input class="input-field" placeholder="Email" type="email" v-model="form.email">


            <button type="submit" class="mt-5 btn-primary">Reset Password</button>
        </form>
    </div>
</template>

<style scoped>

</style>
