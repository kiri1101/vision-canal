<template>
    <div>
        <apexchart :height="height" type="area" :options="options" :series="series"></apexchart>
    </div>
</template>

<script setup>
const props = defineProps({
    height: {
        type: String,
        default: '220'
    },
    xAxis: Object,
    series: Array
})

const options = {
    chart: {
        type: 'area',
        toolbar: {
            show: false
        }
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'smooth'
    },
    xaxis: props.xAxis,
    tooltip: {
        x: {
            format: 'dd MMM'
        },
    },
}
</script>
