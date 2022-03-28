<template>
    <div v-if="!isLoading">
        <sidebar @update="updateView" :charge="data.charge"></sidebar>
        <div class="content_div_main w-100">
            <div class="header_div">
                <div class="container-mian ml-auto py-0 d-flex align-items-center">
                    <p class="mb-0">{{data.credit_msg}}</p>
                </div>
            </div>

            <div class="container-mian ml-auto">
                <dashboard v-if="typeof currPage == 'undefined' || currPage == 'dashboard' || currPage == ''"/>
                <sms-index v-if="currPage.indexOf('sms') >= 0" :path="currPage" @update="changeTemplate"/>
                <email-index v-if="currPage.indexOf('email') >= 0" :path="currPage" @update="changeTemplate"/>
                <push-index v-if="currPage.indexOf('push') >= 0" :path="currPage" @update="changeTemplate"/>
                <analytics-index v-if="currPage == 'analytics'" />
                <setting-index v-if="currPage == 'settings'" />
                <faq-index v-if="currPage == 'faq'" />
                <pricing-index v-if="currPage == 'pricing'" />
            </div>
        </div>
<!--        <sms-charge-index ref="charge" @update="closeM"/>-->
    </div>
</template>

<script>
import Sidebar from "../../layout/Sidebar";
import Dashboard from "./Dashboard";
import SmsIndex from "./sms/index";
import EmailIndex from "./email";
import PushIndex from "./push";
import AnalyticsIndex from "./analytics";
import SettingIndex from "./setting";
import FaqIndex from "./faq";
import PricingIndex from "./pricing"
import SmsChargeIndex from "../../pages/content/SmsCharge/Index";
import instance from "../../../axios";

export default {
    name: "index",
    components: {
        Sidebar, Dashboard, SmsIndex, EmailIndex, PushIndex, AnalyticsIndex, SettingIndex, FaqIndex, PricingIndex
    },
    data(){
      return {
          isLoading: true,
          currPage: 'dashboard',
          data: [],
      }
    },
    methods:{
        updateView(path){
            this.currPage = path;
        },
        openModel(type){
            this.$refs.charge.openM(type);
        },
        closeM(type){
            this.$refs.charge.closeModel(type);
        },
        async getData(url = 'get-sidebar'){
            let base = this;
            base.isLoading = true;
            await axios.get(url)
                .then(res => {
                    base.data = res.data.data;
                    this.setOnesignalUserId();
                })
                .catch(err => {
                    console.log(err);
                })
                .finally(res => {
                    base.isLoading = false;
                });
        },
        setOnesignalUserId(){
            let base = this;
            OneSignal.getUserId().then(function(userId) {
                if(userId != null){
                    OneSignal.sendTag('user_id', base.data.user_id);
                }

            });
        },
        changeTemplate(action){
            localStorage.setItem('rc_currpage', action);
            this.currPage = action;
        }
    },
    mounted() {
        this.currPage = localStorage.getItem('rc_currpage');
        this.getData();
    }
}
</script>

<style scoped>

</style>
