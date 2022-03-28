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
                        <button class="btn_white ml-3 text-gray box_shadow_reg btn-width" @click="isShowTestModel = true;">Send Test</button>
                        <button class="btn_all ml-3"  @click="saveData()">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="middle_div">
            <div class="row">
                <div class="col-7">
                    <div class="form_inner_div border-radius-reg box_shadow_reg">
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
                                            <input type="text" class="text-blue w-100" contenteditable="false" v-model="automation.discount_code">
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
                        <div class="head_div d-flex justify-content-between">
                            <p class="mb-0 display-4 font-400">Content</p>
                            <select class="select_in" @change="addTagInContent($event)">
                                <option disabled>Tags</option>
                                <option v-for="(tag, tindex) in tags" :value="tag">{{tag}}</option>
                            </select>
                        </div>
                        <div class="body_div pb-5">
                            <div class="select_div">
                                <label class="font-400">Body Text</label>
                                <textarea class="select_in w-100" v-model="automation.body_text.body_text">{{ automation.body_text.body_text }}</textarea>
                                 <error-vue v-if="errors['data.body_text.body_text']" msg="required"></error-vue>
                            </div>
                            <div class="select_div mt-4 ">
                                <label class="font-400">Unsubscribe text <span class="text-gray">(Max 11 characters)</span></label>
                                <input class="select_in w-100" type="text" maxlength="11" placeholder="Unsubscribe" v-model="automation.body_text.unsubscribe_text">
                                 <error-vue v-if="errors['data.body_text.unsubscribe_text']" msg="required"></error-vue>
                            </div>
                        </div>
                    </div>
                    <div class="form_inner_div border-radius-reg box_shadow_reg">
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
                <div class="col-5">
                    <div class="modal_mobile_div mx-auto">
                        <img class="bg_img"  src="/images/Ellipse.png" alt>
                        <div class="content_div">
                            <div class="head_div">
                                <div class="row align-items-center">
                                    <div class="col-4">
                                        <div class="left_div d-flex text-left" data-dismiss="modal">
                                            <svg width="13" height="21" viewBox="0 0 13 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2.54335 8.71788L0.549805 10.7114L10.5175 20.6791L12.5111 18.6856L2.54335 8.71788Z" fill="#007AFF"/>
                                                <path d="M0.550401 10.711L2.54395 12.7046L12.5117 2.73687L10.5181 0.743323L0.550401 10.711Z" fill="#007AFF"/>
                                            </svg>
                                            <p class="mb-0 ml-1">Cancel</p>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="left_div text-center">
                                            <p class="mb-1" style="font-size:12px;"><b>Royal Cart</b></p>
                                            <p class="mb-0" style="font-size:10px;">Active Now</p>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="right_div text-right">
                                            <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M18.9018 20.0593L13.1406 14.3065C11.7862 15.3286 10.1348 15.88 8.438 15.8768C6.68761 15.8806 4.98574 15.3017 3.60072 14.2314C2.21569 13.1611 1.22631 11.6602 0.78857 9.96544C0.350825 8.27067 0.489617 6.4784 1.18306 4.87122C1.87651 3.26405 3.08516 1.93339 4.61845 1.08905C6.15174 0.244716 7.92245 -0.0652669 9.65138 0.207979C11.3803 0.481224 12.9691 1.32215 14.1673 2.59818C15.3655 3.8742 16.1049 5.51273 16.2689 7.25543C16.4329 8.99812 16.0123 10.7458 15.0732 12.223C14.9788 12.3739 14.8688 12.5303 14.7307 12.7178L20.482 18.479L18.9018 20.0579V20.0593ZM8.438 1.28133C7.10901 1.28312 5.81017 1.67754 4.7046 2.41506C3.59904 3.15259 2.73603 4.20033 2.22397 5.42672C1.71191 6.6531 1.57363 8.00344 1.82648 9.30816C2.07934 10.6129 2.71206 11.8138 3.64519 12.7601L3.6607 12.7756L3.66916 12.7841C4.36192 13.4748 5.19684 14.0063 6.11584 14.3417C7.03485 14.677 8.01587 14.8082 8.99069 14.7259C9.96551 14.6437 10.9107 14.3502 11.7606 13.8656C12.6104 13.3811 13.3446 12.7173 13.9119 11.9203C14.4792 11.1233 14.866 10.2123 15.0456 9.25063C15.2251 8.28897 15.1931 7.29974 14.9515 6.35174C14.71 5.40374 14.2649 4.51973 13.6472 3.76119C13.0294 3.00265 12.2538 2.38779 11.3743 1.95937C10.4602 1.512 9.45571 1.28005 8.438 1.28133Z" fill="#007AFF"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="content_div_inner">
                                <p class="handle-content">{{automation.body_text.body_text}}
                                    <a href="#">royalcart.in/SAMPLEURL</a></p>
                                <p class="mb-0">{{automation.body_text.unsubscribe_text}} <a href="#">royalcart.in/AAAA</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <send-test-model :show-test-model.sync="isShowTestModel" :data="automation" @update="closeModel"></send-test-model>
        <div class="modal-backdrop" v-if="isShowTestModel"></div>
    </div>
</template>

<script>
import SendTestModel from "../../../Shopify/SendTestModel";
import helper from "../../../../helper";
import ErrorVue from "../../../Shopify/Error.vue";

export default {
    name: "SmsCreateEditAutomation",
    components:{
        SendTestModel, ErrorVue
    },
    data(){
        return{
            id: '',
            action: '',
            tags: ['{domain}', '{firstName}', '{lastName}', '{siteName}', '{cartLink}', '{discountValue}', '{discountCode}', '{storeEmail}'],
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
            let url = 'create-automation/sms/automation/' + this.id;
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
            let url = 'save-automation/sms/automation/' + this.id;
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
