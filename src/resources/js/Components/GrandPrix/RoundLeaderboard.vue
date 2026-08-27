<template>
    <div class="space-y-6">
        <div class="text-center space-y-1">
            <p
                class="text-xs font-black tracking-widest uppercase text-[#1a3a4a]/40"
            >
                {{
                    isLastRound
                        ? "Final Round"
                        : `Round ${roundNumber} of ${totalRounds}`
                }}
            </p>
            <h2
                class="text-3xl font-black tracking-widest uppercase text-[#1a3a4a]"
            >
                Leaderboard
            </h2>
            <p v-if="secretCode" class="text-sm text-[#1a3a4a]/50">
                The code was
                <span class="font-black text-[#1a3a4a]">{{ secretCode }}</span>
            </p>
        </div>
        <div
            class="bg-white/70 rounded-2xl border border-[#1a3a4a]/10 overflow-hidden"
        >
            <div class="divide-y divide-[#1a3a4a]/5">
                <div
                    v-for="(entry, index) in leaderboard"
                    :key="entry.player_id"
                    :class="[
                        'flex items-center gap-4 px-5 py-4',
                        entry.player_id === playerId ? 'bg-[#0d7a6b]/5' : '',
                    ]"
                >
                    <div
                        :class="[
                            'w-8 h-8 rounded-full flex items-center justify-center font-black text-sm flex-shrink-0',
                            rankClass(index),
                        ]"
                    >
                        {{ index + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="font-black text-[#1a3a4a] truncate">
                                {{ entry.name }}
                            </p>
                            <span
                                v-if="entry.player_id === playerId"
                                class="text-xs text-[#1a3a4a]/40"
                                >(you)</span
                            >
                        </div>
                        <p class="text-xs text-[#1a3a4a]/40 mt-0.5">
                            {{
                                entry.solved
                                    ? `Solved in ${entry.guesses_used} guesses · ${entry.seconds_taken}s`
                                    : "Did not solve"
                            }}
                        </p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="font-black text-[#1a3a4a]">
                            {{ entry.total_score }}
                        </p>
                        <p
                            :class="[
                                'text-xs font-bold',
                                entry.round_score > 0
                                    ? 'text-[#0d7a6b]'
                                    : 'text-[#1a3a4a]/30',
                            ]"
                        >
                            +{{ entry.round_score }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Host advances, others wait -->
        <PrimaryButton
            v-if="isHost && !isLastRound"
            @click="$emit('next-round')"
        >
            Start Round {{ roundNumber + 1 }}
        </PrimaryButton>
        <div
            v-else-if="!isHost && !isLastRound"
            class="flex items-center justify-center gap-2 py-3"
        >
            <div
                class="w-4 h-4 rounded-full border-2 border-[#0d7a6b]/40 border-t-[#0d7a6b] animate-spin"
            ></div>
            <p class="text-sm text-[#1a3a4a]/50">
                Waiting for host to start next round...
            </p>
        </div>
    </div>
</template>

<script setup>
import PrimaryButton from "../PrimaryButton.vue";

const props = defineProps({
    roundNumber: { type: Number, required: true },
    totalRounds: { type: Number, required: true },
    secretCode: { type: String, default: null },
    leaderboard: { type: Array, default: () => [] },
    playerId: { type: Number, required: true },
    isHost: { type: Boolean, default: false },
    isLastRound: { type: Boolean, default: false },
});
defineEmits(["next-round"]);

function rankClass(index) {
    if (index === 0) return "bg-yellow-400 text-white";
    if (index === 1) return "bg-gray-300 text-gray-700";
    if (index === 2) return "bg-amber-600 text-white";
    return "bg-[#1a3a4a]/10 text-[#1a3a4a]/60";
}
</script>
