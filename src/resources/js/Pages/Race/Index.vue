<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-[#dce9f0] px-4">
        <div class="w-full max-w-md space-y-8">

            <BackLink href="/multiplayer" />

            <div class="text-center">
                <h2 class="text-3xl font-black tracking-widest uppercase text-[#1a3a4a]">Race Mode</h2>
                <p class="text-sm text-[#1a3a4a]/50 mt-2">First to crack the code wins</p>
            </div>

            <GameCard>
                <h3 class="text-lg font-black tracking-wider uppercase text-[#1a3a4a] mb-4">Create a Room</h3>
                <div class="space-y-3">
                    <input
                        v-model="form.name"
                        type="text"
                        placeholder="Your name"
                        maxlength="20"
                        @keyup.enter="createRoom"
                        class="w-full py-3 px-4 rounded-xl border-2 border-[#1a3a4a]/20 bg-white text-[#1a3a4a] font-medium focus:outline-none focus:border-[#0d7a6b] transition-colors placeholder:text-[#1a3a4a]/30"
                    />
                    <p v-if="form.errors.player_name" class="text-xs text-[#8b1a2f] font-semibold pl-1">
                        {{ form.errors.player_name }}
                    </p>
                    <PrimaryButton :disabled="form.processing || !form.name" @click="createRoom">
                        {{ form.processing ? 'Creating...' : 'Create Room' }}
                    </PrimaryButton>
                </div>
            </GameCard>

        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import BackLink from '@/Components/BackLink.vue'
import GameCard from '@/Components/GameCard.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

const form = useForm({ player_name: '', name: '' })

function createRoom() {
    form.player_name = form.name
    form.post(route('race.create'))
}
</script>