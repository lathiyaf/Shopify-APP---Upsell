<template>
    <date-range-picker
        ref="picker",
        v-model="dateRange",
        @update="updateValues"
    >
    </date-range-picker>
</template>

<script>
import DateRangePicker from 'vue2-daterange-picker';
import 'vue2-daterange-picker/dist/vue2-daterange-picker.css';

export default {
    name: "DateFilter",
    components:{
        DateRangePicker
    },
    data(){
        let startDate = new Date();
        let endDate = new Date();
        return{
            dateRange: {startDate, endDate}
        }
    },
    methods:{
        updateValues($e){;
            let d= {
                startDate: $e.startDate.toLocaleDateString(),
                endDate: $e.endDate.toLocaleDateString(),
            }
            this.$emit('update', d);
        }
    }
}
</script>

<style>
.calendars {
    flex-wrap: nowrap !important;
}
.vue-daterange-picker {
    margin-bottom: 20px;
}
</style>
