<template>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div id="specimensByYear" class="h-[400px] bg-white rounded-lg shadow-sm border border-zinc-100"></div>
            <div id="specimensByCountry" class="h-[400px] bg-white rounded-lg shadow-sm border border-zinc-100"></div>
            <div id="altitudeDistribution" class="h-[400px] bg-white rounded-lg shadow-sm border border-zinc-100"></div>
            <div id="topCollectors" class="h-[400px] bg-white rounded-lg shadow-sm border border-zinc-100"></div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, onUnmounted, watch, } from 'vue'
import * as echarts from 'echarts'

const props = defineProps({
    allSpecimens: Object,
})




let charts = {}

onMounted(() => {
    initCharts()
})

onUnmounted(() => {
    Object.values(charts).forEach(chart => chart.dispose())
})

watch(() => props.allSpecimens, () => {
    updateCharts()
}, { deep: true })

function initCharts() {
    charts.specimensByYear = echarts.init(document.getElementById('specimensByYear'))
    charts.specimensByCountry = echarts.init(document.getElementById('specimensByCountry'))
    charts.altitudeDistribution = echarts.init(document.getElementById('altitudeDistribution'))
    charts.topCollectors = echarts.init(document.getElementById('topCollectors'))

    updateCharts()

    window.addEventListener('resize', handleResize)
}

function handleResize() {
    Object.values(charts).forEach(chart => chart.resize())
}

function updateCharts() {
    updateSpecimensByYearChart()
    updateSpecimensByCountryChart()
    updateAltitudeDistributionChart()
    updateTopCollectorsChart()
}

function updateSpecimensByYearChart() {
    const yearCounts = {}

    props.allSpecimens.forEach(specimen => {
        if (specimen.event_date) {
            const year = new Date(specimen.event_date).getFullYear()
            if (!isNaN(year)) {
                yearCounts[year] = (yearCounts[year] || 0) + 1
            }
        }
    })
    console.log(yearCounts)

    const data = Object.entries(yearCounts).map(([year, count]) => [parseInt(year), count])
    data.sort((a, b) => a[0] - b[0])

    const option = {
        title: { text: 'Specimens by Year' },
        tooltip: { trigger: 'axis' },
        xAxis: {type: 'value',
        name: 'Year',
        min: 1800,
        max: 2025,
        minInterval: 1},
        yAxis: { type: 'value', name: 'Number of Specimens' },
        dataZoom: [
            {
                type: 'slider',
                show: true,
                xAxisIndex: [0],
                start: 0,
                end: 100
            },
        ],
        series: [{
            data: data,
            type: 'line',
            smooth: true
        }]
    }

    charts.specimensByYear.setOption(option)
}

function updateSpecimensByCountryChart() {
    const countryCounts = {}
    props.allSpecimens.forEach(specimen => {
        if (specimen.country && specimen.country.trim() !== '') {
            countryCounts[specimen.country] = (countryCounts[specimen.country] || 0) + 1
        }
    })

    const data = Object.entries(countryCounts)
        .sort((a, b) => b[1] - a[1])

    const option = {
        title: { text: 'Specimens per Country' },
        tooltip: { trigger: 'axis' },
        itemStyle: {
            borderRadius: [5, 5, 0, 0],
        },
        xAxis: {
            type: 'category',
            data: data.map(item => item[0]),
            axisLabel: {
                interval: 0,
                rotate: 90,
                fontSize: 10
            }
        },
        yAxis: { type: 'value', name: 'Number of Specimens' },
        dataZoom: [
            {
                type: 'slider',
                show: true,
                xAxisIndex: [0],
                start: 0,
                end: 10,
            },
        ],
        series: [{
            data: data.map(item => item[1]),
            type: 'bar'
        }]
    }

    charts.specimensByCountry.setOption(option)
}

function updateAltitudeDistributionChart() {
    const altitudes = props.allSpecimens
        .map(specimen => specimen.minimum_altitude_in_meters)
        .filter(altitude => altitude != null && !isNaN(altitude) && altitude !== '')
        .map(Number)

    const option = {
        title: { text: 'Altitude Distribution' },
        tooltip: { trigger: 'axis' },
        xAxis: { type: 'value', name: 'Altitude (m)' },
        yAxis: { type: 'value', name: 'Frequency' },
        series: [{
            data: altitudes,
            type: 'histogram'
        }]
    }

    charts.altitudeDistribution.setOption(option)
}

function updateTopCollectorsChart() {
    const collectorCounts = {}
    props.allSpecimens.forEach(specimen => {
        if (specimen.recorded_by && specimen.recorded_by.trim() !== '') {
            collectorCounts[specimen.recorded_by] = (collectorCounts[specimen.recorded_by] || 0) + 1
        }
    })

    const data = Object.entries(collectorCounts)
        .sort((a, b) => b[1] - a[1])
        .slice(0, 10)

    const option = {
        title: { text: 'Top Collectors' },
        tooltip: { trigger: 'item' },
        series: [{
            type: 'pie',
            /*radius: '50%',*/
            radius: ['35%', '70%'],
            padAngle: 1,
            itemStyle: {
                borderRadius: 5,

            },


            data: data.map(([name, value]) => ({ name, value })),
            emphasis: {
                itemStyle: {
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
            }
        }]
    }

    charts.topCollectors.setOption(option)
}
</script>
