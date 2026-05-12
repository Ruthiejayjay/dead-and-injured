<template>
    <div
        class="min-h-screen flex flex-col items-center justify-start bg-[#dce9f0] px-4"
    >
        <div class="w-full max-w-md space-y-6">
            <div class="flex items-center justify-between">
                <BackLink href="/play" />
            </div>

            <div class="text-center">
                <h2
                    class="text-3xl font-black font-hanuman tracking-wider uppercase text-[#1a3a4a]"
                >
                    Multiplayer
                </h2>
                <p class="font-montserrat text-sm text-[#1a3a4a]/50 mt-2">
                    Choose a game mode
                </p>
            </div>

            <!-- Duel Mode -->
            <GameCard>
                <div class="flex items-center gap-3 mb-2">
                    <div
                        class="w-10 h-10 flex items-center justify-center flex-shrink-0 bg-[#dce9f0] rounded-full"
                    >
                        <img
                            :src="duelIcon"
                            alt="Duel Mode"
                            class="w-6 h-6 object-contain"
                        />
                    </div>
                    <div>
                        <h3
                            class="text-lg font-montserrat font-black tracking-wider uppercase text-[#1a3a4a]"
                        >
                            Duel Mode
                        </h3>
                        <p class="text-xs text-[#1a3a4a]/50">Head-to-head</p>
                    </div>
                </div>
                <p class="text-sm text-[#1a3a4a]/60 leading-relaxed">
                    Head - to - head. You each set a secret and try to break the
                    other’s code first.
                </p>
                <PrimaryButton href="/duel">Play</PrimaryButton>
            </GameCard>

            <!-- Race Mode -->
            <ComingSoonCard>
                <template #icon>
                    <svg
                        class="w-5 h-5 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"
                        />
                    </svg>
                </template>
                <template #title>Race Mode</template>
                <template #description>
                    Speed competition. Players race to crack the same code
                    first.
                </template>
            </ComingSoonCard>

            <!-- Grand Prix -->
            <ComingSoonCard>
                <template #icon>
                    <svg
                        class="w-5 h-5 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 3l14 9-14 9V3z"
                        />
                    </svg>
                </template>
                <template #title>Grand Prix</template>
                <template #description>
                    Tournament mode with multiple rounds, time limits, and
                    points.
                </template>
            </ComingSoonCard>
        </div>
    </div>
</template>

<script setup>
import BackLink from "../Components/BackLink.vue";
import GameCard from "../Components/GameCard.vue";
import PrimaryButton from "../Components/PrimaryButton.vue";
import ComingSoonCard from "../Components/ComingSoonCard.vue";
import duelIcon from "../assets/icons/duel.svg";
</script>
