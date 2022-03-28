<template>
    <div class="sms-automation-on">
        <div class="middle_div">
            <div class="row">
                <div class="col-7 pr-4">
                    <div class="form_inner_div border-radius-reg box_shadow_reg mt-0">
                        <div class="head_div">
                            <p class="mb-0 display-4 font-400">Discount</p>
                        </div>
                        <div class="body_div d-flex align-items-end">
                            <div class="select_div">
                                <label class="font-400">Type</label>
                                <select class="select_in">
                                    <option>Automatic</option>
                                    <option>Automatic</option>
                                    <option>Automatic</option>
                                    <option>Automatic</option>
                                </select>
                            </div>
                            <div class="select_div ml-4">
                                <label class="font-400">Discount</label>
                                <p class="discount_p mb-0">25 <span class="float-right">%</span></p>
                            </div>
                            <p class="mb-2 ml-4 font-400">We will create a new discount code in your store for you</p>
                        </div>
                    </div>
                    <div class="form_inner_div border-radius-reg box_shadow_reg">
                        <div class="head_div d-flex justify-content-between align-items-center">
                            <p class="mb-0 display-4 font-400">Preview</p>
                            <select class="select_in">
                                <option>Tags</option>
                                <option>Tags</option>
                                <option>Tags</option>
                                <option>Tags</option>
                            </select>
                        </div>
                        <div class="body_div pb-4">
                            <div class="select_div">
                                <label class="font-400">Subject</label>
                                <input class="select_in w-100" placeholder="Are you still interested in these items?">
                            </div>
                            <div class="select_div mt-4 mb-0">
                                <label class="font-400">Email Preview</label>
                                <input class="select_in w-100" placeholder="Get them for {discountValue}% OFF">
                            </div>
                        </div>
                    </div>
                    <div class="form_inner_div border-radius-reg box_shadow_reg">
                        <div class="head_div d-flex justify-content-between align-items-center">
                            <p class="mb-0 display-4 font-400">Email Content</p>
                            <select class="select_in">
                                <option>Tags</option>
                                <option>Tags</option>
                                <option>Tags</option>
                                <option>Tags</option>
                            </select>
                        </div>
                        <div class="body_div pb-4">
                            <div class="select_div">
                                <label class="font-400">Header</label>
                                <input class="select_in w-100" placeholder="We've got a special discount for you...">
                            </div>
                            <div class="select_div mt-4">
                                <label class="font-400">Body Text</label>
                                <textarea class="select_in w-100" rows="4">Hi {first name},Remember the items you loved? They're still available! Get them now with an incredible discount:</textarea>
                            </div>
                            <div class="select_div mt-4 mb-0">
                                <label class="font-400">Discount Banner Text</label>
                                <input class="select_in w-100" placeholder="{discountValue}% OFF all items!">
                            </div>
                            <div class="select_div mt-4">
                                <label class="font-400">After Cart Body Text</label>
                                <textarea class="select_in w-100" rows="4"></textarea>
                            </div>
                            <div class="select_div mt-4">
                                <label class="font-400">Footer</label>
                                <textarea class="select_in w-100" rows="4" placeholder="You received this email because you’ve started a checkout at {siteName} and didn’t finish. If you have any questions, please feel free to reach out to us at:{storeEmail} {unsubscribeLink}Unsubscribe{/unsubscribeLink}"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form_inner_div border-radius-reg box_shadow_reg">
                        <div class="head_div">
                            <p class="mb-0 display-4 font-400">Campaigns Scheduled</p>
                        </div>
                        <div class="body_div d-flex align-items-end">
                            <div class="select_div">
                                <label class="font-400">Scheduled</label>
                                <select class="select_in">
                                    <option>Letter</option>
                                </select>
                            </div>
                            <div class="select_div ml-4 w-100">
                                <label class="font-400">Date & Time</label>
                                <input class="select_in w-100" type="date" placeholder="2021-03-27  10:09 PM">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-5 pl-4">
                    <div class="row_head_time">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-12 ml-auto">
                                <div class="right_btn_div d-flex ml-auto justify-content-end align-items-center">
                                    <button class="btn_white text-gray box_shadow_reg btn-width">Send Test</button>
                                    <button class="btn_all ml-3 btn-width">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="right_main_div mt-4">
                        <p class="font-400 text-gray display-2 text-center mb-0">Preview</p>
                        <div class="form_inner_div border-radius-reg box_shadow_reg mt-1">
                            <div class="head_div d-flex justify-content-between align-items-center">
                                <p class="mb-0 font-base text-white font-400">Oops... Left Something Behind?</p>
                                <div class="btn-right_div">
                                    <button class="btn_right">
                                        <svg width="17" height="3" viewBox="0 0 17 3" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.27293 1.7091C0.470156 1.92924 0.766992 2.04379 1.06018 2.03881C6.00877 2.03947 10.9574 2.03815 15.9056 2.03947C16.1072 2.04147 16.3134 2.00494 16.4883 1.90068C16.7858 1.73301 16.9754 1.40928 17 1.07094V0.96801C16.9761 0.669846 16.831 0.380978 16.587 0.203342C16.403 0.0625606 16.1696 -0.00118947 15.9398 -0.000193357C10.9799 -0.000193357 6.02006 -0.000194073 1.06018 0.000137806C0.790898 -0.00318241 0.517637 0.0897864 0.323066 0.279376C0.129492 0.455685 0.0232422 0.710021 0 0.96801V1.07094C0.0229102 1.30502 0.108574 1.53744 0.27293 1.7091Z" fill="white"/>
                                        </svg>
                                    </button>
                                    <button class="btn_right">
                                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="0.5" y="0.5" width="14" height="14" rx="1.5" stroke="white"/>
                                        </svg>
                                    </button>
                                    <button class="btn_right">
                                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.631055 0H0.864551C0.993164 0.028125 1.12412 0.0667969 1.22988 0.148242C1.32305 0.219727 1.40127 0.308203 1.48477 0.390234C3.36943 2.2752 5.2541 4.15986 7.13906 6.04453C7.26006 6.16377 7.37666 6.28799 7.50234 6.40254C7.95146 5.96865 8.38682 5.52012 8.83066 5.08066C10.4423 3.46934 12.0533 1.85771 13.6649 0.24668C13.783 0.120703 13.9421 0.0462891 14.1059 0H14.3121C14.4832 0.0307617 14.6528 0.0975586 14.7756 0.224414C14.8904 0.332812 14.9525 0.481934 15 0.629297V0.864844C14.9698 1.03564 14.8986 1.20234 14.7712 1.32305C13.0474 3.04688 11.3235 4.7707 9.59971 6.49453C9.26777 6.83086 8.92734 7.15869 8.60039 7.5C9.52793 8.44189 10.4689 9.37061 11.4018 10.3072C12.5247 11.4305 13.6479 12.5537 14.7712 13.6767C14.8986 13.7974 14.9704 13.9641 15 14.1352V14.3704C14.9449 14.5518 14.8535 14.7308 14.6956 14.8433C14.5846 14.9291 14.448 14.973 14.3121 15H14.1064C13.943 14.9531 13.7836 14.8787 13.6655 14.753C12.1225 13.21 10.5794 11.6669 9.03633 10.1238C8.52393 9.61611 8.02061 9.09902 7.50264 8.59688C5.43223 10.6544 3.37471 12.7248 1.30781 14.7853C1.18887 14.9051 1.02715 14.9672 0.864551 15H0.631934C0.445898 14.9373 0.261328 14.8438 0.147949 14.6769C0.055957 14.5559 0.0181641 14.4044 0 14.2562V14.1917C0.0205078 14.0016 0.093457 13.8146 0.232031 13.6796C2.20928 11.7018 4.1874 9.72451 6.16465 7.74668C6.24727 7.6667 6.32783 7.58496 6.40518 7.49971C6.00996 7.08838 5.59922 6.6917 5.19844 6.28594C3.54287 4.63066 1.8876 2.97539 0.232324 1.32012C0.093457 1.18535 0.0205078 0.998438 0 0.808301V0.744141C0.0172852 0.602344 0.0518555 0.45791 0.135937 0.339844C0.248437 0.164063 0.438867 0.0647461 0.631055 0Z" fill="white"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="body_div">
                                <div class="email_row d-flex align-items-center justify-content-between">
                                    <p class="font-base mb-0">test123@gmail.com</p>
                                    <button class="btn_return">
                                        <svg width="18" height="15" viewBox="0 0 18 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M7.88534 0.273106C7.77538 0.0736976 7.53024 -0.0386166 7.30763 0.0121602C7.11663 0.0484773 6.98918 0.207197 6.85602 0.334307C4.89893 2.23322 2.94285 4.13281 0.985754 6.03173C0.892271 6.11748 0.826363 6.22777 0.782984 6.34648V6.58523C0.832079 6.69182 0.894289 6.79405 0.980038 6.87576C3.00001 8.83454 5.01797 10.7953 7.03794 12.7544C7.23298 12.9552 7.58707 12.9616 7.78681 12.7655C7.91157 12.6532 7.96403 12.4824 7.9573 12.3183C7.95428 11.3223 7.96033 10.3262 7.95428 9.3302C8.63388 9.32784 9.31751 9.29993 9.99375 9.38501C12.1513 9.61805 14.2069 10.6581 15.6784 12.252C16.1841 12.7931 16.6159 13.4004 16.9787 14.045C17.1052 14.2754 17.4055 14.3968 17.6526 14.2956C17.8231 14.2357 17.9388 14.083 18 13.9189V13.4461C17.963 12.9 17.9196 12.3526 17.8029 11.8166C17.3614 9.537 16.0967 7.43464 14.3111 5.95371C12.5454 4.47547 10.2661 3.6163 7.961 3.59243C7.95091 2.66768 7.95932 1.74294 7.95696 0.818199C7.95293 0.635941 7.98286 0.437542 7.88534 0.273106Z" fill="black"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="content_div">
                                    <p class="font-400 mb-0 text-center">Show a little preview of what your email is about!</p>
                                    <h2 class="text-center mb-3 mt-2">We've got a special discount for you...</h2>
                                    <p class="content_txt font-400 mb-0 line_normal">Hey there!
                                        <br>
                                        <br>
                                        Thanks for stopping by Rosa Shop! We noticed that you really liked our products and we have saved them in your cart, just for you!
                                        <br>
                                        <br>
                                        Complete your purchase now to make sure you can bring them home!</p>
                                </div>
                                <div class="discount_div">
                                    <h3 class="text-center text-blue mb-0">Your Cart Will Expire Soon!</h3>
                                </div>
                                <div class="fot_text">
                                    <div class="row align-items-end">
                                        <div class="col-md-8 pr-0">
                                            <div class="content_div p-0">
                                                <p class="mb-0">The timer is ticking! Check out now before you lose out!
                                                    <a href="#">Click here</a> to visit Store.<br>
                                                    Thanks for shopping,<br>
                                                    The Rosa Shop Team</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <button class="btn_all" data-toggle="modal" data-target="#email-automations-test">Go to Checkout</button>
                                        </div>
                                    </div>
                                    <div class="row align-items-end mt-4">
                                        <div class="col-md-8 pr-0">
                                            <div class="content_div p-0">
                                                <p class="mb-0">This email is sent to you using Carti<br>
                                                    <a href="#">Unsubscribe</a>
                                                </p>
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
    </div>
</template>

<script>
import SendTestModel from "../../../Shopify/SendTestModel";
import instance from "../../../../axios";

export default {
    name: "EmailCreateEditCampaign",
    components:{
        SendTestModel
    },
    data(){
        return{
            id: '',
            action: '',
            tags: ['{domain}', '{firstName}', '{lastName}', '{siteName}', '{cartLink}Click here{/cartLink}', '{unsubscribeLink}Unsubscribe{/unsubscribeLink}', '{discountValue}', '{discountCode}', '{storeEmail}'],
            isLoading: true,
            automation: {},
            isShowTestModel: false,
        }
    },
    methods:{
        async getData(){
            let url = 'create-automation/email/' + this.id;
            let base = this;
            base.isLoading = true;
            await axios.get(url)
                .then(res => {
                    let data = res.data.data;
                    base.automation = data.automation;
                })
                .catch(err => {
                    console.log(err);
                })
                .finally(res => {
                    base.isLoading = false;
                });
        },

        async saveData(){
            let url = 'save-automation/email/' + this.id;
            let base = this;
            base.isLoading = true;
            await axios.post(url, {data: base.automation})
                .then(res => {
                    let data = res.data.data;
                    this.$toast.success(data);
                })
                .catch(err => {
                    console.log(err);
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
        // this.getData();
    }
}
</script>

<style scoped>

</style>
