<template>
    <div id="map" style="height: 500px;"></div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import axios from 'axios'

const props = defineProps({
    mapData: Array,
})

let map
const markers = ref({})

onMounted(() => {
    map = L.map('map',{
        renderer: L.canvas()
    }).setView([0, 0], 2,)

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map)

    props.mapData.forEach(specimen => {
        const marker = L.circleMarker([specimen.latitude, specimen.longitude],
            {color: 'black',
            fillColor: '#f03',
            fillOpacity: 1,
                weight: 1,
            radius: 3})
            .addTo(map)

        marker.bindPopup(() => {
            const popupContent = L.DomUtil.create('div')
            popupContent.innerHTML = `<b>${specimen.scientific_name}</b><br>Catalog: ${specimen.catalog_number}<br>Loading locality...`

            fetchSpecimenDetail(specimen.id, popupContent)

            return popupContent
        })

        markers.value[specimen.id] = marker
    })
})

onUnmounted(() => {
    if (map) {
        map.remove()
    }
})

const fetchSpecimenDetail = async (specimenId, popupContent) => {
    try {
        const response = await axios.get(`/specimens/${specimenId}`)
        const specimen = response.data
        popupContent.innerHTML = `
      <b>${specimen.scientific_name}</b><br>
      Catalog: ${specimen.catalog_number}<br>
      Locality: ${specimen.locality || 'Not specified'}
    `
    } catch (error) {
        console.error('Error fetching specimen detail:', error)
        popupContent.innerHTML += '<br>Error loading locality'
    }
}
</script>

<style scoped>
@import 'leaflet/dist/leaflet.css';
</style>
