<template>

</template>
<script>
export default {
    created(){
        axios.post('/api/auth/logout', {
            token: this.$getToken(),
        }).then(() => {
            localStorage.removeItem('stockist-data')
            this.$router.replace('login')
        })
    },
}
</script>
