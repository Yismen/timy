<template>
  <li class="__dispos_control">
    <div v-if="loading" class="loading">
        Loading...
    </div>
    <select
        v-else
        class="custom-select"
        :class="[payable ? 'bg-green' : 'bg-yellow']"
        id="inputGroupSelect01"
        @change.prevent="createNewTimer"
    >
        <!-- <option value="0" selected>-- Select One --</option> -->
        <option
        class="disposition_option"
        :selected="current == disposition.id"
        v-for="disposition in dispositions"
        :key="disposition.id"
        :value="disposition.id"
        :class="[disposition.payable == 1 ? 'bg-white' : 'bg-yellow']"
        >{{ disposition.name }} {{ disposition.payable == 1 ? '$$' : '--'}}</option>
    </select>
  </li>
</template>

<script>
import {TIMY_DROPDOWN_CONFIG, eventBus} from './config'
import Cookies from 'js-cookie'

export default {
    data() {
        return {
            loading: false,
            current: TIMY_DROPDOWN_CONFIG.default_disposition_id,
            dispositions: []
        }
    },

    computed: {
        currentDisposition() {
            return this.dispositions.filter(disposition => {
                return disposition.id == this.current
            })[0]
        },

        payable() {
            return this.currentDisposition && this.currentDisposition.payable == 1 ? true : false
        }
    },

    mounted() {
        this.watchForWindowUnloadEvent()
        this.getCurrentlyOpenTimerOrCreateANewOne()
        this.fetchDispositions()
        this.setupTimerToReloadWindow()
    },

    methods: {
        getCurrentlyOpenTimerOrCreateANewOne() {
            this.loading = true
            axios.get(`${TIMY_DROPDOWN_CONFIG.routes_prefix}/timy_timers/running`)
                .then(({data}) => {    
                    this.current = this.getCurrentDispositionId(data.data)
                })
                .then(() => {
                    axios.post(`${TIMY_DROPDOWN_CONFIG.routes_prefix}/timy_timers`, {disposition_id: this.current})
                        
                })
                .finally(() => this.loading = false)
        },

        getCurrentDispositionId(running){
            // Check if the user has a disposition running
            if(running) {
                return running.disposition_id
            }

            // Check if there is a valid cookie
            let cookie_disposition = Cookies.get('dainsys_timy')
            if(cookie_disposition) {
                return cookie_disposition
            }

            // if nothing is set return the default disposition id
            return TIMY_DROPDOWN_CONFIG.default_disposition_id
        },

        setupTimerToReloadWindow() {
            // Check if backend session is alive
            setInterval(() => {
                axios.get('timy_ping')
                    .catch(error => window.location.reload())
            }, 15 * 60 * 1000)
            
            if(TIMY_DROPDOWN_CONFIG.auto_refresh && TIMY_DROPDOWN_CONFIG.auto_refresh == true) {
                setInterval(() => {
                    window.location.reload()                
                }, Number(TIMY_DROPDOWN_CONFIG.session_length));
            }
        },

        watchForWindowUnloadEvent() {
            window.onbeforeunload = function() {
                axios.post(`${TIMY_DROPDOWN_CONFIG.routes_prefix}/timy_timers/close_all`)
                    .then((response) => {})
            }
        },

        fetchDispositions() {          
            this.loading = true
            axios.get(`${TIMY_DROPDOWN_CONFIG.routes_prefix}/timy_dispositions`)
                .then(({data}) => this.dispositions = data.data)
                .finally(() => this.loading = false)
        },

        createNewTimer(event) {
            this.loading = true
            axios.post(`${TIMY_DROPDOWN_CONFIG.routes_prefix}/timy_timers`, {disposition_id: event.target.value})
                .then(({data}) =>{ 
                    this.current = data.data.disposition_id
                    eventBus.$emit('timer-created', data.data)
                    return data.data
                })
                .then(response => {
                    Cookies.set(
                        TIMY_DROPDOWN_CONFIG.cookie_prefix, 
                        response.disposition_id, 
                        {expires: 0.5}
                    )
                })
                .finally(() => this.loading = false)
        }
    }
}
</script>
<style lang="css" scoped>
    .bg-green {
    background-color: rgba(59, 193, 114, 0.5);
    }
    .bg-yellow {
    background-color: rgba(255, 237, 74, 0.5);
    }
    .loading {
        padding: 0.5rem !important;
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
        font-style: italic !important;
        font-weight: bolder !important;
    }
    .custom-select{
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
        transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out,
            box-shadow 0.15s ease-in-out;
        background: #fff
            url(data:image/svg+xml,%3csvg xmlns=http://www.w3.org/2000/svg width=4 height=5 viewBox=0 0 4 5%3e%3cpath fill=%23343a40 d=M2 0L0 2h4zm0 5L0 3h4z/%3e%3c/svg%3e)
            no-repeat right 0.75rem center/8px 10px;
    }
    
    .disposition_option {
        cursor: pointer;
    }
    
    
    .disposition_option:hover {
        cursor: pointer;
        border: none !important;
    }
</style>