<template>
    <sms-automation v-if="path == 'sms/automation'" @update="changeTemplate"/>
    <sms-campaign v-else-if="path == 'sms/campaigns'" />
    <sms-analytics v-else-if="path == 'sms/analyics'" />
    <sms-chat v-else-if="path == 'sms/chat'" />

    <sms-create-edit-automation v-else-if="path == 'sms/addreminder' || path.indexOf('sms/editreminder') >= 0" @update="changeTemplate"/>
</template>

<script>

import SmsAutomation from "./Automation";
import SmsCampaigns from "./Campaign";
import SmsCampaign from "./Campaign";
import SmsAnalytics from "./Analytics";
import SmsChat from "./Chat";
import SmsCreateEditAutomation from "./CreateEditAutomation";
export default {
    name: "SmsIndex",
    components: {
        SmsCampaign,
        SmsAutomation, SmsCampaigns, SmsAnalytics, SmsChat, SmsCreateEditAutomation
    },
    props: ['path'],
    methods:{
        changeTemplate(action){
            localStorage.setItem('rc_currpage', action);
            this.$emit('update', action);
        }
    }
}
</script>

<style scoped>

</style>
