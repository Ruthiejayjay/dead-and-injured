<template>
    <component
        :is="href ? Link : 'button'"
        :href="href"
        :disabled="!href && disabled"
        :class="[baseClass, disabled && !href ? disabledClass : variantClass]"
        @click="!href && !disabled ? $emit('click') : null"
    >
        <slot />
    </component>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    href: { type: String, default: null },
    disabled: { type: Boolean, default: false },
    variant: { type: String, default: "green" }, // green | purple | disabled
});

defineEmits(["click"]);

const baseClass =
    "block w-full py-3.5 rounded-full font-hanuman text-center font-bold tracking-[0.2em] uppercase text-sm transition-all active:scale-[0.98] text-white border";

const variantClass = computed(() => {
    switch (props.variant) {
        case "purple":
            return `
                border-[#1c002d]
                bg-[linear-gradient(180deg,#51002C_0%,#B10773_100%),linear-gradient(180deg,rgba(28,0,45,0.53)_0%,rgba(115,7,177,0)_100%)]
                shadow-[inset_0_2px_4px_rgba(255,255,255,0.2),0_4px_10px_rgba(0,0,0,0.3)]
                hover:brightness-110
            `;

        case "green":
        default:
            return `
                border-[#002b2d]
                bg-[linear-gradient(180deg,#005143_0%,#07B1B1_100%),linear-gradient(180deg,rgba(0,43,45,0.53)_0%,rgba(7,177,163,0)_100%)]
                shadow-[inset_0_2px_4px_rgba(255,255,255,0.2),0_4px_10px_rgba(0,0,0,0.25)]
                hover:brightness-110
            `;
    }
});

const disabledClass = `
    border-[#888]
    bg-[linear-gradient(180deg,#d3d3d3_0%,#f0f0f0_100%)]
    text-[#666]
    cursor-not-allowed
`;
</script>
