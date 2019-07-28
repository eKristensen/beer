
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.Vue = require('vue');

const Swal = require('sweetalert2')
const axios = require('axios');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app'
});



let buyfunc = function buy(room,type,quantity) {
  axios.get('/api/buy/'+room+'/'+type+'/'+quantity)
  .then(function (response) {
    Swal.fire({
      title: 'Køb: '+quantity+' '+response.data.product,
      html: response.data.name+'<br>Ny saldo: '+response.data.sum+' kr.',
      type: 'success',
      timer: 3000
    })
  })
  .catch(function (error) {
    Swal.fire({
      title: 'Det skete en fejl.',
      text: error,
      type: 'error',
      timer: 3000
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
      type: 'info',
      timer: 5000
    })
  })
  .catch(function (error) {
    Swal.fire({
      title: 'Det skete en fejl.',
      text: error,
      type: 'error',
      timer: 3000
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
      type: 'success',
      timer: 3000
    }).then((value) => location.reload())
  })
  .catch(function (error) {
    Swal.fire({
      title: 'Det skete en fejl.',
      text: error,
      type: 'error',
      timer: 3000
    })
    console.log(error);
  });
}

window.refund = refundfunc;
