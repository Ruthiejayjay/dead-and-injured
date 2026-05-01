<template>
    <component
        :is="href ? Link : 'button'"
        :href="href"
        :disabled="!href && disabled"
        :class="[
            'block w-full py-3.5 rounded-full text-center font-bold tracking-[0.2em] uppercase text-sm transition-all active:scale-[0.98]',
            disabled && !href
                ? 'bg-[#0d7a6b]/40 cursor-not-allowed text-white'
                : 'bg-[#0d7a6b] hover:bg-[#0a6459] text-white shadow-md shadow-[#0d7a6b]/30',
        ]"
        @click="!href && !disabled ? $emit('click') : null"
    >
        <slot />
    </component>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    href: { type: String, default: null },
    disabled: { type: Boolean, default: false },
});

defineEmits(["click"]);
</script>
