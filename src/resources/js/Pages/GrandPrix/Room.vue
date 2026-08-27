<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-[#dce9f0] px-4 py-10">
        <div class="w-full max-w-md">
            <div class="mb-6">
                <BackLink href="/play" />
            </div>

            <!-- Tournament Finished — highest priority -->
            <TournamentFinished
                v-if="tournamentFinished"
                :standings="finalStandings"
                :player-id="player.id"
            />

            <!-- Waiting for Players -->
            <div v-else-if="room.status === 'waiting'" class="space-y-6">
                <div class="text-center space-y-2">
                    <h2 class="text-3xl font-black tracking-widest uppercase text-[#1a3a4a]">Lobby</h2>
                    <p class="text-sm text-[#1a3a4a]/50">Waiting for players to join</p>
                </div>

                <GameCard>
                    <p class="text-xs font-black tracking-widest uppercase text-[#1a3a4a]/40 mb-2 text-center">Room Code</p>
                    <div class="flex items-center justify-center gap-3 mb-6">
                        <span class="font-mono font-black text-4xl tracking-[0.4em] text-[#1a3a4a]">{{ room.code }}</span>
                        <button @click="copyCode" class="p-2 rounded-lg bg-[#1a3a4a]/10 hover:bg-[#1a3a4a]/20 transition-colors">
                            <svg v-if="!copied" class="w-4 h-4 text-[#1a3a4a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <svg v-else class="w-4 h-4 text-[#0d7a6b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </button>
                    </div>

                    <p class="text-xs font-black tracking-widest uppercase text-[#1a3a4a]/40 mb-3">
                        Players ({{ players.length }}/{{ room.max_players }})
                    </p>
                    <div class="space-y-2 mb-6">
                        <div v-for="p in players" :key="p.id" class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-[#0d7a6b]"></div>
                            <span class="font-bold text-[#1a3a4a] text-sm">{{ p.name }}</span>
                            <span v-if="p.id === player.id" class="text-xs text-[#1a3a4a]/40">(you)</span>
                            <span v-if="p.is_host" class="text-xs font-bold tracking-widest uppercase text-[#0d7a6b]">Host</span>
                        </div>
                    </div>

                    <div class="bg-[#1a3a4a]/5 rounded-xl p-3 text-xs text-[#1a3a4a]/50 space-y-1 mb-6">
                        <p><span class="font-bold">Rounds:</span> {{ room.total_rounds }}</p>
                        <p><span class="font-bold">Max Guesses:</span> {{ room.max_guesses }} per round</p>
                        <p><span class="font-bold">Time Limit:</span> {{ room.time_limit }}s per round</p>
                    </div>

                    <PrimaryButton v-if="player.is_host" :disabled="players.length < 2 || starting" @click="startTournament">
                        {{ starting ? 'Starting...' : 'Start Tournament' }}
                    </PrimaryButton>
                    <div v-else class="flex items-center justify-center gap-2 py-2">
                        <div class="w-4 h-4 rounded-full border-2 border-[#0d7a6b]/40 border-t-[#0d7a6b] animate-spin"></div>
                        <p class="text-sm text-[#1a3a4a]/50">Waiting for host to start...</p>
                    </div>
                </GameCard>
            </div>

            <!-- Round Leaderboard -->
            <RoundLeaderboard
                v-else-if="showLeaderboard && currentLeaderboard"
                :round-number="currentLeaderboard.round_number"
                :total-rounds="room.total_rounds"
                :secret-code="currentLeaderboard.secret_code"
                :leaderboard="currentLeaderboard.leaderboard"
                :player-id="player.id"
                :is-host="player.is_host"
                :is-last-round="currentLeaderboard.is_last_round"
                @next-round="startNextRound"
            />

            <!-- Playing -->
            <RoundPlaying
                v-else-if="room.status === 'playing' && roundStartedAt"
                :current-round="room.current_round"
                :total-rounds="room.total_rounds"
                :time-limit="room.time_limit"
                :max-guesses="room.max_guesses"
                :started-at="roundStartedAt"
                :history="history"
                :submitting="submitting"
                :solved="solved"
                :max-guesses-reached="maxGuessesReached"
                :guesses-used="guessesUsed"
                :round-score="roundScore"
                @guess="submitGuess"
                @time-expired="onTimeExpired"
            />

            <!-- Starting round spinner -->
            <div v-else class="flex flex-col items-center justify-center py-20 gap-3">
                <div class="w-8 h-8 rounded-full border-2 border-[#0d7a6b]/40 border-t-[#0d7a6b] animate-spin"></div>
                <p class="text-sm text-[#1a3a4a]/50">Starting round...</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import BackLink from '@/Components/BackLink.vue'
import GameCard from '@/Components/GameCard.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import RoundPlaying from '@/Components/GrandPrix/RoundPlaying.vue'
import RoundLeaderboard from '@/Components/GrandPrix/RoundLeaderboard.vue'
import TournamentFinished from '@/Components/GrandPrix/TournamentFinished.vue'

const props = defineProps({
    room: Object,
    player: Object,
    players: Array,
})

// ── State ──────────────────────────────────────────────────
const room = ref(props.room)
const player = ref(props.player)
const players = ref(props.players)

const copied = ref(false)
const starting = ref(false)
const submitting = ref(false)
const solved = ref(false)
const maxGuessesReached = ref(false)
const guessesUsed = ref(0)
const roundScore = ref(0)
const history = ref([])
const roundStartedAt = ref(null)
const showLeaderboard = ref(false)
const tournamentFinished = ref(false)
const currentLeaderboard = ref(null)
const finalStandings = ref([])

// Track what we've already shown to avoid duplicate transitions
const lastShownRound = ref(0)
const lastShownLeaderboardRound = ref(0)

let channel = null
let pollInterval = null

// ── Helpers ────────────────────────────────────────────────
function copyCode() {
    navigator.clipboard.writeText(room.value.code)
    copied.value = true
    setTimeout(() => copied.value = false, 2000)
}

function resetRoundState() {
    solved.value = false
    maxGuessesReached.value = false
    guessesUsed.value = 0
    roundScore.value = 0
    history.value = []
    showLeaderboard.value = false
    roundStartedAt.value = null
}

function applyRoundStart(roundNumber, startedAt) {
    if (roundNumber === lastShownRound.value && roundStartedAt.value) return
    resetRoundState()
    room.value.status = 'playing'
    room.value.current_round = roundNumber
    roundStartedAt.value = startedAt
    lastShownRound.value = roundNumber
    starting.value = false
}

function applyLeaderboard(leaderboardData) {
    if (leaderboardData.round_number === lastShownLeaderboardRound.value) return
    currentLeaderboard.value = leaderboardData
    showLeaderboard.value = true
    lastShownLeaderboardRound.value = leaderboardData.round_number
}

function applyTournamentFinished(standings) {
    if (tournamentFinished.value) return
    finalStandings.value = standings
    tournamentFinished.value = true
    stopPolling()
}

// ── Actions ────────────────────────────────────────────────
async function startTournament() {
    starting.value = true
    try {
        await axios.post(route('grand-prix.start', room.value.code))
    } catch (e) {
        starting.value = false
    }
}

async function startNextRound() {
    try {
        await axios.post(route('grand-prix.start', room.value.code))
    } catch (e) {}
}

async function submitGuess(guess) {
    submitting.value = true
    try {
        const { data } = await axios.post(route('grand-prix.guess', room.value.code), { guess })
        history.value.unshift({ guess, dead: data.dead, injured: data.injured })
        guessesUsed.value = data.guesses_used
        if (data.solved) {
            solved.value = true
            roundScore.value = data.score
        } else if (data.max_guesses_reached) {
            maxGuessesReached.value = true
        }
    } finally {
        submitting.value = false
    }
}

async function onTimeExpired() {
    if (solved.value || maxGuessesReached.value) return
    maxGuessesReached.value = true
    try {
        await axios.post(route('grand-prix.finish-round', room.value.code))
    } catch (e) {
        // 422 = already finished, that's fine
    }
}

// ── Polling ────────────────────────────────────────────────
// Single polling function that handles all state transitions
function startPolling() {
    clearInterval(pollInterval)
    pollInterval = setInterval(async () => {
        try {
            const { data } = await axios.get(route('grand-prix.status', room.value.code))

            // Always update player list
            if (data.players) players.value = data.players

            // Priority 1: Tournament finished
            if (data.status === 'finished' && data.final_standings) {
                applyTournamentFinished(data.final_standings)
                return
            }

            // Priority 2: Leaderboard available (round just finished)
            if (data.leaderboard && data.last_finished_round) {
                applyLeaderboard(data.leaderboard)
                // Don't return — also check if next round has started
            }

            // Priority 3: New round started
            if (data.status === 'playing' && data.round_started_at) {
                // Only transition if it's a genuinely new round or we haven't loaded this round yet
                const isNewRound = data.current_round !== room.value.current_round
                const notLoaded = !roundStartedAt.value && !showLeaderboard.value

                if (isNewRound || notLoaded) {
                    applyRoundStart(data.current_round, data.round_started_at)
                }
            }

            // Priority 4: Waiting room - update status
            if (data.status === 'waiting' && room.value.status !== 'waiting') {
                room.value.status = 'waiting'
            }

        } catch (e) {}
    }, 2000)
}

function stopPolling() {
    clearInterval(pollInterval)
}

// ── Broadcasting ───────────────────────────────────────────
onMounted(() => {
    startPolling()

    channel = window.Echo.channel(`room.${room.value.code}`)

    channel.listen('.PlayerJoined', (e) => {
        if (!players.value.find(p => p.id === e.player.id)) {
            players.value.push(e.player)
        }
    })

    channel.listen('.RoundStarted', (e) => {
        applyRoundStart(e.round_number, e.started_at)
    })

    channel.listen('.RoundFinished', (e) => {
        applyLeaderboard({
            round_number: e.round_number,
            total_rounds: e.total_rounds,
            secret_code: e.secret_code,
            leaderboard: e.leaderboard,
            is_last_round: e.is_last_round,
        })
    })

    channel.listen('.TournamentFinished', (e) => {
        applyTournamentFinished(e.final_standings)
    })
})

onUnmounted(() => {
    stopPolling()
    channel?.stopListening('.PlayerJoined')
    channel?.stopListening('.RoundStarted')
    channel?.stopListening('.RoundFinished')
    channel?.stopListening('.TournamentFinished')
})
</script>