<template>
    <div class="container mx-auto p-4">
        <div class="w-full flex items-center justify-center">
            <h1 class="text-4xl font-semibold pb-8 pt-4">{{ specimen.scientific_name }}</h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-[400px_1fr] gap-4">
            <div class="space-y-4">
                <div class="bg-white p-1 border h-[500px] shadow-sm border-slate-100 rounded">
                    <div class="w-full h-full overflow-hidden rounded-sm">

                        <img
                            :src="`/images/noimg.jpg`"
                            :alt="specimen.scientific_name"
                            class="h-full object-cover cursor-pointer"
                            @click="openImageViewer"
                        >
                    </div>
                </div>
                <div class="bg-white p-1 border shadow-sm border-slate-100 rounded flex justify-center">
                    <div ref="mapElement" class="rounded w-full" style="height: 340px;"></div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-slate-50 p-4 rounded">
                    <h2 class="font-semibold text-xl pb-4 mb-2 text-center border-b border-dotted border-gray-400">LABEL DATA</h2>
                    <div class="grid grid-cols-[auto,1fr] gap-x-4 gap-y-1">
                        <template v-for="(value, key) in basicData" :key="key">
                            <div class="contents">
                                <div class="text-right font-semibold">{{ key }}:</div>
                                <div class="pb-1">{{ value }}</div>
                                <div class="col-span-2 border-b border-dotted border-gray-400 mb-1"
                                     :class="{ 'border-b-0': key === Object.keys(basicData).slice(-1)[0] }"></div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="bg-slate-50 p-4 rounded">
                    <h2 class="font-semibold text-xl pb-4 mb-2 border-b border-dotted border-gray-400 text-center">GEOGRAPHIC DATA</h2>
                    <div class="grid grid-cols-[auto,1fr] gap-x-4 gap-y-1">
                        <template v-for="(value, key) in geographicData" :key="key">
                            <div class="contents">
                                <div class="text-right font-semibold">{{ key }}:</div>
                                <div class="pb-1">{{ value }}</div>
                                <div class="col-span-2 border-b border-dotted border-gray-400 mb-1"
                                     :class="{ 'border-b-0': key === Object.keys(geographicData).slice(-1)[0] }"></div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="bg-slate-50 p-4 rounded">
                    <h2 class="font-bold mb-2 text-center">ADDITIONAL INFORMATION</h2>
                    <div class="grid grid-cols-[auto,1fr] gap-x-4 gap-y-1">
                        <template v-for="(value, key) in additionalInfo" :key="key">
                            <div class="contents">
                                <div class="text-right font-semibold">{{ key }}:</div>
                                <div class="pb-1">{{ value }}</div>
                                <div class="col-span-2 border-b border-dotted border-gray-400 mb-1"
                                     :class="{ 'border-b-0': key === Object.keys(additionalInfo).slice(-1)[0] }"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <SpecimenImageViewer
        v-if="showImageViewer"
        :specimen="specimen"
        :onClose="closeImageViewer"
        class="z-[999999999]"
    />
</template>

<script setup>
import { onMounted, ref, computed } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import SpecimenImageViewer from './ImageViewer.vue';

const props = defineProps({
    specimen: {
        type: Object,
        required: true
    }
});

const mapElement = ref(null);
const showImageViewer = ref(false);

const openImageViewer = () => {
    showImageViewer.value = true;
};

const closeImageViewer = () => {
    showImageViewer.value = false;
};

const basicData = computed(() => ({
    'Catalog Number': props.specimen.catalog_number,
    'Scientific Name': props.specimen.scientific_name,
    'Event Date': props.specimen.event_date,
    'Recorded By': props.specimen.recorded_by,
    'Other Catalog Numbers': props.specimen.other_catalog_numbers,
}));

const geographicData = computed(() => ({
    'Country': props.specimen.country,
    'Locality': props.specimen.locality,
    'Minimum Altitude': props.specimen.minimum_altitude_in_meters ? `${props.specimen.minimum_altitude_in_meters} m` : 'Not specified',
    'Latitude': props.specimen.latitude,
    'Longitude': props.specimen.longitude,
}));

const additionalInfo = computed(() => ({
    'Sheet Type': props.specimen.sheet_type,
    'Specimen Image': props.specimen.specimen_image ? 'Available' : 'Not available',
    'Cover Image': props.specimen.cover_image ? 'Available' : 'Not available',
    'Cover Barcode': props.specimen.cover_barcode,
}));

onMounted(() => {
    if (props.specimen.latitude && props.specimen.longitude) {
        const map = L.map(mapElement.value).setView([props.specimen.latitude, props.specimen.longitude], 1);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        L.marker([props.specimen.latitude, props.specimen.longitude]).addTo(map)
            .bindPopup(`${props.specimen.scientific_name} specimen location`);
    }
});
</script>

<style scoped>
@import 'leaflet/dist/leaflet.css';
</style>
