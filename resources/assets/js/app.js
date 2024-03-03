
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vue from 'vue'

const Swal = require('sweetalert2')
const axios = require('axios');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('high-score', require('./components/HighScoreComponent.vue').default);

const app = new Vue({
    el: '#app'
});



let buyfunc = function buy(room,type,quantity) {
  axios.get('/api/buy/'+room+'/'+type+'/'+quantity)
  .then(function (response) {
    Swal.fire({
      title: 'Køb: '+quantity+' '+response.data.product,
      html: response.data.name+'<br>Ny saldo: '+response.data.sum+' kr.',
      icon: 'success',
      timer: 3000
    })
  })
  .catch(function (error) {
    Swal.fire({
      title: 'Det skete en fejl.',
      text: error,
      icon: 'error'
    })
    console.log(error);
  });
}

window.buy = buyfunc;


let sumfunc = function sum(room) {
  axios.get('/api/sum/'+room)
  .then(function (response) {
    Swal.fire({
      title: 'Saldo: '+response.data.data.sum + ' kr.',
      text: response.data.data.name,
      icon: 'info',
      timer: 5000
    })
  })
  .catch(function (error) {
    Swal.fire({
      title: 'Det skete en fejl.',
      text: error,
      icon: 'error'
    })
    console.log(error);
  });
}

window.sum = sumfunc;


let refundfunc = function refund(id) {
  axios.get('/api/refund/'+id)
  .then(function (response) {
    Swal.fire({
      title: 'Refunderet',
      html: 'Beløb: '+response.data.data.amount,
      icon: 'success',
      timer: 3000
    }).then((value) => location.reload())
  })
  .catch(function (error) {
    Swal.fire({
      title: 'Det skete en fejl.',
      text: error,
      icon: 'error'
    })
    console.log(error);
  });
}

window.refund = refundfunc;
