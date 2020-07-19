<template>
    <div class="__dashboard">
        <div class="loading" v-if="loading">Loading...</div>
        <div class="" v-else>    
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="card mb-2">
                        <div class="card-body p-0">               
                            <h4 class="card-title m-0 p-3 border-bottom">
                                Unassigned Users:
                                <span class="badge badge-pill" :class="[unassigned.length > 0 ? 'bg-danger  text-white' : 'bg-light text-muted']">
                                    {{ unassigned.length }}
                                </span>
                            </h4>   
                            <div class="card-text bg-light ">
                            <draggable :sort="false" v-model="unassigned" class="draggable" @remove="dataDropped"  group="users" id="unassigned">
                                <div v-for="user in unassigned" :key="user.id"  :id="user.id" class="bg-light border">
                                    {{ user.name }}
                                </div>
                            </draggable>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6" v-for="role in roles" :key="role.id">
                    <div class="card mb-2">
                        <div class="card-body p-0">
                            <h4 class="card-title m-0 p-3 border-bottom">
                                {{ role.name }}: 
                                <span class="badge badge-pill" :class="[role.users.length > 0 ? 'bg-primary  text-white' : 'bg-light text-muted']">
                                    {{ role.users.length }}
                                </span>
                            </h4>
                            <div class="card-text bg-light">
                                <draggable :sort="false" v-model="role.users" class="draggable"  @remove="dataDropped" group="users" :id="role.id">
                                    <div v-for="user in role.users" :key="user.id" :id="user.id" class="bg-light border">
                                        {{ user.name }}
                                    </div>
                                </draggable>
                            </div>
                        </div>
                    </div>
                </div>
            </div>          
        </div>
        <!-- Dashboard -->
    </div>
</template>

<script>
import {TIMY_DROPDOWN_CONFIG} from './config'
import draggable from 'vuedraggable'

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
                    this.roles = data.data.roles
                    this.unassigned = data.data.unassigned
                })
                .catch(({response}) => alert(response.data.message)) 
                .finally(() => this.loading = false)
        }, 2000)
    },

    methods: {
        dataDropped(item) {
            let userId = item.item.attributes.id.value
            let roleId = item.to.attributes.id.value

            if (roleId == 'unassigned') {
                this.deleteAsignation(userId)
            }

            if (userId > 0 && roleId > 0) {
                this.completeAssignation(userId, roleId)
            }
        },

        completeAssignation(userId, roleId) {
            axios.post(`${TIMY_DROPDOWN_CONFIG.routes_prefix}/assign/${userId}/${roleId}`)
                .then(({data}) => {})
                .catch(({response}) => alert(response.data.message)) 
        },

        deleteAsignation(userId) {
            axios.delete(`${TIMY_DROPDOWN_CONFIG.routes_prefix}/unassign/${userId}`)
                .then(({data}) => {})
                .catch(({response}) => alert(response.data.message)) 
        }
    },

    components: {
        draggable
    }
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
    .draggable {
        cursor: move;
    }
</style>