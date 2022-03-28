<template>
    <div class="sms-automation-on" v-if="!isLoading">
        <div class="row_head_time">
            <div class="row justify-content-between align-items-center">
                <div class="col-7">
                    <div class="time_slap">
                        <ul class="time_ul">
                            <li v-for="(auto, index) in allAutomations" :class="{'active_li' : (auto.id == automation.id)}" @click="changeAutomation(auto.id)">{{index+1 | filter_ordinal }} Reminder</li>
                        </ul>
                    </div>
                </div>
                <div class="col-5">
                    <div class="right_btn_div d-flex ml-auto justify-content-end align-items-center">
                        <input type="checkbox" class="checkbox" checked v-model="automation.is_active" @change="saveData()"/>
                        <button class="btn_all ml-3"  @click="saveData()">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="middle_div pt-4">
            <div class="row">
                <div class="col-7 pr-4">
                    <div class="form_inner_div border-radius-reg box_shadow_reg mt-0">
                        <div class="head_div">
                            <p class="mb-0 display-4 font-400">Discount</p>
                        </div>
                        <div class="body_div">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="select_div">
                                        <label class="font-400">Type</label>
                                        <select class="select_in w-100" v-model="automation.discount_type">
                                            <option value="0">Automatic</option>
                                            <option value="1">Pre-made</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3" v-if="automation.discount_type == 1">
                                    <div class="select_div" >
                                        <label class="font-400">Discount code</label>
                                        <p class="discount_p mb-0">
                                            <span class="discount-number d-inline-block">
                                            <input type="text" class="text-blue w-100" v-model="automation.discount_code">
                                            </span>
                                        </p>
                                        <error-vue v-if="errors['data.discount_code']" msg="required"></error-vue>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="select_div">
                                        <label class="font-400">Discount</label>
                                        <p class="discount_p mb-0">
                                        <span class="discount-number d-inline-block">
                                            <input type="number" class="text-blue" min="0" max="100" v-model="automation.discount_value">%
                                        </span>
                                        </p>
                                        <error-vue v-if="errors['data.discount_value']" msg="required"></error-vue>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <p class="mb-2 font-400" v-if="automation.discount_type == 0">We will create a new discount code in your store for you</p>
                                    <p class="mb-2 font-400" style="display: grid;" v-else>Manage your discount codes here
                                        <a href="#" target="_blank">Shopify Admin panel</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form_inner_div border-radius-reg box_shadow_reg">
                        <div class="head_div d-flex justify-content-between align-items-center">
                            <p class="mb-0 display-4 font-400">Content</p>
                            <select class="select_in" @change="addTagInContent($event)">
                                <option disabled>Tags</option>
                                <option v-for="(tag, tindex) in tags" :value="tag">{{tag}}</option>
                            </select>
                        </div>
                        <div class="body_div pb-4">
                            <div class="select_div">
                                <label class="font-400">Headline</label>
                                <input class="select_in w-100" placeholder="You order was not processed" v-model="automation.body_text.headline">
                                <error-vue v-if="errors['data.body_text.headline']" msg="required"></error-vue>
                            </div>
                            <div class="select_div mt-4 mb-0">
                                <label class="font-400">Body Text</label>
                                <input class="select_in w-100" placeholder="Oops... Seems like you didn't complete your purchase. CLICK HERE to complete it now >>" v-model="automation.body_text.body_text">
                                <error-vue v-if="errors['data.body_text.body_text']" msg="required"></error-vue>
                            </div>
                        </div>
                    </div>
                    <div class="form_inner_div border-radius-reg box_shadow_reg mt-0">
                        <div class="head_div">
                            <p class="mb-0 display-4 font-400">Sending Timing</p>
                        </div>
                        <div class="body_div d-flex align-items-end">
                            <div class="select_div">
                                <label class="font-400">Send</label>
                                <span class="discount_p d-inline-block mr-2">
                                <input type="number" class="text-blue mr-2" min="0" max="100" v-model="automation.sending_time">%</span>
                                <error-vue v-if="errors['data.sending_time']" msg="required"></error-vue>
                            </div>
                            <div class="select_div ml-4">
                                <select class="select_in" v-model="automation.sending_type">
                                    <option value="0">Minutes</option>
                                    <option value="1">Hours</option>
                                </select>
                            </div>
                            <p class="mb-2 ml-4 font-400" v-if="allAutomations[0].id == id">After abandonment</p>
                            <p class="mb-2 ml-4 font-400" v-else>After previous reminder</p>
                        </div>
                    </div>
                </div>
                <div class="col-5 pl-4">
                    <div class="right_main_div ">
                        <p class="font-400 text-gray display-2 text-center mb-0">Preview</p>
                        <div class="form_inner_div border-radius-reg box_shadow_reg mt-1">
                            <div class="body_div">
                                <div class="row align-items-center">
                                    <div class="col-md-3 pr-0">
                                        <div class="off-bg-div text-center">
                                            <img src="/images/big-place-img.png" alt/>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="content_div">
                                            <p class="font-400 mb-2 handle-content">{{automation.body_text.headline}}</p>
                                            <p class="mb-2 font-s-12 handle-content"> {{automation.body_text.body_text}}</p>
                                            <!-- <p class="text-gray mb-0">/rosa-shop.net</p> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SendTestModel from "../../../Shopify/SendTestModel";
import instance from "../../../../axios";
import helper from "../../../../helper";
import ErrorVue from "../../../Shopify/Error.vue";

export default {
    name: "PushCreateEditAutomation",
    components:{
        SendTestModel, ErrorVue
    },
    data(){
        return{
            id: '',
            action: '',
            tags: ['{domain}', '{firstName}', '{lastName}', '{siteName}', '{discountValue}', '{discountCode}', '{storeEmail}'],
            isLoading: true,
            automation: {},
            allAutomations: {},
            isShowTestModel: false,
            errors: []
        }
    },
    methods:{
        changeAutomation(id){
            let p = localStorage.getItem('rc_currpage');
            let pArr = p.split("/");
            pArr[2] = id;
            let newpath = pArr.join('/');
            localStorage.setItem('rc_currpage', newpath);

            this.id = id;
            this.getData();
        },
        async getData(){
            let url = 'create-automation/push/automation/' + this.id;
            let base = this;
            base.isLoading = true;
            await axios.get(url)
                .then(res => {
                    let data = res.data.data;
                    base.automation = data.automation;
                    base.allAutomations = data.allAutomations;
                })
                .catch(err => {
                    console.log(err);
                })
                .finally(res => {
                    base.isLoading = false;
                });
        },

        async saveData(){
            let url = 'save-automation/push/automation/' + this.id;
            console.log(url);
            let base = this;
            base.isLoading = true;
            await axios.post(url, {data: base.automation})
                .then(res => {
                    let data = res.data.data;
                    this.$toast.success(data);
                })
                .catch(err => {
                    console.log(err);
                    base.errors = err.response.data.errors;
                })
                .finally(res => {
                    base.isLoading = false;
                });
        },

        addTagInContent(event){
            let v = event.target.value;
            document.execCommand('insertText', false /*no UI*/, v);
        },

        closeModel(){
            this.isShowTestModel = false;
        }
    },
    mounted(){
        let p = localStorage.getItem('rc_currpage');
        let pArr = p.split("/");
        this.action = pArr[1];
        if( pArr[1] == 'editreminder' ){
            this.id = pArr[2];
        }
        this.getData();
    },
    filters:{
        filter_ordinal(n){
            return helper.ordinal(n);
        }
    }
}
</script>

<style scoped>

</style>
