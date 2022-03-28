<template>
    <div>
        <add-charge :show-fund-model.sync="isShowAddFundModel" @update="closeModel" ></add-charge>
        <one-time-payment :show-onetime-fund-model.sync="isShowOneTimeFundModel" @update="closeModel"></one-time-payment>
        <div class="modal-backdrop" v-if="isShowAddFundModel || isShowOneTimeFundModel"></div>
    </div>
</template>

<script>

import AddCharge from "./AddCharge";
import OneTimePayment from "./OneTimePayment";

export default {
    name: "SmsChargeIndex",
    components:{
        AddCharge, OneTimePayment
    },
    data(){
        return {
            isShowAddFundModel: false,
            isShowOneTimeFundModel: false,
        }
    },
    methods:{
        openM(type){
            if(type == 'fund'){
                this.isShowAddFundModel = true;
            }else if(type == 'onetime'){
                this.isShowOneTimeFundModel = true;
            }
        },
        cModel(type){
            console.log('cValeeeeeeee');
            this.$emit('update', type);
        },
        closeModel(type){
            if(type == 'fund'){
                this.isShowAddFundModel = false;
            }else if(type == 'onetime'){
                this.isShowOneTimeFundModel = false;
            }else if(type == 'openonetimemodal'){
                this.isShowAddFundModel = false;
                this.isShowOneTimeFundModel = true;
            }else if(type == 'backcharge'){
                this.isShowAddFundModel = true;
                this.isShowOneTimeFundModel = false;
            }
            this.$forceUpdate();
        },
    },
}
</script>

<style scoped>

</style>
