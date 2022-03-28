<template>
    <div>
        <div class="row_head_time">
            <div class="row justify-content-between align-items-center">
                <div class="col-7">
                    <div class="normal_content">
                        <p class="mb-0 font-400">Get your customers to complete their purchases with our Abandoned Cart Push Notifications! Only sent to those on<br> your subscriber list, started checkout but did not complete checkout process.</p>
                    </div>
                </div>
                <div class="col-5">
                    <div class="right_btn_div d-flex ml-auto justify-content-end">
                        <div class="blue_btn_div d-flex align-items-center ml-4">
                            <p class="text-white mb-0">Help Video
                                <span class="svg_icon ml-4">
                                                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12.6151 0.0598388C15.8258 -0.238162 19.1238 0.582773 21.7958 2.39084C24.4348 4.14589 26.4234 6.83506 27.3676 9.85519C28.3748 13.053 28.1584 16.6132 26.7816 19.6692C25.6526 22.2179 23.7443 24.4128 21.3717 25.8799C18.6453 27.5877 15.3157 28.3183 12.1265 27.8713C8.48463 27.3928 5.08341 25.3999 2.85414 22.4858C0.983036 20.0073 -0.110112 16.8912 0.00880179 13.7736C-0.0341791 11.4842 0.663544 9.23483 1.73377 7.23049C3.89714 3.24902 8.1107 0.499677 12.6151 0.0598388ZM12.4876 2.26619C10.4761 2.53697 8.53764 3.32495 6.9058 4.53272C3.19225 7.23765 1.34264 12.2578 2.47304 16.7207C3.11059 19.3869 4.69658 21.8196 6.90007 23.4543C8.92877 24.9744 11.4589 25.8527 14.0005 25.8083C16.7828 25.8541 19.5408 24.7896 21.6525 22.993C23.6841 21.2738 25.1053 18.8511 25.6082 16.2378C26.0294 14.0802 25.8217 11.808 25.028 9.76063C24.177 7.53708 22.6325 5.58718 20.664 4.24618C18.2986 2.6301 15.3344 1.88653 12.4876 2.26619Z" fill="white"/>
                                                    <path d="M10.459 8.04982C10.81 7.89365 11.1481 8.14867 11.4504 8.30054C14.2986 9.92522 17.1296 11.58 19.9349 13.2777C20.1655 13.4468 20.5237 13.5815 20.518 13.9196C20.6125 14.3551 20.1698 14.5743 19.869 14.7677C17.0895 16.4469 14.2814 18.0816 11.4533 19.679C11.1453 19.8294 10.8043 20.106 10.4475 19.9355C10.0192 19.7206 10.1023 19.1804 10.0765 18.7836C10.0507 15.5858 10.0507 12.388 10.0765 9.19024C10.0965 8.79052 10.0321 8.26472 10.459 8.04982Z" fill="white"/>
                                                </svg>
                                            </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="revanu_div sms-automation" v-if="!isLoading">
            <div class="row">
                <div class="col-4">
                    <div class="box_div box_shadow_reg border-radius-reg">
                        <div class="head_div green_bg_light">
                            <p class="title_p mb-0 d-flex justify-content-between font-400 display-3 align-items-center">
                                <span>Revenue Recovered</span>
                                <span class="icon_span">
                                                <svg width="37" height="26" viewBox="0 0 37 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M3.3846 0.201876C3.81097 0.060911 4.26171 0.00174031 4.71072 0C13.5828 0 22.4567 0 31.3288 0C33.3441 0.0139225 35.2254 1.46883 35.8397 3.37273C36.1025 4.33512 36.0329 5.34625 36.0207 6.33126C36.0172 11.0945 36.0381 15.856 36.0103 20.6175C36.0172 21.9628 35.3855 23.2993 34.31 24.1121C33.5181 24.7333 32.507 25.0344 31.5081 25.024C22.5159 25.0257 13.5254 25.0257 4.5332 25.024C2.94082 25.071 1.34669 24.2026 0.591392 22.7894C0.131949 22.0272 0.0136082 21.1239 0.0153485 20.2486C0.0170888 16.0701 0.0275306 11.8916 0.00316616 7.71307C0.121508 6.20596 -0.233516 4.62227 0.333826 3.17607C0.873323 1.82211 1.97668 0.649137 3.3846 0.201876ZM3.60736 1.77164C2.21162 2.38075 1.38149 3.96444 1.56945 5.46285C12.5352 5.45937 23.5009 5.45937 34.4666 5.46285C34.4527 4.91291 34.5223 4.34382 34.3552 3.80955C33.9027 2.49213 32.5662 1.50189 31.1618 1.53322C22.6882 1.53496 14.2128 1.53496 5.73924 1.53322C5.02571 1.5454 4.2739 1.45316 3.60736 1.77164ZM1.57293 9.35245C1.58163 13.169 1.55553 16.9855 1.58511 20.802C1.61644 22.2569 2.93734 23.4855 4.3818 23.4855C13.4245 23.4995 22.4689 23.489 31.5116 23.4908C33.0065 23.5552 34.4248 22.3108 34.4527 20.7968C34.4805 16.982 34.4562 13.1672 34.4649 9.35245C23.5009 9.35767 12.5369 9.35767 1.57293 9.35245Z" fill="black"/>
                                                </svg>
                                            </span>
                            </p>
                        </div>
                        <div class="prod_numer_div text-center">
                            <div class="price_div display-1 font-400 ">{{shop.currency_symbol}}{{analytics.revenue.toFixed(2)}}</div>
                        </div>
                        <div class="link_div">
                            <p class="text-center mb-0">Quantity</p>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="box_div box_shadow_reg border-radius-reg">
                        <div class="head_div orange_bg_light">
                            <p class="title_p mb-0 d-flex justify-content-between font-400 display-3 align-items-center">
                                <span>Abandoned Carts</span>
                                <span class="icon_span">
                                                <svg width="32" height="27" viewBox="0 0 32 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M15.624 1.03395C16.726 0.338691 18.0363 -0.0624174 19.3452 0.00795252C21.749 0.0966186 24.0248 1.6349 25.024 3.82059C25.6222 5.00844 25.7179 6.38487 25.5265 7.68531C24.9691 10.8703 21.7673 13.3473 18.5387 13.0165C18.5753 12.6112 18.6133 12.2073 18.6499 11.802C19.3016 11.7189 19.9729 11.8019 20.609 11.6091C21.8954 11.2545 23.048 10.3988 23.7067 9.23204C24.9227 7.10687 24.523 4.14711 22.6568 2.51875C20.9355 0.997353 18.208 0.820021 16.2827 2.06557C16.0294 1.74327 15.8267 1.3872 15.624 1.03395Z" fill="black"/>
                                                    <path d="M11.4045 1.74171C13.0188 1.49682 14.7316 1.83179 16.0686 2.79022C17.5632 3.76133 18.5512 5.38969 18.9073 7.11797C19.0002 8.10738 19.0312 9.139 18.6906 10.089C18.0446 12.1368 16.3416 13.82 14.277 14.4181C12.7879 14.8784 11.1258 14.7433 9.73248 14.0438C7.86486 13.1684 6.48279 11.3599 6.07746 9.34448C6.02117 8.61404 6.01554 7.87515 6.0648 7.14471C6.51657 4.47769 8.73745 2.21882 11.4045 1.74171ZM11.396 2.99007C9.72825 3.30674 8.26456 4.50584 7.62701 6.07791C7.23434 6.98005 7.16538 7.99056 7.26953 8.95885C7.46657 10.6238 8.53337 12.1607 10.0421 12.901C11.1708 13.4921 12.5022 13.5301 13.7351 13.3288C15.1678 12.9812 16.4866 12.0439 17.1523 10.7082C18.3359 8.455 17.7349 5.30102 15.5802 3.85C14.3825 2.98867 12.8316 2.72548 11.396 2.99007Z" fill="black"/>
                                                    <path d="M23.2881 13.1909C23.4584 12.8348 23.4978 12.3493 23.7919 12.0903C25.9706 12.9334 27.8199 14.5111 29.1598 16.411C30.5798 18.4335 31.4074 20.8514 31.6157 23.3087C31.4313 23.6 31.2723 24.0166 30.8824 24.0603C29.2498 24.4205 27.6046 24.8034 25.9326 24.8836C25.8833 24.5036 25.8256 24.1236 25.7679 23.745C27.2921 23.5114 28.8093 23.2496 30.3223 22.954C30.0619 20.3489 28.9951 17.7917 27.2021 15.8678C26.1071 14.7081 24.7588 13.8045 23.2881 13.1909Z" fill="black"/>
                                                    <path d="M1.84455 19.0318C3.23366 16.6829 5.33772 14.7041 7.90481 13.7175C8.05118 14.0862 8.20318 14.455 8.33547 14.8293C7.47555 15.2586 6.6086 15.6808 5.84016 16.2649C3.21254 18.2 1.60248 21.3681 1.321 24.5896C5.46297 25.5706 9.7471 25.8549 13.9946 25.7592C17.2485 25.6607 20.4968 25.2962 23.6803 24.6079C23.3707 21.696 22.0858 18.8249 19.8663 16.8701C18.9247 15.9918 17.7819 15.381 16.6265 14.8378C16.7827 14.4578 16.9248 14.0722 17.0684 13.6865C18.5996 14.3635 20.045 15.2698 21.2132 16.4788C23.3454 18.6926 24.6247 21.6594 24.94 24.705C24.9836 25.0893 24.6937 25.382 24.4938 25.6747C18.4927 27.0878 12.2452 27.2933 6.12727 26.6571C4.24417 26.4207 2.35966 26.1293 0.520192 25.6621C0.216194 25.4003 -0.0695081 25.0006 0.0360468 24.5756C0.269675 22.6348 0.853745 20.7221 1.84455 19.0318Z" fill="black"/>
                                                </svg>
                                            </span>
                            </p>
                        </div>
                        <div class="prod_numer_div">
                            <div class="price_div display-1 font-400 text-center">{{analytics.abandoned_orders}}</div>
                        </div>
                        <div class="link_div">
                            <p class="text-center mb-0">Quantity</p>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="box_div box_shadow_reg border-radius-reg">
                        <div class="head_div blue_bg_light">
                            <p class="title_p mb-0 d-flex justify-content-between font-400 display-3 align-items-center">
                                <span>Orders Recovered</span>
                                <span class="icon_span">
                                                <svg width="26" height="25" viewBox="0 0 26 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M5.40932 0H19.8487C20.2984 0.147832 20.7459 0.39082 20.9849 0.817891C22.2797 3.0552 23.5728 5.29307 24.8676 7.53037C25.0478 7.84699 25.2885 8.16475 25.2562 8.55047C25.2602 14.517 25.2562 20.4841 25.2579 26.4512C25.2602 26.7701 25.2624 27.0918 25.2052 27.4073C25.0925 27.9844 24.7022 28.4965 24.1812 28.7672C23.9308 28.9099 23.6419 28.9547 23.361 29H1.8976C1.33119 28.9496 0.778379 28.6873 0.431738 28.2286C0.119082 27.8423 -0.00212887 27.3393 0.00183597 26.8499C-0.000996062 20.8262 0.00183594 14.8025 0.000703131 8.77873C-0.00779296 8.44738 0.0601758 8.1115 0.233496 7.82604C1.58041 5.49018 2.92732 3.15375 4.27424 0.817891C4.511 0.389121 4.96016 0.148965 5.40932 0ZM5.72367 1.70148C4.73813 3.416 3.74861 5.12824 2.76137 6.84162C2.6951 6.95207 2.62826 7.06309 2.58691 7.18543C5.6557 7.19223 8.72393 7.18713 11.7927 7.18826C11.8001 7.05799 11.8046 6.92715 11.8035 6.79688C11.7961 5.09822 11.8131 3.399 11.795 1.70035C9.77121 1.69922 7.74744 1.69695 5.72367 1.70148ZM13.5208 1.70035C13.5095 3.52928 13.5067 5.35934 13.5225 7.18826C16.5834 7.18543 19.6442 7.19449 22.7051 7.18373C21.6476 5.3565 20.5907 3.52984 19.5355 1.70148C17.5304 1.69695 15.5259 1.69922 13.5208 1.70035ZM1.69992 8.89201C1.70049 14.8976 1.69992 20.9027 1.70049 26.9083C1.66707 27.1076 1.81943 27.3195 2.03297 27.2985C9.07793 27.3042 16.1235 27.2991 23.1684 27.3008C23.3672 27.3319 23.5791 27.179 23.5564 26.966C23.5615 20.9417 23.5564 14.9169 23.5587 8.89258C16.2724 8.88635 8.98617 8.88691 1.69992 8.89201Z" fill="black"/>
                                                    <path d="M15.6477 15.0834C16.0017 14.9702 16.4163 15.1322 16.6072 15.4499C16.8213 15.7813 16.7533 16.2553 16.4628 16.5199C15.0179 17.9642 13.5735 19.4091 12.1281 20.8529C11.9389 21.0562 11.6568 21.1695 11.3793 21.131C11.1476 21.1111 10.955 20.965 10.7993 20.8041C10.14 20.1415 9.47275 19.4867 8.81912 18.8195C8.48834 18.4898 8.51439 17.8957 8.87066 17.5949C9.17426 17.3157 9.68515 17.3145 9.98875 17.5949C10.4985 18.0775 10.9726 18.6014 11.4897 19.0732C12.7607 17.8367 13.9983 16.5646 15.2609 15.3191C15.3707 15.2143 15.4987 15.1231 15.6477 15.0834Z" fill="black"/>
                                                </svg>
                                            </span>
                            </p>
                        </div>
                        <div class="prod_numer_div text-center">
                            <div class="price_div display-1 font-400">{{analytics.orders}}</div>
                        </div>
                        <div class="link_div">
                            <p class="text-center mb-0">Quantity</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table_div sms-automation-table">
            <div class="table_heading">
                <div class="row align-items-center">
                    <div class="col-6">
                        <p class="title_p display-3 font-400 mb-0">Draft</p>
                    </div>
                    <div class="col-6 text-right">
                        <button class="btn_all" @click="changeAction('push/addreminder')">+ Add Reminder</button>
                    </div>
                </div>
            </div>

            <div class="table_div_inner box_shadow_reg border-radius-reg">
                <table class="table mb-0">
                    <thead class="thead-blue">
                    <tr>
                        <th>Reminders</th>
                        <th >Sent</th>
                        <th style="width:15%">Clicked</th>
                        <th style="width:15%">CTR (%)</th>
                        <th style="width:15%">Revenue</th>
                        <th style="width:15%">Active</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(automation, index) in automations.data">
                        <td>
                            <p class="mb-0">{{(index + 1) | filter_ordinal}}<br>
                                <span class="text-gray">{{automation.sending_time}} {{ automation.sending_type | filter_sendingtype }}
                                    <span v-if="index == 0"> after abandoned checkout </span>
                                    <span v-else> after previous message </span>
                                </span>
                            </p>
                        </td>
                        <td>{{automation.total_sent}}</td>
                        <td>{{automation.total_clicked}}</td>
                        <td>{{(automation.ctr == 0) ? '-' : automation.ctr + '%'}}</td>
                        <td>{{automation.total_revenue}}</td>
                        <td>
                        <input type="checkbox" class="checkbox" v-model="automation.is_active" @change="updateStatus(automation.id)"/>
                        </td>
                        <td>
                            <button class="btn_table btn_blue font-400" @click="changeAction('push/editreminder/' + automation.id)">Edit</button>
                            <button class=" ml-1 btn_table btn_red font-400" v-if="automation.is_default == 0"  @click="deleteAutomation(automation.id)">Remove</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <pagination :data="automations" @update="changePageData"></pagination>
    </div>
</template>

<script>
import instance from "../../../../axios";
import helper from "../../../../helper";
import Pagination from "../../../Shopify/Pagination";

export default {
    name: "Automation",
    components:{
        Pagination
    },
        data(){
            return{
                automations: [],
                isLoading: true,
                analytics: [],
                shop: [],
            }
        },
        methods:{
            changePageData(url){
                this.getData(url);
            },
            changeAction(action){
                this.$emit('update', action);
            },
            async getData(url = 'get-automations/push'){
                let base = this;
                base.isLoading = true;
                await axios.get(url)
                    .then(res => {
                        let data = res.data.data;
                        base.automations = data.automations;
                        base.analytics = data.analytics.push;
                        base.shop = data.shop;
                    })
                    .catch(err => {
                        console.log(err);
                    })
                    .finally(res => {
                        base.isLoading = false;
                    });
            },
            async updateStatus(id){
                let base = this;
                await axios.get('update-status/' + id)
                    .then(res => {
                        let data = res.data.data;
                        this.$toast.success(data);
                    })
                    .catch(err => {
                        console.log(err);
                    })
                    .finally(res => {
                    });
            },
            async deleteAutomation(id){
                let base = this;
                await axios.get('delete-automation/' + id)
                    .then(res => {
                        let data = res.data.data;
                        this.$toast.success(data);
                        this.getData();
                    })
                    .catch(err => {
                        console.log(err);
                    })
                    .finally(res => {
                    });
            }
        },
        mounted(){
            this.getData();
        },
        filters:{
            filter_sendingtype(type){
                return ( type == 0 ) ? 'minutes' : 'hours';
            },
            filter_ordinal(n){
                return helper.ordinal(n);
            }
        }
}
</script>

<style scoped>

</style>
