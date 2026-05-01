<template>
    <Link
        :href="href"
        class="flex items-center gap-2 text-[#1a3a4a]/60 hover:text-[#1a3a4a] transition-colors text-sm w-fit"
    >
        <svg
            class="w-4 h-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 19l-7-7 7-7"
            />
        </svg>

        {{ label }}</Link
    >
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    href: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        default: "Back",
    },
});
</script>
