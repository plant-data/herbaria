<template>
    <div class="fixed inset-0 bg-black bg-opacity-75 z-50 flex">
        <div class="flex h-screen bg-gray-100 w-full">
            <!-- Sidebar -->
            <div class="w-1/4 bg-gray-800 text-white p-4">
                <h2 class="text-xl font-bold mb-4">{{ specimen.scientific_name }}</h2>
                <p class="text-sm mb-4">Year: <b>{{ new Date(specimen.event_date).getFullYear() }}</b></p>
                <p class="text-sm mb-4">Barcode: <b>{{ specimen.catalog_number }}</b></p>
                <button @click="toggleAdditionalInfo" class="text-sm text-blue-300 hover:text-blue-100">
                    {{ showAdditionalInfo ? 'Hide Additional Info' : 'Show Additional Info' }}
                </button>
                <div v-if="showAdditionalInfo" class="mt-4">
                    <p class="text-xs">Locality: {{ specimen.locality }}</p>
                    <p class="text-xs">Recorded by: {{ specimen.recorded_by }}</p>
                    <p class="text-xs">Altitude: {{ specimen.minimum_altitude_in_meters }} m</p>
                </div>
                <div class="mt-4 flex flex-wrap gap-2 justify-start">
                    <div v-for="(image, index) in images" :key="index"
                         class="w-12 min-h-[58px] bg-gray-200 cursor-pointer rounded-md"
                         @click="selectImage(index)">
<!--                        <img :src="image.thumbnail" alt="Thumbnail" class="w-full h-full object-cover rounded-md"> -->
                        <img :src="`/images/noimg.jpg`" alt="Thumbnail" class="w-full h-full object-cover rounded-md">
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="flex-1 relative">
                <div id="image-container" class="faster w-full h-full"></div>
                <button @click="closeViewer" class="absolute right-3 top-8 transform -translate-y-1/2 w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center hover:bg-gray-100 transition-colors border border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <button @click="previousImage" class="absolute left-4 top-1/2 transform -translate-y-1/2 w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center hover:bg-gray-100 transition-colors border border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <button @click="nextImage" class="absolute right-4 top-1/2 transform -translate-y-1/2 w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center hover:bg-gray-100 transition-colors border border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue'
import ImageViewer from 'iv-viewer'
import 'iv-viewer/dist/iv-viewer.css'

const props = defineProps({
    specimen: {
        type: Object,
        required: true
    },
    onClose: {
        type: Function,
        required: true
    }
})

const showAdditionalInfo = ref(false)
const currentImageIndex = ref(0)

const images = computed(() => [
    { url: `/images/noimg.jpg`, thumbnail: `/api/placeholder/120/90?text=${props.specimen.catalog_number}` },
    // Add more images here if available
])

let viewer = null

const toggleAdditionalInfo = () => {
    showAdditionalInfo.value = !showAdditionalInfo.value
}

const selectImage = (index) => {
    currentImageIndex.value = index
    if (viewer) {
        viewer.load(images.value[index].url)
    }
}

const closeViewer = () => {
    props.onClose()
}

const previousImage = () => {
    if (currentImageIndex.value > 0) {
        currentImageIndex.value--
    } else {
        currentImageIndex.value = images.value.length - 1
    }
}

const nextImage = () => {
    if (currentImageIndex.value < images.value.length - 1) {
        currentImageIndex.value++
    } else {
        currentImageIndex.value = 0
    }
}

const initViewer = () => {
    const container = document.querySelector('#image-container')
    if (container) {
        viewer = new ImageViewer(container, {
            zoomValue: 101,
            maxZoom: 800,
            snapView: true,
            refreshOnResize: true,
            zoomOnMouseWheel: true,
            hasZoomButtons: true,
            zoomStep: 50,
        })
        viewer.load(images.value[currentImageIndex.value].url)
    }
}

onMounted(() => {
    initViewer()
})

watch(currentImageIndex, () => {
    if (viewer) {
        viewer.load(images.value[currentImageIndex.value].url)
    }
})
</script>

<style scoped>
@import 'iv-viewer/dist/iv-viewer.css';
.faster {
    will-change: transform;
}
</style>
