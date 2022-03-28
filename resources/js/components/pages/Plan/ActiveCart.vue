<template>
    <div class="content_div" v-else>
        <div class="top_step_container">
            <div class="bar_step">
                <div class="progress_step">

                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="step_txt text-center mr-auto active_step">
                        <p class="mb-0">1 <br>Install App</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="step_txt text-center mx-auto active_step">
                        <p class="mb-0">2<br>Approve Plan</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="step_txt text-center ml-auto">
                        <p class="mb-0">3 <br>Activate Cart<br>
                            Recovery</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-progress-bar">
            <label>Your Progress : <b>Step 3 - Activate Cart Recovery</b></label>
            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="max-width: 60%">
                    <span class="title">60% Completed</span>
                </div>
            </div>
        </div>
        <div class="inner_box_main inner_box_main_2">
            <div class="box-inner growing_div">
                <div class="head_div">
                    <p class="display-4 mb-0 text-white">SMS Cart Recovery</p>
                </div>
                <div class="title_div">
                    <p class="mb-2">Features</p>
                    <p class="sub_line mb-0">3 SMS Reminders</p>
                </div>
                <div class="content_inner_div">
                    <ul class="ul_inner pl-0 mb-0">
                        <li>3 SMS Reminders</li>
                        <li>Conversion optimizes SMS Content</li>
                        <li>Personalized SMS Messages Per Customer</li>
                        <li>Discount Dripping For Optimized Conversion</li>
                    </ul>
                </div>
            </div>
            <div class="box-inner growing_div">
                <div class="head_div">
                    <p class="display-4 mb-0 text-white">For Growing Business</p>
                </div>
                <div class="title_div">
                    <p class="mb-2">Features</p>
                    <p class="sub_line mb-0 text-gray">Optimized Growth Tool to Capture New
                        Subscribers</p>
                </div>
                <div class="content_inner_div">
                    <ul class="ul_inner pl-0 mb-0">
                        <li>Exit Intent Popup</li>
                        <li>Collect Email Subscribe</li>
                        <li>Collect Push Subscribers</li>
                        <li>Collect SMS Subscribers</li>
                        <li>Offer a Discount in Exchange for Subscribing</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ActiveCart"
}
</script>

<style scoped>

</style>
