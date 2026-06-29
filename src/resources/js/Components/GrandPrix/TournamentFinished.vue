<template>
    <div class="space-y-6">
        <div class="text-center space-y-2">
            <p class="text-4xl mb-2">🏆</p>
            <h2
                class="text-3xl font-black tracking-widest uppercase text-[#1a3a4a]"
            >
                Tournament Over
            </h2>
        </div>
        <!-- Podium for top 3 -->
        <div
            v-if="standings.length >= 1"
            class="flex items-end justify-center gap-3 px-4 pt-8"
        >
            <!-- 2nd place -->
            <div v-if="standings[1]" class="flex flex-col items-center flex-1">
                <p
                    class="font-black text-sm text-[#1a3a4a] text-center mb-1 truncate w-full text-center"
                >
                    {{ standings[1].name }}
                    <span
                        v-if="standings[1].player_id === playerId"
                        class="text-[#1a3a4a]/40 text-xs"
                        >(you)</span
                    >
                </p>
                <p class="text-xs font-bold text-[#1a3a4a]/60 mb-2">
                    {{ standings[1].total_score }} pts
                </p>
                <div class="relative w-full">
                    <img :src="podium2nd" class="w-full" alt="2nd place" />
                    <div
                        class="absolute inset-0 flex flex-col items-center justify-center gap-1 pt-4"
                    >
                        <span class="font-black text-white text-2xl">2</span>
                        <div class="flex gap-0.5">
                            <PodiumStar size="md" />
                        </div>
                    </div>
                </div>
            </div>
            <!-- 1st place -->
            <div class="flex flex-col items-center flex-1">
                <p
                    class="font-black text-sm text-[#1a3a4a] text-center mb-1 truncate w-full text-center"
                >
                    {{ standings[0].name }}
                    <span
                        v-if="standings[0].player_id === playerId"
                        class="text-[#1a3a4a]/40 text-xs"
                        >(you)</span
                    >
                </p>
                <p class="text-xs font-bold text-[#1a3a4a]/60 mb-2">
                    {{ standings[0].total_score }} pts
                </p>
                <div class="relative w-full">
                    <img :src="podium1st" class="w-full" alt="1st place" />
                    <div
                        class="absolute inset-0 flex flex-col items-center justify-center gap-1 pt-4"
                    >
                        <span class="font-black text-white text-2xl">1</span>
                        <div class="flex gap-0.5">
                            <PodiumStar size="md" />
                        </div>
                    </div>
                </div>
            </div>
            <!-- 3rd place -->
            <div v-if="standings[2]" class="flex flex-col items-center flex-1">
                <p
                    class="font-black text-sm text-[#1a3a4a] text-center mb-1 truncate w-full text-center"
                >
                    {{ standings[2].name }}
                    <span
                        v-if="standings[2].player_id === playerId"
                        class="text-[#1a3a4a]/40 text-xs"
                        >(you)</span
                    >
                </p>
                <p class="text-xs font-bold text-[#1a3a4a]/60 mb-2">
                    {{ standings[2].total_score }} pts
                </p>
                <div class="relative w-full">
                    <img :src="podium3rd" class="w-full" alt="3rd place" />
                    <div
                        class="absolute inset-0 flex flex-col items-center justify-center gap-1 pt-4"
                    >
                        <span class="font-black text-white text-2xl">3</span>
                        <div class="flex gap-0.5">
                            <PodiumStar size="md" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rest of players -->
        <div
            v-if="standings.length > 3"
            class="bg-white/70 rounded-2xl border border-[#1a3a4a]/10 overflow-hidden"
        >
            <div class="divide-y divide-[#1a3a4a]/5">
                <div
                    v-for="(entry, index) in standings.slice(3)"
                    :key="entry.player_id"
                    :class="[
                        'flex items-center gap-4 px-5 py-3',
                        entry.player_id === playerId ? 'bg-[#0d7a6b]/5' : '',
                    ]"
                >
                    <div
                        class="w-7 h-7 rounded-full bg-[#1a3a4a]/10 flex items-center justify-center font-black text-sm text-[#1a3a4a]/60 flex-shrink-0"
                    >
                        {{ index + 4 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1">
                            <p class="font-black text-[#1a3a4a] truncate">
                                {{ entry.name }}
                            </p>
                            <span
                                v-if="entry.player_id === playerId"
                                class="text-xs text-[#1a3a4a]/40"
                                >(you)</span
                            >
                        </div>
                    </div>
                    <p class="font-black text-sm text-[#1a3a4a]">
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
import PodiumStar from "./PodiumStar.vue";
import Podium1st from "@/assets/podium-1.svg";
import Podium2nd from "@/assets/podium-2.svg";
import Podium3rd from "@/assets/podium-3.svg";

defineProps({
    standings: { type: Array, default: () => [] },
    playerId: { type: Number, required: true },
});
</script>
