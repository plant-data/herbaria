<template>
    <div>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Catalog Number</th>
                    <th class="px-4 py-2 text-left">Scientific Name</th>
                    <th class="px-4 py-2 text-left">Country</th>
                    <th class="px-4 py-2 text-left">Locality</th>
                    <th class="px-4 py-2 text-left">Altitude (m)</th>
                    <th class="px-4 py-2 text-left">Event Date</th>
                    <th class="px-4 py-2 text-left">Recorded By</th>
                    <th class="px-4 py-2 text-left">Latitude</th>
                    <th class="px-4 py-2 text-left">Longitude</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="specimen in specimens.data" :key="specimen.id" class="border-b">
                    <td class="px-4 py-2">
                        <Link :href="route('specimens.show', specimen.id)" class="w-full h-full">
                        {{ specimen.catalog_number }}
                        </Link>
                    </td>
                    <td class="px-4 py-2">{{ specimen.scientific_name }}</td>
                    <td class="px-4 py-2">{{ specimen.country }}</td>
                    <td class="px-4 py-2">{{ specimen.locality }}</td>
                    <td class="px-4 py-2">{{ specimen.minimum_altitude_in_meters }}</td>
                    <td class="px-4 py-2">{{ specimen.event_date }}</td>
                    <td class="px-4 py-2">{{ specimen.recorded_by }}</td>
                    <td class="px-4 py-2">{{ specimen.latitude }}</td>
                    <td class="px-4 py-2">{{ specimen.longitude }}</td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            <template v-for="link in specimens.links" :key="link.label">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    v-html="link.label"
                    class="px-4 py-2 border rounded hover:bg-gray-100"
                    :class="{ 'bg-blue-500 text-white': link.active }"
                />
                <span v-else v-html="link.label" class="px-4 py-2 text-gray-500" />
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
</script>
