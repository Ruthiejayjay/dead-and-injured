<template>
    <Teleport to="body">
        <div
            class="fixed inset-0 flex items-center justify-center z-50 px-4 bg-white/10 backdrop-blur-md"
            @click.self="$emit('close')"
        >
            <div
                class="bg-[#f0ede4] rounded-2xl border-2 border-[#1a3a4a] w-full max-w-md p-8 relative shadow-2xl"
            >
                <!-- Floating Title -->
                <div class="absolute -top-5 left-1/2 -translate-x-1/2">
                    <div
                        class="bg-[#f0ede4] border-2 border-[#1a3a4a] rounded-xl px-6 py-2 shadow-md"
                    >
                        <h2
                            class="font-hanuman text-sm font-black tracking-widest uppercase text-[#1a3a4a]"
                        >
                            How To Play
                        </h2>
                    </div>
                </div>

                <!-- Close Button aligned with title -->
                <button
                    @click="$emit('close')"
                    class="absolute -top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full border-2 border-[#1a3a4a] bg-[#f0ede4] text-[#1a3a4a] hover:bg-[#1a3a4a] hover:text-white transition-colors font-bold text-sm shadow"
                >
                    ✕
                </button>

                <div class="font-montserrat space-y-4 text-[#1a3a4a] text-sm leading relaxed">
                    <p>
                        Guess the secret <strong>4-digit number</strong>. The
                        digits are all different.
                    </p>

                    <div class="space-y-2">
                        <div class="flex items-start gap-3">
                            <span
                                class="mt-0.5 w-5 h-5 rounded-full bg-[#0d7a6b] flex items-center justify-center flex-shrink-0"
                            >
                                <svg
                                    class="w-3 h-3 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="3"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                            </span>
                            <p>
                                <strong>Dead:</strong> Correct digit in the
                                correct position.
                            </p>
                        </div>
                        <div class="flex items-start gap-3">
                            <span
                                class="mt-0.5 w-5 h-5 rounded-full bg-[#8b1a2f] flex items-center justify-center flex-shrink-0"
                            >
                                <svg
                                    class="w-3 h-3 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="3"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </span>
                            <p>
                                <strong>Injured:</strong> Correct digit but in
                                the wrong position.
                            </p>
                        </div>
                    </div>

                    <div
                        class="bg-[#1a3a4a]/10 rounded-xl p-4 text-xs space-y-1"
                    >
                        <p class="font-bold text-[#1a3a4a]">Example</p>
                        <p>
                            Secret is <strong>1234</strong>. You guess
                            <strong>1356</strong>.
                        </p>
                        <p>
                            Result: <strong>1 Dead</strong> (1 is correct),
                            <strong>1 Injured</strong> (3 is right number, wrong
                            place).
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script setup>
defineEmits(["close"]);
</script>
