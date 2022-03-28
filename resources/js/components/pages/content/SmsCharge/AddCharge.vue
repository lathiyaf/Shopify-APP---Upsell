<template>
    <div class="modal" id="AddFunds" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="font-400 mb-0 display-4 pl-4">Add Funds</p>
                    <button type="button btn_close" class="close" data-dismiss="modal" aria-label="Close" @click="closeModal('fund')">
                    <span aria-hidden="true">
                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.631055 0H0.864551C0.993164 0.028125 1.12412 0.0667969 1.22988 0.148242C1.32305 0.219727 1.40127 0.308203 1.48477 0.390234C3.36943 2.2752 5.2541 4.15986 7.13906 6.04453C7.26006 6.16377 7.37666 6.28799 7.50234 6.40254C7.95146 5.96865 8.38682 5.52012 8.83066 5.08066C10.4423 3.46934 12.0533 1.85771 13.6649 0.24668C13.783 0.120703 13.9421 0.0462891 14.1059 0H14.3121C14.4832 0.0307617 14.6528 0.0975586 14.7756 0.224414C14.8904 0.332812 14.9525 0.481934 15 0.629297V0.864844C14.9698 1.03564 14.8986 1.20234 14.7712 1.32305C13.0474 3.04688 11.3235 4.7707 9.59971 6.49453C9.26777 6.83086 8.92734 7.15869 8.60039 7.5C9.52793 8.44189 10.4689 9.37061 11.4018 10.3072C12.5247 11.4305 13.6479 12.5537 14.7712 13.6767C14.8986 13.7974 14.9704 13.9641 15 14.1352V14.3704C14.9449 14.5518 14.8535 14.7308 14.6956 14.8433C14.5846 14.9291 14.448 14.973 14.3121 15H14.1064C13.943 14.9531 13.7836 14.8787 13.6655 14.753C12.1225 13.21 10.5794 11.6669 9.03633 10.1238C8.52393 9.61611 8.02061 9.09902 7.50264 8.59688C5.43223 10.6544 3.37471 12.7248 1.30781 14.7853C1.18887 14.9051 1.02715 14.9672 0.864551 15H0.631934C0.445898 14.9373 0.261328 14.8438 0.147949 14.6769C0.055957 14.5559 0.0181641 14.4044 0 14.2562V14.1917C0.0205078 14.0016 0.093457 13.8146 0.232031 13.6796C2.20928 11.7018 4.1874 9.72451 6.16465 7.74668C6.24727 7.6667 6.32783 7.58496 6.40518 7.49971C6.00996 7.08838 5.59922 6.6917 5.19844 6.28594C3.54287 4.63066 1.8876 2.97539 0.232324 1.32012C0.093457 1.18535 0.0205078 0.998438 0 0.808301V0.744141C0.0172852 0.602344 0.0518555 0.45791 0.135937 0.339844C0.248437 0.164063 0.438867 0.0647461 0.631055 0Z" fill="white"></path>
                        </svg>
                    </span>
                    </button>
                </div>
                <div class="modal-body pt-0 px-5">
                    <div class="title_div">
                        <p class="display-2 font-400 border-bottom text-center py-3 mb-0">Enable Auto Reload (Recommended)</p>
                    </div>
                    <div class="input_group_main py-3">
                        <label class="d-block font-400">Add ${{parseFloat(form.fund).toFixed()}} to my account</label>
                        <div class="input_group d-flex">
                           <input type="number" class="input-model w-100" name="addfund" v-model="form.fund">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input_group_main py-3">
                                <label class="d-block font-400">When my balance falls below: ${{form.recur_min}}</label>
                                <div class="input_group d-flex">
                                    <div class="input_group d-flex">
                                        <input type="number" class="input-model w-100" name="addfund" v-model="form.recur_min">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input_group_main py-3">
                                <label class="d-block font-400">And never exceed ${{form.recur_max}} per billing cycle</label>
                                <div class="input_group d-flex">
                                    <div class="input_group d-flex">
                                        <input type="number" class="input-model w-100" name="addfund" v-model="form.recur_max">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="notice_div">
                        <div class="icon_div">
                            <svg width="11" height="25" viewBox="0 0 11 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.52472 0H8.0524C8.64289 0.0345625 9.23289 0.203968 9.71725 0.551054C10.3116 0.965317 10.7882 1.5816 10.9372 2.30011C11.0881 3.0196 10.9746 3.81016 10.5531 4.42157C10.1169 5.06317 9.44124 5.54705 8.68183 5.72035C7.77931 5.92723 6.77651 5.77292 6.02295 5.21846C5.43977 4.80468 4.98072 4.18937 4.84198 3.47913C4.7417 2.92029 4.77578 2.32348 5.01479 1.80261C5.3244 1.11866 5.90807 0.573933 6.58861 0.266764C6.88507 0.129488 7.20927 0.0749667 7.52472 0Z" fill="#5590F5"/>
                                <path d="M4.08617 8.5084C4.6932 8.41104 5.31436 8.4125 5.92285 8.49429C6.67155 8.597 7.42024 8.87253 7.97616 9.39924C8.44349 9.82616 8.76526 10.4059 8.87722 11.029C8.9921 11.5504 8.9113 12.0839 8.88306 12.6092C8.83244 13.3598 8.63577 14.0895 8.42645 14.809C8.07644 16.0489 7.72643 17.2888 7.37691 18.5291C7.24451 18.9823 7.13595 19.4419 7.04979 19.9058C6.94123 20.4977 6.83024 21.1106 6.95778 21.7084C7.01717 22.0014 7.16856 22.2979 7.44068 22.4439C7.82623 22.6547 8.27846 22.6839 8.70928 22.6868C9.383 22.6834 10.0319 22.4707 10.6604 22.2477C10.5606 22.7214 10.4437 23.1916 10.3181 23.6595C9.47257 23.99 8.62701 24.322 7.76732 24.615C7.16954 24.8219 6.53573 24.8838 5.90874 24.9242H5.37618C4.52039 24.8726 3.63637 24.6949 2.93295 24.1769C2.22904 23.6721 1.74224 22.8674 1.67069 21.9995C1.60302 21.022 1.78119 20.0484 2.01534 19.1035C2.32397 18.017 2.62919 16.929 2.93733 15.842C3.10235 15.2423 3.28928 14.6484 3.41877 14.0399C3.53317 13.5526 3.66412 13.0658 3.68797 12.5634C3.71328 12.1087 3.71572 11.6205 3.48595 11.2121C3.31362 10.9034 2.95583 10.7725 2.62578 10.717C2.12681 10.6396 1.61081 10.6503 1.12012 10.7715C0.737497 10.8533 0.379701 11.0174 0 11.1079C0.100767 10.6357 0.225387 10.1684 0.344165 9.70057C0.775954 9.52824 1.21066 9.36224 1.64586 9.19722C2.44713 8.92899 3.24255 8.6155 4.08617 8.5084Z" fill="#5590F5"/>
                            </svg>
                        </div>
                        <p class="display-3 font-600 mb-2 text-white">Info Title</p>
                        <p class="mb-0 text-white">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting.</p>
                    </div>
                    <div class="make_payment_div text-center border-top">
                        <p class="font-400 mb-2">Make one time payment</p>
                        <a href="#" @click="closeModal('openonetimemodal')">Add Funds</a>
                    </div>
                    <div class="btn_div text-right py-3 border-top">
                        <button class="btn_gray" @click="closeModal('fund')">Cancel</button>
                        <button class="btn_all ml-2" @click="enableAutoReload()">Enable with Shopify</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
var addFundModel = "";
export default {
    name: "AddCharge",
    props: ["ShowFundModel"],
    data(){
      return{
          form: {
              id: '',
              fund: 10,
              fund_type: 'recur',
              recur_min: 10,
              recur_max: 500,
          }
      }
    },
    methods:{
        closeModal(type){
            this.$emit('update', type);
            // $(addFundModel).slideUp();
        },
        async getData(url = 'get-recur-fund'){
            let base = this;
            base.isLoading = false;
            await axios.get(url)
                .then(res => {
                    let data = res.data.data;
                   if(data.recur != null){
                       base.form = data.recur;
                   }
                })
                .catch(err => {
                    console.log(err);
                })
                .finally(res => {
                    base.isLoading = false;
                });
        },
        async enableAutoReload(){
            let url = 'enable-recur';
            let base = this;
            base.isLoading = true;
            await axios.post(url, {data: base.form})
                .then(res => {
                    this.$toast.success(res.data.data);
                    this.closeModal('fund');
                })
                .catch(err => {
                    console.log(err);
                })
                .finally(res => {
                    base.isLoading = false;
                });
        }
    },
    watch: {
        ShowFundModel(newVal, oldVal) {
            if (newVal === true) {
                $(addFundModel).slideDown();
                this.getData();
            } else {
                $(addFundModel).slideUp();
            }
        },
    },
    mounted() {
        addFundModel = document.getElementById("AddFunds");
    },
}
</script>

<style scoped>

</style>
