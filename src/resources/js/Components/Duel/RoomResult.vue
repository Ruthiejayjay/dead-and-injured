<template>
    <Teleport to="body">
        <div
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4"
        >
            <GameCard class="w-full max-w-md">
                <div class="text-center space-y-2 mb-6">
                    <p class="text-5xl mb-3">{{ result.won ? "🎉" : "💀" }}</p>
                    <h2
                        class="text-2xl font-black tracking-widest uppercase text-[#1a3a4a]"
                    >
                        {{ result.won ? "You Won!" : "You Lost!" }}
                    </h2>
                    <p class="text-sm text-[#1a3a4a]/50">
                        {{
                            result.won
                                ? opponent.name +
                                  " couldn't crack your code in time."
                                : result.winner_name +
                                  " cracked the code in " +
                                  result.winner_guesses +
                                  " guesses."
                        }}
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-6">
                    <div
                        class="bg-white rounded-xl p-4 text-center border border-[#1a3a4a]/10"
                    >
                        <p
                            class="text-xs font-bold tracking-widest uppercase text-[#1a3a4a]/40 mb-1"
                        >
                            Your Time
                        </p>
                        <TimerDisplay
                            :elapsed-ms="elapsedMs"
                            class="justify-center"
                        />
                    </div>
                    <div
                        class="bg-white rounded-xl p-4 text-center border border-[#1a3a4a]/10"
                    >
                        <p
                            class="text-xs font-bold tracking-widest uppercase text-[#1a3a4a]/40 mb-1"
                        >
                            Your Guesses
                        </p>
                        <p class="text-2xl font-black font-mono text-[#1a3a4a]">
                            {{ guessesCount }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <PrimaryButton href="/duel">Play Again</PrimaryButton>
                    <OutlineButton href="/">Home</OutlineButton>
                </div>
            </GameCard>
        </div>
    </Teleport>
</template>

<script setup>
import GameCard from "@/Components/GameCard.vue";
import TimerDisplay from "@/Components/TimerDisplay.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import OutlineButton from "@/Components/OutlineButton.vue";

defineProps({
    result: { type: Object, required: true },
    opponent: { type: Object, required: true },
    elapsedMs: { type: Number, required: true },
    guessesCount: { type: Number, required: true },
});
</script>
