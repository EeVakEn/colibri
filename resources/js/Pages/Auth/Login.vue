<script setup>
import { Link, useForm} from '@inertiajs/vue3';
import {defineComponent} from "vue";
import {route} from "ziggy-js";
import Logo from "@/UI/Logo.vue";

defineComponent({
    Link
})
const form = useForm({
    email: null,
    password: null,
})
function login() {
    form.post(route('login'))
}
</script>
<script>
import EmptyLayout from '@/Layouts/EmptyLayout.vue';

export default {
    layout: EmptyLayout,
};
</script>

<template>
    <Head :title="title"></Head>
    <div class="h-screen w-screen flex items-center justify-center ">
        <div class="max-w-md w-full space-y-8">

            <Logo class="justify-center"></Logo>
            <div>
                <h2 class="mt-6 text-center text-lg font-extrabold text-gray-900">
                    Sign In
                </h2>
            </div>

            <form @submit.prevent="login" class="mt-8 space-y-6" action="#" method="POST">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="email-address" class="input-label mt-2">E-mail</label>
                        <input v-model="form.email"
                               :class="{'border-red-500': form.errors.email}"
                               id="email-address" name="email" type="email" autocomplete="email" required=""
                               class="input-field" placeholder="E-mail" />
                    </div>
                    <div>
                        <label for="password" class="input-label mt-2">Password</label>
                        <input v-model="form.password"
                               :class="{'border-red-500': form.errors.email}"
                               id="password" name="password" type="password" autocomplete="current-password"
                               required=""
                               class="input-field" placeholder="Password" />
                    </div>
                </div>

                <div class="text-red-500" v-if="form.errors.email">{{ form.errors.email }}</div>

                <div>
                    <button type="submit"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            :disabled="form.processing"
                    >
                        Login
                    </button>
                </div>

                <div>
                    <p class="flex justify-between">
                        <Link :href="route('registerForm')">Register</Link>
                        <Link :href="route('forgotPassword')">Forgot Password?</Link>
                    </p>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>

</style>
