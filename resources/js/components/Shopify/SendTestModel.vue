<template>
    <div class="modal" id="sendTestMailModel" tabindex="-1" role="dialog" aria-labelledby="sendTestMailModel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="font-400 mb-0 display-3 pl-4">Send Test</p>
                    <button type="button btn_close" class="close" data-dismiss="modal" aria-label="Close" @click="closeModal">
                    <span aria-hidden="true">
                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.631055 0H0.864551C0.993164 0.028125 1.12412 0.0667969 1.22988 0.148242C1.32305 0.219727 1.40127 0.308203 1.48477 0.390234C3.36943 2.2752 5.2541 4.15986 7.13906 6.04453C7.26006 6.16377 7.37666 6.28799 7.50234 6.40254C7.95146 5.96865 8.38682 5.52012 8.83066 5.08066C10.4423 3.46934 12.0533 1.85771 13.6649 0.24668C13.783 0.120703 13.9421 0.0462891 14.1059 0H14.3121C14.4832 0.0307617 14.6528 0.0975586 14.7756 0.224414C14.8904 0.332812 14.9525 0.481934 15 0.629297V0.864844C14.9698 1.03564 14.8986 1.20234 14.7712 1.32305C13.0474 3.04688 11.3235 4.7707 9.59971 6.49453C9.26777 6.83086 8.92734 7.15869 8.60039 7.5C9.52793 8.44189 10.4689 9.37061 11.4018 10.3072C12.5247 11.4305 13.6479 12.5537 14.7712 13.6767C14.8986 13.7974 14.9704 13.9641 15 14.1352V14.3704C14.9449 14.5518 14.8535 14.7308 14.6956 14.8433C14.5846 14.9291 14.448 14.973 14.3121 15H14.1064C13.943 14.9531 13.7836 14.8787 13.6655 14.753C12.1225 13.21 10.5794 11.6669 9.03633 10.1238C8.52393 9.61611 8.02061 9.09902 7.50264 8.59688C5.43223 10.6544 3.37471 12.7248 1.30781 14.7853C1.18887 14.9051 1.02715 14.9672 0.864551 15H0.631934C0.445898 14.9373 0.261328 14.8438 0.147949 14.6769C0.055957 14.5559 0.0181641 14.4044 0 14.2562V14.1917C0.0205078 14.0016 0.093457 13.8146 0.232031 13.6796C2.20928 11.7018 4.1874 9.72451 6.16465 7.74668C6.24727 7.6667 6.32783 7.58496 6.40518 7.49971C6.00996 7.08838 5.59922 6.6917 5.19844 6.28594C3.54287 4.63066 1.8876 2.97539 0.232324 1.32012C0.093457 1.18535 0.0205078 0.998438 0 0.808301V0.744141C0.0172852 0.602344 0.0518555 0.45791 0.135937 0.339844C0.248437 0.164063 0.438867 0.0647461 0.631055 0Z" fill="white"></path>
                        </svg>
                    </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input_group_main px-4">
                        <label class="d-block font-400">{{ (data.reminder_type == 'email') ? 'Email Address' : 'Phone number' }}</label>
                        <div class="input_group d-flex">
                            <input class="input-model" v-if="data.reminder_type == 'email'" placeholder="Email" v-model="receiver">
                            <input class="input-model" v-else placeholder="Mobile number" v-model="receiver">
                            <button class="btn_all" @click="sendTestMail()">send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import instance from "../../axios";

var sendTestMailModel = "";
export default {
    name: "SendTestModel",
    props: ["ShowTestModel", "data"],
    data(){
        return{
            receiver: '',
        }
    },
    watch: {
        ShowTestModel(newVal, oldVal) {
            if (newVal === true) {
                $(sendTestMailModel).slideDown();
            } else {
                $(sendTestMailModel).slideUp();
            }
        },
    },
    methods:{
        closeModal(){
            this.$emit('update');
        },
        async sendTestMail(){
            if(this.receiver == ''){
                let type = (this.data.reminder_type == 'email') ? 'Email' : 'Phone number';
                this.$toast.error(type + ' is required');
                return;
            }
            let url = 'test-automation-mail';
            let base = this;
            base.isLoading = true;
            await axios.post(url, {data: base.data, receiver: base.receiver})
                .then(res => {
                    let data = res.data.data;
                    this.$toast.success(data);
                    this.$emit('update');
                })
                .catch(err => {
                    console.log(err);
                })
                .finally(res => {
                    base.isLoading = false;
                });
        },
    },
    mounted() {
        sendTestMailModel = document.getElementById("sendTestMailModel");
    },
}
</script>

<style scoped>

</style>
