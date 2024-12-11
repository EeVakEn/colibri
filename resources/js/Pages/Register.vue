<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { route } from "ziggy-js";

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
    <div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-lg font-extrabold text-gray-900">
                    Registration
                </h2>
            </div>

            <form @submit.prevent="register" class="mt-8 space-y-6">
                <!-- Name -->
                <div>
                    <input
                        v-model="form.name"
                        id="name"
                        name="name"
                        type="text"
                        placeholder="Name"
                        :class="{'border-red-500': form.errors.name}"
                        class="appearance-none rounded relative block w-full px-3 py-2 border placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    />
                    <div v-if="form.errors.name" class="text-red-500 text-sm">
                        {{ form.errors.name }}
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <input
                        v-model="form.email"
                        id="email"
                        name="email"
                        type="email"
                        placeholder="E-mail"
                        :class="{'border-red-500': form.errors.email}"
                        class="appearance-none rounded relative block w-full px-3 py-2 border placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    />
                    <div v-if="form.errors.email" class="text-red-500 text-sm">
                        {{ form.errors.email }}
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <input
                        v-model="form.password"
                        id="password"
                        name="password"
                        type="password"
                        placeholder="Password"
                        :class="{'border-red-500': form.errors.password}"
                        class="appearance-none rounded relative block w-full px-3 py-2 border placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    />
                    <div v-if="form.errors.password" class="text-red-500 text-sm">
                        {{ form.errors.password }}
                    </div>
                </div>

                <!-- Password Confirmation -->
                <div>
                    <input
                        v-model="form.password_confirmation"
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        placeholder="Confirm password"
                        :class="{'border-red-500': form.errors.password_confirmation}"
                        class="appearance-none rounded relative block w-full px-3 py-2 border placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
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
            </form>
        </div>
    </div>
</template>
