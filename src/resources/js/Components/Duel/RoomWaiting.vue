<template>
    <div class="space-y-6">
        <div class="text-center space-y-2">
            <h2
                class="text-3xl font-black tracking-widest uppercase text-[#1a3a4a]"
            >
                Waiting...
            </h2>
            <p class="text-sm text-[#1a3a4a]/50">
                Share the room code with your opponent
            </p>
        </div>

        <GameCard>
            <p
                class="text-xs font-black tracking-widest uppercase text-[#1a3a4a]/40 mb-2 text-center"
            >
                Room Code
            </p>
            <div class="flex items-center justify-center gap-3">
                <span
                    class="font-mono font-black text-4xl tracking-[0.4em] text-[#1a3a4a]"
                    >{{ code }}</span
                >
                <button
                    @click="copyCode"
                    class="p-2 rounded-lg bg-[#1a3a4a]/10 hover:bg-[#1a3a4a]/20 transition-colors"
                >
                    <svg
                        v-if="!copied"
                        class="w-4 h-4 text-[#1a3a4a]"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                        />
                    </svg>
                    <svg
                        v-else
                        class="w-4 h-4 text-[#0d7a6b]"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>
                </button>
            </div>
        </GameCard>

        <GameCard>
            <p
                class="text-xs font-black tracking-widest uppercase text-[#1a3a4a]/40 mb-4"
            >
                Players
            </p>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-[#0d7a6b]"></div>
                        <span class="font-bold text-[#1a3a4a]">{{
                            player.name
                        }}</span>
                        <span class="text-xs text-[#1a3a4a]/40">(you)</span>
                    </div>
                    <span
                        class="text-xs font-bold tracking-widest uppercase text-[#0d7a6b]"
                        >Ready</span
                    >
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div
                            class="w-2 h-2 rounded-full bg-[#1a3a4a]/20 animate-pulse"
                        ></div>
                        <span class="font-bold text-[#1a3a4a]/40">
                            {{
                                opponent
                                    ? opponent.name
                                    : "Waiting for opponent..."
                            }}
                        </span>
                    </div>
                </div>
            </div>
        </GameCard>
    </div>
</template>

<script setup>
import { ref } from "vue";
import GameCard from "@/Components/GameCard.vue";

const props = defineProps({
    code: { type: String, required: true },
    player: { type: Object, required: true },
    opponent: { type: Object, default: null },
});

const copied = ref(false);

function copyCode() {
    navigator.clipboard.writeText(props.code);
    copied.value = true;
    setTimeout(() => (copied.value = false), 2000);
}
</script>
