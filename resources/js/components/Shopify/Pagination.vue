<template>
    <div class="pagination_container" v-if="data.last_page > 1">
        <ul class="page_ul box_shadow_reg border-radius-reg">
            <li @click="changePage(data.links[0].url)" :class="{'disabled': data.current_page === 1}"><i class="fa fa-angle-left"></i></li>
            <li class="page_li" v-for="(page, index) in data.links" :class="{'active_li': page.active}" v-if="index != 0 && index != data.last_page + 1" @click="changePage(page.url)">{{page.label}}</li>
            <li @click="changePage(data.links[data.last_page + 1].url)" :class="{'disabled': data.current_page === data.last_page}"><i class="fa fa-angle-right"></i></li>
        </ul>
    </div>
</template>

<script>
export default {
    name: "Pagination",
    props: ['data'],
    methods: {
        changePage(url){
            this.$emit('update', url);
        }
    }
}
</script>

<style scoped>

</style>
