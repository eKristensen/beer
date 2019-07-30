import { shallowMount } from '@vue/test-utils';
//import Vue from 'vue'
import HighScoreComponent from '../../../resources/assets/js/components/HighScoreComponent.vue';

import axios from 'axios';
import MockAdapter from 'axios-mock-adapter';

// Mock it!

describe('HighScoreComponent.vue', () => {
  it('can get products from api and show them', async () => {
    /*
    //const vm = new Vue(HighScoreComponent).$mount()
    //const prod = vm.get_products();
    //expect(vm.products).toBe('result')
    */

    // This sets the mock adapter on the default instance
    var mock = new MockAdapter(axios);

    // Mock any GET request to /users
    // arguments for reply are (status, data, headers)
    mock.onGet('/api/products').reply(200, {
      data: [
        { id: 1, name: 'Test product #1' },
        { id: 2, name: 'Test product #2' }
      ]
    });


    const wrapper = shallowMount(HighScoreComponent);

    // WHY 4 ticks??
    await wrapper.vm.$nextTick()
    await wrapper.vm.$nextTick()
    await wrapper.vm.$nextTick()
    await wrapper.vm.$nextTick()




    //wrapper.vm.$nextTick(() => {
        expect(wrapper.html()).toContain('Test product #1');
        expect(wrapper.html()).toContain('Test product #2');
     //   done();
   // })
  });


  it('can get statistics for all from api and show them', async () => {
    /*
    //const vm = new Vue(HighScoreComponent).$mount()
    //const prod = vm.get_products();
    //expect(vm.products).toBe('result')
    */

    // This sets the mock adapter on the default instance
    var mock = new MockAdapter(axios);


    // Mock any GET request to /users
    // arguments for reply are (status, data, headers)
    mock.onGet('/api/products').reply(200, {
      data: [
        { id: 1, name: 'Test product #1' },
        { id: 2, name: 'Test product #2' }
      ]
    });

    // Mock any GET request to /users
    // arguments for reply are (status, data, headers)
    mock.onGet('/api/statistics').reply(200, {
      data: [
        { "Test room": 33 }
      ]
    });


    const wrapper = shallowMount(HighScoreComponent);

    // WHY 4 ticks??
    await wrapper.vm.$nextTick()
    await wrapper.vm.$nextTick()
    await wrapper.vm.$nextTick()
    await wrapper.vm.$nextTick()


    // Click that button
    const button = wrapper.find('#get-all')
    button.trigger('click')

    // WHY 4 ticks??
    await wrapper.vm.$nextTick()
    await wrapper.vm.$nextTick()
    await wrapper.vm.$nextTick()
    await wrapper.vm.$nextTick()

    // Check the VM got the data
    expect(wrapper.vm.chartOptions.series[0].data).toEqual([{ "Test room": 33 }]);

  });
});
