<script setup>
import {Link, useForm} from '@inertiajs/vue3';
import {route} from "ziggy-js";
import Logo from "@/UI/Logo.vue";

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

function register() {
    form.post(route('register'));
}
</script>

<script>
import EmptyLayout from "@/Layouts/EmptyLayout.vue";

export default {
    layout: EmptyLayout
}
</script>

<template>
    <div class="h-screen w-screen flex items-center justify-center ">
        <div class="max-w-md w-full space-y-8">
            <Logo class="justify-center"></Logo>

            <div>
                <h2 class="mt-6 text-center text-lg font-extrabold text-gray-900">
                    Registration
                </h2>
            </div>

            <form @submit.prevent="register" class="mt-8 space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="input-label">Name</label>
                    <input
                        v-model="form.name"
                        id="name"
                        name="name"
                        type="text"
                        placeholder="Name"
                        :class="{'border-red-500': form.errors.name}"
                        class="input-field"
                    />
                    <div v-if="form.errors.name" class="text-red-500 text-sm">
                        {{ form.errors.name }}
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="input-label">Email</label>
                    <input
                        v-model="form.email"
                        id="email"
                        name="email"
                        type="email"
                        placeholder="E-mail"
                        :class="{'border-red-500': form.errors.email}"
                        class="input-field"
                    />
                    <div v-if="form.errors.email" class="text-red-500 text-sm">
                        {{ form.errors.email }}
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="input-label">Password</label>
                    <input
                        v-model="form.password"
                        id="password"
                        name="password"
                        type="password"
                        placeholder="Password"
                        :class="{'border-red-500': form.errors.password}"
                        class="input-field"
                    />
                    <div v-if="form.errors.password" class="text-red-500 text-sm">
                        {{ form.errors.password }}
                    </div>
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="input-label">Password Again</label>
                    <input
                        v-model="form.password_confirmation"
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        placeholder="Confirm password"
                        :class="{'border-red-500': form.errors.password_confirmation}"
                        class="input-field"
                    />
                    <div v-if="form.errors.password_confirmation" class="text-red-500 text-sm">
                        {{ form.errors.password_confirmation }}
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button
                        type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        :disabled="form.processing"
                    >
                        Register
                    </button>
                </div>
                <div class="mx-auto text-center mt-2">
                    <Link :href="route('login')">Sign In</Link>
                </div>
            </form>
        </div>
    </div>
</template>
