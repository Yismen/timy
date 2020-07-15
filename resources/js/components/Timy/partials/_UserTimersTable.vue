<template>
    <div>
        <Pagination :links="links" :meta="meta" @pagination-updated="fetchTimers" />
        <table class="table table-striped table-inverse table-responsive table-light">
            <thead class="thead-inverse">
                <tr>
                    <th scope="col">Disposition</th>
                    <th scope="col">Started At</th>
                    <th scope="col">Finished At</th>
                    <th scope="col" width="10%">Total Hours</th>
                    <th scope="col" width="10%">Total Payable Hours</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="timer in timers" :key="timer.id" class="text-success"
                    :class="{'text-info': !timer.finished_at, 'text-danger': !timer.is_payable && timer.finished_at}"
                >
                    <td scope="row">{{ timer.disposition }}</td>
                    <td>{{ timer.started_at }}</td>
                    <td>{{ timer.finished_at }}</td>
                    <td :class="[timer.is_payable ? 'text-success' : 'text-danger']">
                        {{ timer.total_hours.toFixed(2).toLocaleString() }}
                    </td>
                    <td :class="[timer.is_payable ? 'text-success' : 'text-danger']">
                        {{ timer.payable_hours.toFixed(2).toLocaleString() }}
                    </td>
                </tr>
            </tbody>
        </table>
        <Pagination :links="links" :meta="meta" @pagination-updated="fetchTimers" />
    </div>
</template>

<script>
import moment from 'moment'
import {TIMY_DROPDOWN_CONFIG, eventBus} from '../config'
import Pagination from './_Pagination'
export default {
    data() {
        return {
            loading: false,
            now: moment(),
            timers: [],
            links: [],
            meta: [],
            openTimersInterval: null
        }
    },

    mounted() {
        this.fetchTimers()
        this.registerSiblinsEventListeners()
        
    },

    methods: {
        /**
         * Fetch the timers from the backend. If @param url is null, us index url, otherwise use 
         * passed url. This is ideal for using pagination links. Then the openTimersInterval 
         * is cleared to kill timer refresh. Then a new timer is set to automatically 
         * update all open timers visible in the table.
         */
        fetchTimers(url = null) {            
            url = url ?? `${TIMY_DROPDOWN_CONFIG.routes_prefix}/timers`
            
            axios.get(url)
                .then(({data}) => {
                    this.timers = data.data
                    this.links = data.links
                    this.meta = data.meta
                })
                .then(() => clearInterval(this.openTimersInterval))
                .then(() => this.updateOpenTimers())
        },
        /**
         * Set an interval to automatically update the total hours and the payable hours of all 
         * open timers visible. Open timers are those where the finished_at date is null.
         */
        updateOpenTimers(timer) {
            this.openTimersInterval = setInterval(() => {
                this.timers.forEach(timer => {
                    if (timer.finished_at == null) {
                        let total_hours = Number(moment().diff(moment(this.now), 'seconds') / 60 / 60)
                        eventBus.$emit('timer-counter-updated', total_hours)
                        
                        timer.total_hours = total_hours
                        if (!! timer.is_payable) {
                            timer.payable_hours = total_hours
                        }
                    }
                })
            }, 35000 )/** Every 35 seconds, time that actually change a decimal value */
        },
        /**
         * Return a boolean indicating if the current timer is payable to the user.
         */
        isPayable(timer) {
            return !! timer.disposition.payable || false
        },
        /**
         * Return a text class based on current timer setup.
         */
        parseRowClass(timer) {
            if(! timer.finished_at) {
                return 'text-info'
            }
            if (! timer.is_payable) {
                return 'text-danger'
            }
            return 'text-success'
        },
        
        closeAllOpenTimers() {
            this.timers.forEach(timer => {
                if (timer.finished_at == null) {
                    timer.finished_at = moment().utcOffset(-240).format('YYYY-MMM-DD HH:mm:ss')
                }
            })
        },

        registerSiblinsEventListeners() {
            /** 
             * Sibling components communication implementation. A better implementation 
             * would be Vuex or any Web-Sockets such as Puhser, but we are using 
             * this aproach to reduce dependencies.
             */
            eventBus.$on('timer-created', async (timer) => {
                await this.timers.forEach(timer => {
                    if (timer.finished_at == null) {
                        timer.finished_at = moment().utcOffset(-240).format('YYYY-MMM-DD HH:mm:ss')
                    }
                })
                
                this.now = moment() // reset timer
                this.timers.unshift(timer)
            })      

            eventBus.$on('timer-stopped', async (timer) => {
                await this.closeAllOpenTimers()                
                this.now = moment() // reset timer
                    // this.timers.unshift(timer)
            }) // updateOpenTimers method
        }
    },
    
    components: {Pagination}
}
</script>

<style>

</style>