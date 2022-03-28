<template>
    <div class="rc__content-loader">
        <content-loader
            width=400
            height=160
            viewBox="0 0 400 160"
            primaryColor="#f0efef"
            secondaryColor="#dedede"
        >
            <rect x="48" y="8" rx="3" ry="3" width="88" height="6" />
            <rect x="48" y="26" rx="3" ry="3" width="52" height="6" />
            <rect x="0" y="56" rx="3" ry="3" width="410" height="6" />
            <rect x="0" y="72" rx="3" ry="3" width="380" height="6" />
            <rect x="0" y="88" rx="3" ry="3" width="178" height="6" />
            <rect x="0" y="8" rx="3" ry="3" width="30" height="30" />
        </content-loader>
    </div>
</template>

<script>
import {  ContentLoader } from 'vue-content-loader';

export default {
    name: "SkeletonLoader",
    components: {
        ContentLoader
    },
}
</script>

<style scoped>

</style>
