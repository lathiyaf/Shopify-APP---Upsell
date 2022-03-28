<template>
    <apex-chart height="400" type="area" :options="chartOptions" :series="displayData"></apex-chart>
</template>

<script>
import ApexChart from 'vue-apexcharts';
export default {
    name: "SalesOvertime",
    props: ['displayData', 'keys', 'formatype'],
    components: {
        ApexChart,
    },
    data(){
        let formatt = this.formattype;
        return{
            chartOptions: {
                chart: {
                    toolbar: {
                        show: false,
                    },
                },
                stroke: {
                    curve: 'straight',
                },
                colors: ['#5590F5', '#44BFFF'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        opacityFrom: 1,
                        opacityTo: 1,
                    }
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
                        borderRadius: 4
                    }
                },
            },
        }
    },
}
</script>

<style scoped>

</style>
