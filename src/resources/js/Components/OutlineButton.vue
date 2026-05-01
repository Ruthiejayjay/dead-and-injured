<template>
    <component
        :is="href ? Link : 'button'"
        :href="href"
        :class="[
            'block w-full py-3.5 rounded-full border-2 border-[#1a3a4a] text-[#1a3a4a] font-bold tracking-[0.2em] uppercase text-sm text-center transition-all active:scale-[0.98]',
            'hover:bg-[#1a3a4a] hover:text-white',
        ]"
        @click="!href ? $emit('click') : null"
    >
        <slot />
    </component>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    href: { type: String, default: null },
});

defineEmits(["click"]);
</script>
