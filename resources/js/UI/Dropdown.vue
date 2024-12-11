<template>
    <div class="relative inline-block text-left" v-click-outside="closeDropdown">
        <!-- Триггер для открытия дропдауна -->
        <span @click="toggleDropdown">
      <slot name="button"></slot>
    </span>

        <!-- Содержимое дропдауна -->
        <div
            v-if="isOpen"
            class="origin-top-right absolute right-0 my-2 w-56 rounded-2xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
        >
            <slot></slot>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

const isOpen = ref(false);

function toggleDropdown() {
    isOpen.value = !isOpen.value;
}

function closeDropdown() {
    isOpen.value = false;
}
</script>
