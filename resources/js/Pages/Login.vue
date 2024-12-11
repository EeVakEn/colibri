<script setup>
import { Link, useForm} from '@inertiajs/vue3';
import {defineComponent} from "vue";
import {route} from "ziggy-js";

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
    <div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-lg font-extrabold text-gray-900">
                    Войти
                </h2>
            </div>

            <form @submit.prevent="login" class="mt-8 space-y-6" action="#" method="POST">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="email-address" class="sr-only">E-mail</label>
                        <input v-model="form.email"
                               :class="{'border-red-500': form.errors.email}"
                               id="email-address" name="email" type="email" autocomplete="email" required="" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="E-mail" />
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input v-model="form.password"
                               :class="{'border-red-500': form.errors.email}"
                               id="password" name="password" type="password" autocomplete="current-password" required="" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Пароль" />
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
                    <p>
                        Are u have an acc? Create it by link. <Link :href="route('registerForm')">Register</Link>
                    </p>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>

</style>
