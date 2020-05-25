export const TIMY_DROPDOWN_CONFIG = {
    /**
     * The package assumes you are sending requests to the same server. Update this variable and re-compile
     * to prefix your routes. For instance, you can pass the whole server url 'https://example.com' or
     * maybe just use the backslash pattern such as '/api' or '/admin' https://example.com/api'
     */
    routes_prefix: "",

    /**
     * Any time the user refresh the browser or close it all running timers are closed. 
     * However, when the component is mounted a new timer is set automatically using
     * the known id provided in this variable. 
     */
    default_disposition_id: 1,

    /**
     * Store all the timers in this module to allow store like centralized state controlls
     */
    timers: [],

    /**
     * Boolean. If set to true, the browser will refresh automatically
     * every amount of minutes.
     */
    auto_refresh: false,

    /**
     * The amount of time the browser will refresh itself, closing the current 
     * timer and setting a new one, if AUTO_REFRESH is set to true, otherwise
     * it will remain active
     */
    session_length: 15 * 60 * 1000, /** 15 minutes */

    /** 
     * Cookie prefix name
     */
    cookie_prefix: 'dainsys_timy'
}

import Vue from 'vue'

export const eventBus = new Vue()