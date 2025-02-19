<script setup>
    import { ref, watch } from 'vue';

    defineProps({
        modelValue: String
    });

    const emit = defineEmits(['update:modelValue']);

    const onInput = (event) => {
        emit('update:modelValue', event.target.value);
    };
</script>

<template>
    <input
        :value="modelValue"
        @input="onInput"
        type="text"
        placeholder="Search project..."
        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm w-1/3"
    />
</template>
