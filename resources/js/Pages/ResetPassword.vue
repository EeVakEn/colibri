<script setup>
import {useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import Logo from "@/UI/Logo.vue";

const props = defineProps({
    token: String,
    email: String,
});
const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: ''
})
const reset = () => {
    form.post(route('password.reset'))
}
</script>
<script>
import EmptyLayout from "@/Layouts/EmptyLayout.vue";

export default {
    layout: EmptyLayout
}
</script>

<template>

    <div class="h-screen w-screen flex flex-col items-center justify-center gap-5">
        <Logo class="justify-center"></Logo>
        <h1>Set New Password</h1>

        <form @submit.prevent="reset" class="flex flex-col gap-2">
<!--            <div>-->
<!--                <label for="email" class="input-label">Email</label>-->
<!--                <input class="input-field" type="email" placeholder="Email"  v-model="form.email">-->
<!--            </div>-->
            <div>
                <label for="password" class="input-label">Password</label>
                <input class="input-field" type="password" placeholder="Password"  v-model="form.password">
            </div>
            <div>
                <label for="password_confirmation" class="input-label">Password Again</label>
                <input class="input-field" type="password" placeholder="Password" v-model="form.password_confirmation">
            </div>
            <button type="submit" class="mt-5 btn-primary">Reset Password</button>
        </form>
    </div>
</template>

<style scoped>

</style>
