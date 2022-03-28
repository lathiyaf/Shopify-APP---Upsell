<template>
    <div class="Polaris-Labelled__Error">
        <div id="PolarisTextField4Errorkey" class="Polaris-InlineError">
            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
            {{msg}}
        </div>
    </div>
</template>

<script>
export default {
    props: ['msg'],
    name: 'ErrorMsg'
}
</script>

<style scoped>
.Polaris-InlineError{
    color: red;
}
</style>
