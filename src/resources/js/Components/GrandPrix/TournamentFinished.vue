<template>
    <div class="space-y-6">
        <div class="text-center space-y-2">
            <p class="text-5xl mb-3">🏆</p>
            <h2
                class="text-3xl font-black tracking-widest uppercase text-[#1a3a4a]"
            >
                Tournament Over
            </h2>
            <p class="text-sm text-[#1a3a4a]/50">
                {{ standings[0]?.name }} wins!
            </p>
        </div>
        <div
            class="bg-white/70 rounded-2xl border border-[#1a3a4a]/10 overflow-hidden"
        >
            <div class="px-5 py-3 border-b border-[#1a3a4a]/10">
                <span
                    class="text-xs font-black tracking-widest uppercase text-[#1a3a4a]"
                    >Final Standings</span
                >
            </div>
            <div class="divide-y divide-[#1a3a4a]/5">
                <div
                    v-for="(entry, index) in standings"
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
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <p class="font-black text-[#1a3a4a]">
                                {{ entry.name }}
                            </p>
                            <span
                                v-if="entry.player_id === playerId"
                                class="text-xs text-[#1a3a4a]/40"
                                >(you)</span
                            >
                        </div>
                    </div>
                    <p class="font-black text-[#1a3a4a]">
                        {{ entry.total_score }} pts
                    </p>
                </div>
            </div>
        </div>
        <div class="flex flex-col gap-3">
            <PrimaryButton href="/grand-prix">Play Again</PrimaryButton>
            <OutlineButton href="/">Home</OutlineButton>
        </div>
    </div>
</template>

<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import OutlineButton from "@/Components/OutlineButton.vue";

defineProps({
    standings: { type: Array, default: () => [] },
    playerId: { type: Number, required: true },
});

function rankClass(index) {
    if (index === 0) return "bg-yellow-400 text-white";
    if (index === 1) return "bg-gray-300 text-gray-700";
    if (index === 2) return "bg-amber-600 text-white";
    return "bg-[#1a3a4a]/10 text-[#1a3a4a]/60";
}
</script>
