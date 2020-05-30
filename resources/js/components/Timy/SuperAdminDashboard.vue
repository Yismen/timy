<template>
    <div class="__dashboard">
        <div class="loading" v-if="loading">Loading...</div>

        <div class="" v-else>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Blanditiis, adipisci id. Temporibus, provident laudantium voluptate nostrum quae, aspernatur id maxime omnis perferendis rem deleniti eveniet, molestias iusto nulla atque soluta.
        </div>
        <!-- Dashboard -->
    </div>
</template>

<script>
import {TIMY_DROPDOWN_CONFIG} from './config'

export default {
    data() {
        return {
            loading: false,
            roles: [],
            unassigned: []
        }
    },

    mounted() {
        /**
         * Set up a timeout to give the Dropdown component the time to render and create a new timer. 
         * Trying to ensure the last timer is also fetched by the UserTimesTable component. 
         */
        setTimeout(() => {
            axios.get(`${TIMY_DROPDOWN_CONFIG.routes_prefix}/super_admin`)
                .then(({data}) => {
                    this.roles = data.roles
                    this.unassigned = data.unassigned
                })
                .finally(() => this.loading = false)
        }, 2000)
    },
}
</script>

<style scoped>
    .loading{
        height: 70vh;
        font-size: xxx-large;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>