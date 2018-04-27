<template>
    <div class="entry">
        {{entry.m.user.first_name}} {{entry.m.user.last_name}}
    </div>
</template>

<script>
    export default {
        name: "entry",
        props: {
            entry: Object
        },
    }
</script>

<style scoped>

</style>