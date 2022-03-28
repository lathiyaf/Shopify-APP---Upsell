<template>
        <section class="welcome_screen">
            <skeleton-loader v-if="isLoading"/>
            <div class="right_circle_bg"></div>
            <div v-if="!isLoading" class="main_content">
                <div class="img_div">
                    <img src="/images/logo.png" alt="Royal Cart Logo">
                </div>
                <div class="main_content_inner">
                    <div class="head_div">
                        <h4 class="text-white mb-0">Welcome to Royal Cart - Important Message</h4>
                    </div>
                    <div class="content_div" v-if="!isPlanSelected">
                        <p>Congratulations, you are 5 minutes away from <b class="font-600">boosting your revenue by up to 15%!</b></p>
                        <p>In the next page, you will approve charges for the highest possible plan (up to $1,000/month).<br>
                            <b class="font-600">We will not charge you $1,000</b> unless we generate over $100,000/month for you.</p>
                        <p class="mb-0">The app is 100% FREE if we generate less than $100/month, and you get a 30 days free trial either way, including 2$ credit for the SMS reminders</p>

                        <pricing-table v-if="isShowPlanTable" :plans="allPlans" @update="updatePlan"/>

                        <div class="btn_div text-right mt-4">
                            <button class="btn_gray" @click="isShowPlanTable = !isShowPlanTable">Pricing Table &nbsp;&nbsp;
                                <i class="fa fa-angle-down" aria-hidden="true" v-if="!isShowPlanTable"></i>
                                <i class="fa fa-angle-up" aria-hidden="true" v-else></i>
                            </button>

<!--                            <a :href="`/billing?plan=${plan_id}&shop=${shop}`" target="_blank"><button class="btn_all ml-2" >-->
<!--                                Let’s Go&nbsp;&nbsp-->
<!--                            </button></a>-->
                            <a  @click="changePlan(plan_id)" target="_parent"><button class="btn_all ml-2" >
                                Let’s Go&nbsp;&nbsp
                            </button></a>
                        </div>
                    </div>

                    <active-cart v-else></active-cart>
                </div>
            </div>

            <div class="discount_div" v-if="isPlanSelected && !isLoading">
                <div class="row m-0">
                    <div class="col-7 pl-0">
                        <div class="inner_content">
                            <h6>Set Up Max Discount</h6>
                            <p class="mb-0">Let us know what is the biggest % discount you’re willing to offer customers in order to recover their orders and we’ll take care of the rest!</p>
                        </div>
                    </div>
                    <div class="col-5 pr-0">
                        <div class="inner_content discount_inner">
                            <h6>Max Discount</h6>

                            <span class="discount-number d-inline-block mr-2">
                                <input type="number" class="text-blue mr-2" min="0" max="100" v-model="max_discount">%</span>

                            <button class="btn_all" @click="publishApp('')" :disabled="isPublishing">
                                <i class="fa fa-spinner fa-spin" v-if="isPublishing"></i>
                                <span v-else>Publish</span>
                            </button>
                        </div>
                        <span class="inner_content discount_inner later-link" @click="publishApp('later')">I'll do it later</span>
                    </div>
                </div>
            </div>
            <div class="left_circle_bg"></div>
        </section>
</template>

<script>
import instance from "../../../axios";
import 'vue-slider-component/theme/default.css';
import PricingTable from "./PricingTable";
import SkeletonLoader from "../../Shopify/SkeletonLoader";
import ActiveCart from "./ActiveCart";
import Toasted from 'vue-toasted';
import helper from '../../../helper';
export default {
    name: "Welcome",

        components: {
            PricingTable, SkeletonLoader, ActiveCart, Toasted
        },
        data(){
            return{
                allPlans: [],
                shop: '',
                plan_id: 1,
                max_discount: 30,
                isPlanSelected: false,
                isShowPlanTable: false,
                isLoading: false,
                isPublishing: false,
            }
        },
        methods: {
            async getPlans() {
                let base = this;
                base.isLoading = true;
                await axios.get('get-plans')
                    .then(res => {
                        let data = res.data.data;
                        if(data.is_published){
                            localStorage.setItem('rc_currpage', 'dashboard');
                            this.$router.push('content');
                        }else{
                            base.allPlans = data.plans;
                            base.shop = data.shop;
                            base.isPlanSelected = (!(typeof data.curr_plan == 'undefined' || data.curr_plan == null));
                        }
                    })
                    .catch(err => {
                        console.log(err);
                    })
                    .finally(res => {
                        base.isLoading = false;
                    });
            },
            updatePlan(id){
                this.plan_id = id;
            },
            async publishApp(action){
                let base = this;
                base.isPublishing = true;
                let disc = (action == '') ? this.max_discount : 30;
                await axios.post('publish-app', {data: {'discount': disc}})
                    .then(res => {
                        localStorage.setItem('rc_currpage', 'dashboard');
                       this.$router.push('content');
                    })
                    .catch(err => {
                        console.log(err);
                    })
                    .finally(res => {
                        base.isPublishing = false;
                    });
            },
            async changePlan(plan){
                let base = this;
                base.isClickPlan = true;
                await axios({
                    url: "mbilling/" + plan,
                    method: "get",
                })
                    .then(function (res) {
                        let data = res.data.data;
                        window.top.location.href = data.confirmation_url;
                        // window.open(data.confirmationUrl, '_parent');
                    })
                    .catch(function (err) {
                        base.isClickPlan = false;
                        // helper.errorToast(err.response.data);
                    })
                    .finally((res) => {
                        // helper.stopLoading();
                    });
            },
        },
        created(){
            this.getPlans();
        },
        mounted(){
        }
}
</script>

<style scoped>

</style>
