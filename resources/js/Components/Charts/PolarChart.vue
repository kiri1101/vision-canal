<template>
    <div>
        <apexchart :height="height" type="polarArea" :options="polarArea.options" :series="polarArea.series">
        </apexchart>
    </div>
</template>

<script setup>
const props = defineProps({
    height: {
        type: String,
        default: '250'
    },
    series: Array,
    label: Array
})

const polarArea = {
    series: props.series,
    options: {
        chart: {
            type: 'polarArea',
            toolbar: {
                show: false
            }
        },
        labels: props.label,
        fill: {
            opacity: 1,
        },
        stroke: {
            width: 1,
            colors: undefined
        },
        yaxis: {
            show: false
        },
        legend: {
            position: 'bottom'
        },
        plotOptions: {
            polarArea: {
                rings: {
                    strokeWidth: 0
                },
                spokes: {
                    strokeWidth: 0
                },
            }
        },
        theme: {
            monochrome: {
                enabled: true,
                shadeTo: 'light',
                shadeIntensity: 0.6
            }
        }
    },
}
</script>
