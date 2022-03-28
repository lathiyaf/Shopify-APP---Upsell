<template>
    <email-automation v-if="path == 'email/automation'" @update="changeTemplate"/>
    <email-analytics v-else-if="path == 'email/analyics'" />
    <email-campaign v-else-if="path == 'email/campaigns'" @update="changeTemplate"/>

    <email-create-edit-automation v-else-if="path == 'email/addreminder' || path.indexOf('email/editreminder') >= 0 || path == 'email/addcampaign' || path.indexOf('email/editcampaign') >= 0" />
</template>

<script>

import EmailAutomation from './Automation';
import EmailAnalytics from './Analytics';
import EmailCampaign from './Campaign';
import EmailCreateEditAutomation from './CreateEditAutomation';
import EmailCreateEditCampaign from './CreateEditCampaign';

export default {
    name: "Emailindex",
    props: ['path'],
    components: {
        EmailAutomation, EmailAnalytics, EmailCampaign, EmailCreateEditAutomation, EmailCreateEditCampaign
    },
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
