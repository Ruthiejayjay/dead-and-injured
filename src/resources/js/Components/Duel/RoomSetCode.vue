<template>
    <div class="space-y-6">
        <div class="text-center space-y-2">
            <h2
                class="text-3xl font-black tracking-widest uppercase text-[#1a3a4a]"
            >
                Set a Code
            </h2>
            <p class="text-sm text-[#1a3a4a]/50">
                {{
                    player.ready
                        ? "Waiting for " +
                          opponent.name +
                          " to set their code..."
                        : "Set the secret code your opponent must crack"
                }}
            </p>
        </div>

        <GameCard>
            <!-- Players ready status -->
            <div class="space-y-3 mb-6">
                <PlayerReadyRow :player="player" is-you />
                <PlayerReadyRow :player="opponent" />
            </div>

            <!-- Code input -->
            <div v-if="!player.ready" class="space-y-3">
                <input
                    v-model="secretCode"
                    type="text"
                    inputmode="numeric"
                    maxlength="4"
                    placeholder="Enter 4 digits"
                    @input="sanitize"
                    class="w-full py-4 px-5 rounded-xl border-2 border-[#1a3a4a]/20 bg-white text-[#1a3a4a] font-mono font-bold text-2xl text-center tracking-[0.5em] focus:outline-none focus:border-[#0d7a6b] transition-colors placeholder:tracking-normal placeholder:text-base placeholder:font-normal placeholder:text-[#1a3a4a]/30"
                />
                <p
                    v-if="error"
                    class="text-xs text-[#8b1a2f] font-semibold pl-1"
                >
                    {{ error }}
                </p>
                <p class="text-xs text-[#1a3a4a]/40 text-center">
                    All 4 digits must be different
                </p>
                <PrimaryButton
                    :disabled="secretCode.length !== 4"
                    @click="submit"
                >
                    Confirm Code
                </PrimaryButton>
            </div>

            <div v-else class="flex flex-col items-center gap-2 py-4">
                <div
                    class="w-8 h-8 rounded-full border-2 border-[#0d7a6b]/40 border-t-[#0d7a6b] animate-spin"
                ></div>
                <p class="text-sm text-[#1a3a4a]/50">
                    Waiting for {{ opponent?.name }}...
                </p>
            </div>
        </GameCard>
    </div>
</template>

<script setup>
import { ref } from "vue";
import GameCard from "@/Components/GameCard.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import PlayerReadyRow from "@/Components/Duel/PlayerReadyRow.vue";

const props = defineProps({
    player: { type: Object, required: true },
    opponent: { type: Object, required: true },
});

const emit = defineEmits(["submit"]);

const secretCode = ref("");
const error = ref("");

function sanitize() {
    const seen = [];
    secretCode.value = secretCode.value
        .replace(/\D/g, "")
        .split("")
        .filter((d) => {
            if (seen.includes(d)) return false;
            seen.push(d);
            return true;
        })
        .join("")
        .slice(0, 4);
    error.value = "";
}

function submit() {
    if (secretCode.value.length !== 4) return;
    emit("submit", secretCode.value);
}
</script>
