<template>
    <div class="container">
        <br>
        <div class="buttons">
            <button class="button is-loading" v-if="products==null"></button>
            <button class="button" v-if="products!=null" v-on:click="get_all_statistics()">Alle produkter</button>
            <template v-for="product in products">
                <button class="button" v-on:click="get_statistics(product)">{{ product.name }}</button>
            </template>
        </div>
        <highcharts :options="chartOptions"></highcharts>
    </div>
</template>

<script>
    import axios from 'axios';
    import {Chart} from 'highcharts-vue'

    export default {
        name: 'HighScoreComponent',
        mounted() {
            console.log('Component mounted.')
        },
        data() {
            return {
                products: null,
                chartOptions: {
                    chart: {
                        height: 600,
                        type: 'column'
                    },
                    title: {
                        text: 'Statistik'
                    },
                    subtitle: {
                        text: 'For de sidste 30 dage'
                    },
                    xAxis: {
                        type: 'category',
                        labels: {
                            rotation: -45
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Antal'
                        }
                    },
                    legend: {
                        enabled: false
                    },
                    series: [{
                        name: 'Vælg data',
                        data: [
                        ],
                        dataLabels: {
                            enabled: true,
                            rotation: -90,
                            color: '#FFFFFF',
                            align: 'right',
                            format: '{point.y:.1f}', // one decimal
                            y: 10, // 10 pixels down from the top
                        }
                    }]
                }
            };
        },
        beforeMount(){ // Autoload
          this.get_products()
        },
        methods: {
            get_products() {
              axios({
                    method: 'get',
                    url: '/api/products'
                })
                .then(response => {
                    this.products = response.data.data;
                })
                .catch(error => {
                    this.products = "Error";
                });
            },
            get_all_statistics() {
              axios({
                    method: 'get',
                    url: '/api/statistics'
                })
                .then(response => {
                    this.chartOptions.title.text = 'Alle produkter'
                    this.chartOptions.series = [{
                        name: 'Alle produkter',
                        data: response.data.data,
                        dataLabels: {
                            enabled: true,
                            rotation: -90,
                            color: '#FFFFFF',
                            align: 'right',
                            format: '{point.y}', // one decimal
                            y: 10, // 10 pixels down from the top
                        }
                    }]
                })
                .catch(error => {
                    // TODO Error
                });
            },
            get_statistics(product) {
              axios({
                    method: 'get',
                    url: '/api/statistics/'+product.id
                })
                .then(response => {
                    this.chartOptions.series = [{
                        name: product.name,
                        data: response.data.data,
                        dataLabels: {
                            enabled: true,
                            rotation: -90,
                            color: '#FFFFFF',
                            align: 'right',
                            format: '{point.y}', // one decimal
                            y: 10, // 10 pixels down from the top
                        }
                    }]
                })
                .catch(error => {
                    // TODO Error
                });
            }
        },
        components: {
            highcharts: Chart
        }
    }
</script>
