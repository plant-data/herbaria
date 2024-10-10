<template>
    <div class="p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <Link :href="route('specimens.show', specimen.id)" v-for="specimen in specimens.data" :key="specimen.id" class="grid grid-cols-[150px_1fr] bg-white rounded-lg shadow-sm border border-zinc-100 overflow-hidden">
                <div class="m-2 h-44 rounded-sm overflow-clip bg-gray-200 flex items-center justify-center">
                    <img
                        :src="`/images/noimg.jpg`"
                        :alt="specimen.scientific_name"
                        class="object-cover w-full h-full"
                    />
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800">{{ specimen.scientific_name }}</h3>
                    <p class="text-sm text-gray-600">Event Date: {{ formatDate(specimen.event_date) }}</p>
                    <p class="text-sm text-gray-600">Catalog: {{ specimen.catalog_number }}</p>
                </div>
            </Link>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            <template v-for="link in specimens.links" :key="link.label">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    v-html="link.label"
                    class="px-4 py-2 mx-1 border rounded hover:bg-gray-100"
                    :class="{ 'bg-blue-500 text-white': link.active }"
                />
                <span v-else v-html="link.label" class="px-4 py-2 mx-1 text-gray-500" />
            </template>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    specimens: Object,
})

function formatDate(dateString) {
    if (!dateString) return 'N/A'
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
}
</script>
