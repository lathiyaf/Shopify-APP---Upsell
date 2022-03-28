<template>
    <div class="main_div">
        <div class="content_div_main settings w-100">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>

import Sidebar from "./Sidebar";
export default {
    components: {
        Sidebar
    },
    data() {
        return{
            isPlanSelected: false,

        }
    },
    methods: {
        async getPlan() {
            let base = this;

            await axios.get('get-plan')
                .then(res => {
                    let data = res.data.data;
                    base.isPlanSelected = (!(typeof data.plan == 'undefined' || data.plan == null));
                })
                .catch(err => {
                    console.log(err);
                })
                .finally(res => {
                    base.is_load = false;
                });
        },
    },
    created(){
        // this.getPlan();
    }
};
</script>
