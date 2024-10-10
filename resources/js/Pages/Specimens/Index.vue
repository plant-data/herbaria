<template>
    <div class="p-6">
        <h1 class="text-2xl font-semibold mb-6">Virtual Herbarium</h1>

        <div class="mb-4">
            <button
                @click="setActiveView('table')"
                :class="{ 'bg-blue-500 text-white': activeView === 'table', 'bg-gray-200': activeView !== 'table' }"
                class="px-4 py-2 rounded-l-md"
            >
                Table View
            </button>
            <button
                @click="setActiveView('map')"
                :class="{ 'bg-blue-500 text-white': activeView === 'map', 'bg-gray-200': activeView !== 'map' }"
                class="px-4 py-2"
            >
                Map View
            </button>
            <button
                @click="setActiveView('cards')"
                :class="{ 'bg-blue-500 text-white': activeView === 'cards', 'bg-gray-200': activeView !== 'cards' }"
                class="px-4 py-2"
            >
                Card View
            </button>
            <button
                @click="setActiveView('graphs')"
                :class="{ 'bg-blue-500 text-white': activeView === 'graphs', 'bg-gray-200': activeView !== 'graphs' }"
                class="px-4 py-2 rounded-r-md"
            >
                Graph View
            </button>
        </div>

        <SpecimensTable v-if="activeView === 'table'" :specimens="specimens" />
        <SpecimensMap v-else-if="activeView === 'map'" :mapData="mapData" />
        <SpecimensCards v-else-if="activeView === 'cards'" :specimens="specimens" />
        <SpecimensGraphs v-else-if="activeView === 'graphs' && allSpecimens" :allSpecimens="allSpecimens" />
        <div v-else-if="activeView === 'graphs' && !allSpecimens" class="text-center py-4">
            Loading graphs...
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import SpecimensTable from './SpecimensTable.vue'
import SpecimensMap from './SpecimensMap.vue'
import SpecimensCards from './SpecimensCards.vue'
import SpecimensGraphs from './SpecimensGraphs.vue'

const props = defineProps({
    specimens: Object,
    mapData: Array,
})

const activeView = ref('table')
const allSpecimens = ref(null)

async function setActiveView(view) {
    activeView.value = view
    if (view === 'graphs' && !allSpecimens.value) {
        try {
            const response = await axios.get('/specimens/all')
            allSpecimens.value = response.data
        } catch (error) {
            console.error('Error fetching all specimens:', error)

        }
    }
}
</script>
