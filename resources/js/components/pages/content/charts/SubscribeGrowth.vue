<template>
    <apex-chart height="400" type="bar" :options="chartOptions" :series="displayData"></apex-chart>
</template>

<script>
import ApexChart from 'vue-apexcharts';
export default {
    name: "SubscribeGrowth",
    props: ['displayData', 'keys'],
    components: {
        ApexChart,
    },
    data(){
        return{
            chartOptions: {
                chart: {
                    toolbar: {
                        show: false,
                    },
                },
                fill: {
                    colors: '#5590F5',
                },
                grid: {
                    show: false,
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    type: 'date',
                    categories: this.keys
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                         columnWidth: "20%"
                    },
                },
            },
        }
    },
}
</script>

<style scoped>

</style>
