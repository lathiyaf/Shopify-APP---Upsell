<template>
    <div class="sms-automation-on" v-if="!isLoading">
        <form ref="form" enctype="multipart/form-data" id="welcome-push-form">
            <div class="row_head_time">
                <div class="row justify-content-between align-items-center">
                    <div class="col-8">
                        <p class="font-400 mb-0">Welcome visitors on your site with a personalised message.
                        </p>
                        <p class="font-400 mb-0">*Make sure to enable Subscription Landing before proceeding!</p>

                    </div>
                    <div class="col-4">
                        <div class="right_btn_div d-flex ml-auto justify-content-end align-items-center">
                            <input type="checkbox" class="checkbox" checked v-model="form.active" @change="saveData()"/>
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
                                        <select class="select_in w-100" v-model="form.discount_type">
                                            <option value="0">Automatic</option>
                                            <option value="1">Pre-made</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3" v-if="form.discount_type == 1">
                                    <div class="select_div" >
                                        <label class="font-400">Discount code</label>
                                        <p class="discount_p mb-0">
                                            <span class="discount-number d-inline-block">
                                            <input type="text" class="text-blue w-100" v-model="form.discount_code">
                                            </span>
                                        </p>
                                         <error-vue v-if="errors.discount_code" msg="required"></error-vue>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="select_div">
                                        <label class="font-400">Discount</label>
                                        <p class="discount_p mb-0">
                                            <span class="discount-number d-inline-block">
                                                <input type="number" class="text-blue" min="0" max="100" v-model="form.discount_value">%
                                            </span>
                                        </p>
                                         <error-vue v-if="errors.discount_value" msg="required"></error-vue>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <p class="mb-2 font-400" v-if="form.discount_type == 0">We will create a new discount code in your store for you</p>
                                    <p class="mb-2 font-400" style="display: grid;" v-else>Manage your discount codes here
                                        <a href="#" target="_blank">Shopify Admin panel</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form_inner_div border-radius-reg box_shadow_reg">
                        <div class="head_div d-flex justify-content-between align-items-center">
                            <p class="mb-0 display-4 font-400">Logo</p>
                        </div>
                        <div class="body_div pb-4">
                            <div class="select_div">
                                <label class="font-400">Logo Image</label>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-md-5">
                                    <div class="upload_div">
                                        <vue-dropzone ref="myVueDropzone" id="dropzone" :options="dropzoneOptions"
                                                      @vdropzone-success="updateValue($event, 'add')"
                                                      @vdropzone-removed-file="updateValue($event, 'remove')"
                                                      @vdropzone-mounted="renderImage()"
                                        ></vue-dropzone>
                                    </div>
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
                                <input class="select_in w-100" placeholder="Your {discountValue}% code: {discountCode}" v-model="form.headline">
                                 <error-vue v-if="errors.headline" msg="required"></error-vue>
                            </div>
                            <div class="select_div mt-4 mb-0">
                                <label class="font-400">Body Text</label>
                                <input class="select_in w-100" placeholder="*Can be used only once. Click here and the discount will apply automatically" v-model="form.body_text">
                                <error-vue v-if="errors.body_text" msg="required"></error-vue>
                            </div>
                            <div class="select_div mt-4 mb-0">
                                <label class="font-400">URL</label>
                                <input class="select_in w-100" placeholder="https://rosa-shop.net" v-model="form.url">
                                <error-vue v-if="errors.url" msg="required"></error-vue>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-5 pl-4">
                    <div class="right_main_div ">
                        <p class="font-400 text-gray display-2 text-center mb-0">Preview</p>
                        <div class="form_inner_div border-radius-reg box_shadow_reg mt-1">
                            <div class="body_div">
                                <div class="row">
                                    <div class="col-md-3 pr-0">
                                        <div class="off-bg-div text-center">
                                            <img :src="prew_img" class="welcome-push-logo"/>
<!--                                            <svg width="80" height="78" viewBox="0 0 80 78" fill="none" xmlns="http://www.w3.org/2000/svg">-->
<!--                                                <path d="M40 0L48.5095 16.6204L65.7115 9.35822L61.5467 27.56L79.3923 33.0541L64.502 44.3204L74.641 60L55.9926 59.0592L53.6808 77.5877L40 64.88L26.3192 77.5877L24.0074 59.0592L5.35898 60L15.498 44.3204L0.607689 33.0541L18.4533 27.56L14.2885 9.35822L31.4905 16.6204L40 0Z" fill="#5590F5"/>-->
<!--                                            </svg>-->
<!--                                            <p class="font-400 text-white off-text mb-0">10%<br>Off</p>-->
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="content_div">
                                            <p class="font-400 mb-2"> {{form.headline}}</p>
                                            <p class="mb-2 font-s-12"> {{form.body_text}}</p>
                                            <p class="text-gray mb-0">{{form.url}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</template>

<script>
import vue2Dropzone from 'vue2-dropzone';
import 'vue2-dropzone/dist/vue2Dropzone.min.css';
import ErrorVue from '../../../Shopify/Error.vue';

export default {
    name: "PushWelcomePopup",
    components: {
        vueDropzone: vue2Dropzone, ErrorVue
    },
    data(){
      return{
          isLoading: true,
          form: [],
          tags: ['{siteName}', '{discountValue}', '{discountCode}', '{storeEmail}'],
          dropzoneOptions: {
              url: window.apiHost + '/api/store-images',
              thumbnailWidth: 150,
              maxFilesize: 4,
              acceptedFiles: 'image/*',
              addRemoveLinks: true,
              dictDefaultMessage: "<i class='fa fa-cloud-upload'></i> Upload images",
          },
          prew_img: '',
          place_img: '',
          errors: []
      }
    },
    methods: {
        async getData(){
            let url = 'get-welcome-push';
            let base = this;
            base.isLoading = true;
            await axios.get(url)
                .then(res => {
                    let data = res.data.data;
                    base.form = data.automations;
                    base.prew_img = base.form.logo;
                    base.place_img = data.place_img;
                    base.renderImage();
                })
                .catch(err => {
                    console.log(err);
                })
                .finally(res => {
                    base.isLoading = false;
                });
        },
        async saveData(){
            this.errors = [];
            if(!this.isValidWelcomeForm()){
                return;
            }

            let url = 'save-welcome-push/' + this.form.id;
            let base = this;
            base.isLoading = true;

            let formData = new FormData(this.$refs.form);
            formData.append('data', JSON.stringify(base.form));
            formData.append('file', base.form.logo);
            // console.log(formData);
            await axios.post(url, formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        '_method': 'post',
                    }
                })
                .then(res => {
                    let data = res.data.data;
                    this.$toast.success(data);
                    base.getData();
                })
                .catch(err => {
                    console.log(err);
                })
                .finally(res => {
                    base.isLoading = false;
                });
        },
        isValidWelcomeForm(){
            let isSuccess = true;
            if(this.form.discount_code== '' && this.form.discount_type == 1){
                isSuccess = false;
                this.errors.discount_code = 'required';
            }
            if(this.form.discount_value == ''){
                isSuccess = false;
                this.errors.discount_value = 'required';
            }
            if(this.form.headline == ''){
                isSuccess = false;
                this.errors.headline = 'required';
            }
            if(this.form.body_text == ''){
                isSuccess = false;
                this.errors.body_text = 'required';
            }
            if(this.form.url == ''){
                isSuccess = false;
                this.errors.url = 'required';
            }
            let org = this.form.discount_code;
            this.form.discount_code = 1;
            this.form.discount_code = org;

            return isSuccess;
        },
        addTagInContent(event){
            let v = event.target.value;
            console.log(v);
            document.execCommand('insertText', false /*no UI*/, v);
        },
        updateValue($e, action){
           if(action == 'add'){
               this.prew_img = $e.dataURL;
               this.form.logo = $e;
           }else if(action == 'remove'){
               this.prew_img = this.place_img;
               this.form.logo = '';
           }
        },
        renderImage() {
            let str = this.form.logo;
            if (str.length > 0) {
                this.showImage(str);
            }
        },
        showImage(str) {
            let base = this;
            let new_str = str;
            if(str.indexOf('?') > 0){
                new_str = str.substring(0, str.indexOf('?'));
            }
            var extension = str.substring(new_str.lastIndexOf('.') + 1);
            var name = str.substring(new_str.lastIndexOf('/') + 1);
            var file = {size: 150, name: name, type: "image/" + extension};
            var url = str;

            var nm = 'myVueDropzone';

            if(typeof base.$refs[`${nm}`] != 'undefined'){
                base.$refs[`${nm}`].manuallyAddFile(file, url);
            }
        },
    },
    mounted() {
        this.getData();
    }
}
</script>

<style scoped>
.welcome-push-logo{
    width: 100px;
}
</style>
