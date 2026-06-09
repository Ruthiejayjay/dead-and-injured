<template>
    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <label class="text-sm font-bold text-[#1a3a4a]">{{ label }}</label>
            <span class="text-sm font-black text-[#1a3a4a]"
                >{{ modelValue }}{{ suffix }}</span
            >
        </div>
        <input
            type="range"
            :min="min"
            :max="max"
            :step="step"
            :value="modelValue"
            @input="$emit('update:modelValue', parseInt($event.target.value))"
            class="w-full h-2 rounded-full appearance-none cursor-pointer accent-[#0d7a6b]"
            style="
                background: linear-gradient(
                    to right,
                    #0d7a6b var(--progress),
                    #1a3a4a22 var(--progress)
                );
            "
            :style="{ '--progress': progress }"
        />
    </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    modelValue: { type: Number, required: true },
    label: { type: String, required: true },
    min: { type: Number, default: 1 },
    max: { type: Number, default: 10 },
    step: { type: Number, default: 1 },
    suffix: { type: String, default: "" },
});

defineEmits(["update:modelValue"]);

const progress = computed(() => {
    const pct =
        ((props.modelValue - props.min) / (props.max - props.min)) * 100;
    return `${pct}%`;
});
</script>
