<template>
    <div
        class="min-h-screen flex flex-col items-center justify-center bg-[#dce9f0] px-4 py-8 sm:py-12 md:py-16"
    >
        <div class="w-full max-w-md space-y-6">
            <div class="mb-6">
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
            <GameCard>
                <div class="flex items-center gap-3 mb-2">
                    <div
                        class="w-10 h-10 flex items-center justify-center flex-shrink-0 bg-[#dce9f0] rounded-full"
                    >
                        <img
                            :src="raceIcon"
                            alt="Race Mode"
                            class="w-6 h-6 object-contain"
                        />
                    </div>
                    <div>
                        <h3
                            class="text-lg font-montserrat font-black tracking-wider uppercase text-[#1a3a4a]"
                        >
                            Race Mode
                        </h3>
                        <p class="text-xs text-[#1a3a4a]/50">
                            Speed competition
                        </p>
                    </div>
                </div>
                <p class="text-sm text-[#1a3a4a]/60 leading-relaxed">
                    Both players get the same secret code. First one to crack it
                    wins.
                </p>
                <PrimaryButton href="/race">Play</PrimaryButton>
            </GameCard>

            <!-- Grand Prix -->
            <GameCard>
                <div class="flex items-center gap-3 mb-2">
                    <div
                        class="w-10 h-10 flex items-center justify-center flex-shrink-0 bg-[#dce9f0] rounded-full"
                    >
                        <img
                            :src="grandPrixIcon"
                            alt="Grand Prix"
                            class="w-6 h-6 object-contain"
                        />
                    </div>
                    <div>
                        <h3
                            class="text-lg font-montserrat font-black tracking-wider uppercase text-[#1a3a4a]"
                        >
                            Grand Prix
                        </h3>
                        <p class="text-xs text-[#1a3a4a]/50">
                            Speed competition
                        </p>
                    </div>
                </div>
                <p class="text-sm text-[#1a3a4a]/60 leading-relaxed">
                    Tournament mode with multiple rounds, time limits, and points.
                </p>
                <PrimaryButton href="/grand-prix">Play</PrimaryButton>
            </GameCard>
        </div>
    </div>
</template>

<script setup>
import BackLink from "../Components/BackLink.vue";
import GameCard from "../Components/GameCard.vue";
import PrimaryButton from "../Components/PrimaryButton.vue";
import ComingSoonCard from "../Components/ComingSoonCard.vue";
import duelIcon from "../assets/icons/duel.svg";
import raceIcon from "../assets/icons/race.svg";
import grandPrixIcon from "../assets/icons/grand-prix.svg";
</script>
