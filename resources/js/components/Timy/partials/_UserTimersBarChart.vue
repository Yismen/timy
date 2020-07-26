<template>    
    <div class="card">
        <div class="card-body">
            <LineChart :chart-data="datacollection" :options="options" :styles="myStyles" />
        </div>
    </div>
</template>

<script>
import LineChart from '../charts/LineChart'

export default {
    data() {
        return {
            datacollection: {},
            options: {
                responsive: true,
                maintainAspectRatio: false,
                hover: {
                    intersect: true,
                },
                tootips: {
                    mode: 'dataset'
                }
            },
            myStyles: {
                height: '200px',
                position: 'relative'
            }
        }
    },

    props: {
        chartData: {required: true},
        chartTitle: {default: 'Default Title'},
        borderColor: {default: 'rgba(63,81,181,1)'},
        backgroundColor: {default: 'rgba(63,81,181,.25)'},
    },

    mounted () {
        this.fillData()
    },

    methods: {
        async fillData () {
            let labels = []
            let data = []
            await this.chartData.forEach(timer => {
                labels.push(timer.date)
                data.push(Number(timer.hours).toFixed(2))
            })

            this.datacollection = {
                labels: labels,
                datasets: [
                    {
                        label: this.chartTitle  ,
                        borderColor: this.borderColor,
                        backgroundColor: this.backgroundColor,
                        borderWidth: 1,
                        data: data
                    }
                ]
            }
        }
    },
      
    watch: {
        chartData(val) {
            val.forEach(timer => {
                this.datacollection.labels.push(timer.date)
                this.datacollection.datasets[0].data.push(timer.hours)
            });
        }
    },

    components: {LineChart}
}
</script>

<style>

</style>