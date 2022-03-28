<template>
    <push-automation v-if="path == 'push/automation'" @update="changeTemplate"/>
    <welcome-popup v-else-if="path == 'push/welcomepopup'"/>

    <push-create-edit-automation v-else-if="path == 'push/addreminder' || path.indexOf('push/editreminder') >= 0" @update="changeTemplate"></push-create-edit-automation>
</template>

<script>

import PushAutomation from "./Automation";
import WelcomePopup from "./WelcomePopup";
import PushCreateEditAutomation from "./CreateEditAutomation";

export default {
    name: "index",
    props: ['path'],
    components:{
        PushAutomation, WelcomePopup, PushCreateEditAutomation
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
