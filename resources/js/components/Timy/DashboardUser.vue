<template>
    <div class="__dashboard">
        <div class="loading" v-if="loading">Loading...</div>
        <div class="" v-else>
            <div class="row">
                <div class="col-6 col-sm-6 col-lg-4 col-xl-3">
                    <Infobox :title="hoursToday" description="Hours Today" />
                </div>
                <div class="col-6 col-sm-6 col-lg-4 col-xl-3">
                    <Infobox :title="hoursLastDate" description="Previous Date" />
                </div>
                
                <div class="col-6 col-sm-6 col-lg-4 col-xl-3">
                    <Infobox :title="hoursPayrollTD" description="This Payroll" />
                </div>
                
                <div class="col-6 col-sm-6 col-lg-4 col-xl-3">
                    <Infobox :title="hoursLastPayroll" description="Last Payroll" />
                </div>
            </div>
            <div class="row">
                <div class="col-12 mb-2">
                   <TimersBarChart :chart-data="hours_daily"
                    chart-title="Daily Hours" 
                    border-color="rgba(63, 81, 181, 1)" 
                    background-color="rgba(63, 81, 181, 0.25)" />
                </div>
                <div class="col-12">
                   <TimersBarChart :chart-data="hours_by_payrolls" 
                    chart-title="Hours By Payroll" 
                    border-color="rgba(46,125,50 ,1)" 
                    background-color="rgba(46,125,50 ,.25)" />
                </div>
            </div>
            <div class="row">
                <div class="col-auto col-sm-12">
                    <TimersTable /> 
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {TIMY_DROPDOWN_CONFIG, eventBus} from './config'
import Infobox from './partials/_Infobox'
import TimersTable from './partials/_UserTimersTable'
import TimersBarChart from './partials/_UserTimersBarChart'

export default {
    data() {
        return {
            loading: true,
            hours_today: 0,
            hours_today_initial: 0,
            hours_payrolltd: 0,
            hours_payrolltd_initial: 0,
            hours_last_date: 0,
            hours_last_payroll: 0,
            hours_daily: 0,
            hours_by_payrolls: 0,
            user: null,
        }
    },

    mounted() {
        /**
         * listen to timer-counter-updated event by _UserTimersTabe 
         * in method updateOpenTimers
         */
        eventBus.$on('timer-counter-updated', async (hours) => {
            this.hours_today.hours = Number(this.hours_today_initial.hours) + Number(hours)
            this.hours_payrolltd.hours = this.hours_payrolltd_initial.hours + hours
        }) // updateOpenTimers method
        /**
         * Set up a timeout to give the Dropdown component the time to render and create a new timer. 
         * Trying to ensure the last timer is also fetched by the UserTimesTable component. 
         */
        setTimeout(() => {
            axios.get(`${TIMY_DROPDOWN_CONFIG.routes_prefix}/timers/user_dashboard`)
                .then(({data}) => {
                    this.hours_today = data.data.hours_today
                    this.hours_today_initial = {...data.data.hours_today}
                    this.hours_payrolltd = data.data.hours_payrolltd
                    this.hours_payrolltd_initial = {...data.data.hours_payrolltd}
                    this.hours_last_date = data.data.hours_last_date
                    this.hours_daily = data.data.hours_daily
                    this.hours_by_payrolls = data.data.hours_by_payrolls
                    this.user = data.user

                    return data
                })
                .finally(() => this.loading = false)
        }, 2000)
    },

    computed: {
        hoursToday() {
            return this.hours_today ? Number(this.hours_today.hours).toFixed(2):  0
        },

        hoursLastDate() {
            return this.hours_last_date ? Number(this.hours_last_date.hours).toFixed(2): 0
        },

        hoursPayrollTD() {
            return this.hours_payrolltd ? Number(this.hours_payrolltd.hours).toFixed(2): 0
        },

        hoursLastPayroll() {
            return this.hours_last_payroll ? Number(this.hours_last_payroll.hours).toFixed(2):  0
        }
    },

    components: {Infobox, TimersTable, TimersBarChart}
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