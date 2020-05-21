<template>    
    <select class="custom-select" :class="[payable ? 'bg-green' : 'bg-yellow']" id="inputGroupSelect01" @change.prevent="createNewTimer">
        <!-- <option value="0" selected>-- Select One --</option> -->
        <option 
            class="task_option"
            :selected="current == task.id" 
            v-for="task in tasks" 
            :key="task.id" 
            :value="task.id" 
            :class="[task.payable == 1 ? 'bg-white' : 'bg-yellow']"
        >{{ task.name }} {{ task.payable == 1 ? '$$' : '--'}}</option>
    </select>
</template>

<script>
import {TIMY_DROPDOWN_CONFIG} from './config'

export default {
    data() {
        return {
            current: TIMY_DROPDOWN_CONFIG.default_task_id,
            tasks: [],
            timers: TIMY_DROPDOWN_CONFIG.timers
        }
    },

    computed: {
        currentTask() {
            return this.tasks.filter(task => {
                return task.id == this.current
            })[0]
        },

        payable() {
            return this.currentTask && this.currentTask.payable == 1 ? true : false
        }
    },

    mounted() {
        this.watchForWindowUnloadEvent()
        this.getCurrentlyOpenTimerOrCreateANewOne()
        this.fetchTasks()
        this.setupTimerToReloadWindow()
    },

    methods: {
        getCurrentlyOpenTimerOrCreateANewOne() {
            axios.get(`${TIMY_DROPDOWN_CONFIG.routes_prefix}/timy_timers/running`)
                .then(({data}) => {    
                    console.log(this.getCurrentTaskId(data.data))
                    this.current = this.getCurrentTaskId(data.data)
                })
                .then(() => {
                    axios.post(`${TIMY_DROPDOWN_CONFIG.routes_prefix}/timy_timers`, {task_id: this.current})
                })
        },

        getCurrentTaskId(running){
            // Check if the user has a task running
            if(running) {
                return running.task_id
            }

            // Check if there is a vali cookie
            let cookie_task = document.cookie.split(";") .filter(cookie => { return cookie.trim().startsWith(TIMY_DROPDOWN_CONFIG.cookie_prefix) })[0]

            if(cookie_task) {
                return Number(cookie_task.split("=").map(function(cookie) {
                    return cookie.trim()
                })[1])
            }

            // if nothing is set return the default task id
            return TIMY_DROPDOWN_CONFIG.default_task_id
        },

        setupTimerToReloadWindow() {
            if(TIMY_DROPDOWN_CONFIG.auto_refresh && TIMY_DROPDOWN_CONFIG.auto_refresh == true) {
                setInterval(() => {
                    window.location.reload()                
                }, Number(TIMY_D
                ROPDOWN_CONFIG.session_length));
            }
        },

        watchForWindowUnloadEvent() {
            window.onbeforeunload = function() {
                axios.post(`${TIMY_DROPDOWN_CONFIG.routes_prefix}/timy_timers/close_all`)
                    .then((response) => {})
            }
        },

        fetchTasks() {          
            axios.get(`${TIMY_DROPDOWN_CONFIG.routes_prefix}/timy_tasks`)
                .then(({data}) => this.tasks = data.data)
        },

        createNewTimer(event) {
            axios.post(`${TIMY_DROPDOWN_CONFIG.routes_prefix}/timy_timers`, {task_id: event.target.value})
                .then(({data}) =>{ 
                    this.current = data.data.task_id
                    // fire pusher event or whatever
                    return data.data
                })
                .then(response => {
                    let date = new Date()
                    date.setTime(date.getTime() + 1*12*60*60*1000) /** 12 hours */
                    document.cookie = `${TIMY_DROPDOWN_CONFIG.cookie_prefix}=${response.task_id}; expires=${date.toUTCString()}; path=/;`
                })
        }
    }
}
</script>
<style lang="css" scoped>
    .bg-green {
        background-color: rgba(59, 193, 114, .5) !important;
    }
    .bg-yellow {
        background-color: rgba(255, 237, 74, .5) !important;
    }
    .custom-select, .task_option{
        cursor: pointer;
        display: inline-block;
        width: 100%;
        height: calc(1.6em + 0.75rem + 2px);
        padding: 0.375rem 1.75rem 0.375rem 0.75rem;
        font-size: 0.9rem;
        font-weight: 400;
        line-height: 1.6;
        color: #495057;
        vertical-align: middle;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        -webkit-appearance: none;
        transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        background: #fff url(data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='4' height='5' viewBox='0 0 4 5'%3e%3cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e) no-repeat right 0.75rem center/8px 10px;
    }
</style>