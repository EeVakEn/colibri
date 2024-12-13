<template>
    <div v-if="visible" class="fixed inset-0 flex items-center justify-center z-50">
        <!-- Background overlay -->
        <div
            class="absolute inset-0 bg-black bg-opacity-50"
            @click="close"
        ></div>

        <!-- Modal content -->
        <div
            class="relative bg-white rounded-lg shadow-lg w-[90%] max-w-md p-6"
            role="dialog"
            aria-modal="true"
        >
            <!-- Close button -->
            <button
                class="absolute top-3 right-3 text-gray-600 hover:text-gray-900"
                @click="close"
            >
                <X/>
            </button>

            <!-- Slot for modal content -->
            <slot></slot>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, defineEmits, defineProps } from 'vue';
import { X } from  'lucide-vue-next'

// Props and emits
const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true,
    },
});

const emit = defineEmits(['update:modelValue']);

// Local state for modal visibility
const visible = ref(props.modelValue);

// Watch for external changes to visibility
watch(() => props.modelValue, (newVal) => {
    visible.value = newVal;
});

// Close modal function
function close() {
    visible.value = false;
    emit('update:modelValue', false);
}
</script>
