<template>
    <div class="__TimyAdmin">
        <div v-if="loading" class="loading">Loading...</div>
        <div v-else>
            <h5>Running Timers</h5>
            <table class="table table-hover bg-white">
                <thead class="thead-inverse">
                    <tr>
                        <th>User</th>
                        <th>Started At</th>
                        <th>Disposition</th>
                        <th colspan="2">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(timer, index) in running_timers" :key="timer.id">
                            <td scope="row">
                                <a href="#" @click.prevent="seeUserProfile(timer.user_id)">
                                    {{ timer.name }} 
                                </a>
                            </td>
                            <td>{{ timer.started_at }}</td>
                            <td>{{ timer.disposition }}</td>
                            <td>
                                <button class="btn btn-xs btn-danger" title="Close Timer" @click.prevent="closeTimer(timer, index)">X</button>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select 
                                        class="form-control custom-select" 
                                        name="" id="dispositionId"
                                        :class="[!! timer.is_payable ? 'bg-white' : 'bg-warning']"
                                        @change.prevent="updateTimer(timer, index, $event)"
                                    >
                                        <option 
                                            v-for="disposition in dispositions" 
                                            :key="disposition.id"
                                            :value="disposition.id"
                                            :selected="disposition.id == timer.disposition_id"
                                            :class="[!!disposition.payable ? 'bg-white' : 'bg-warning']"
                                        >
                                            {{ disposition.name }}
                                        </option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                    </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import {TIMY_DROPDOWN_CONFIG, eventBus} from './config'
import Cookies from 'js-cookie'

export default {
    data() {
        return {
            loading: true,
            dispositions: null,
            running_timers: null,
            users: null,
        }
    },

    mounted() {  
        setTimeout(() => {
            axios.get(`${TIMY_DROPDOWN_CONFIG.routes_prefix}/admin`)
                .then(({data}) => {
                    this.dispositions = data.data.dispositions
                    this.running_timers = data.data.running_timers
                    this.users = data.data.users
                })
                .then(() => {
                    let vm = this
                    window.Echo.private(`Timy.Admin`)
                        .listen('.Dainsys\\Timy\\Events\\TimerCreatedAdmin', function(response) {
                            let index = vm.running_timers.findIndex(item => item.user_id === response.user.id)
                            if(index >= 0) {
                                vm.$set(vm.running_timers, index, response.timer)
                            } else {
                                vm.running_timers.push(response.timer)
                            }
                        });
                })
                .finally(() => this.loading = false)
        }, 3000)
        
    },

    methods: {
        async updateTimer(timer, index, event) {
            this.loading = true            
            await this.completeClose(timer, index)

            axios.post(`${TIMY_DROPDOWN_CONFIG.routes_prefix}/admin/create_timer_forced/${timer.user_id}/${event.target.value}`)
                .then(({data}) => {
                    this.running_timers.splice(index, 0, data.data)
                    return data.data
                })
                .then(response => {                    
                    Cookies.set(
                        TIMY_DROPDOWN_CONFIG.cookie_prefix, 
                        response.disposition_id, 
                        {expires: 0.5} // 0.5 days is 12 hours
                    )
                    return response
                })
                .finally(response => this.loading = false)
        },

        closeTimer(timer, index) {
            let confirmation = confirm("Are you sure?")            
            if(confirmation) {
                this.completeClose(timer, index)
            }
        },

        completeClose(timer, index) {
            axios.delete(`${TIMY_DROPDOWN_CONFIG.routes_prefix}/timers/${timer.id}`)
                    .then(({data}) => {
                        this.running_timers.splice(index, 1)
                        return data.data
                    })
                    .then(response => eventBus.$emit('timer-closed', response))
        },

        seeUserProfile(user_id) {
            console.log(user_id)
        }
    }
}
</script>

<style>

</style>