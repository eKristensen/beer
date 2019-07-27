import { mount } from '@vue/test-utils';
import expect from 'expect';
import HighScoreComponent from '../../resources/js/components/HighScoreComponent.vue';

describe('HighScoreComponent.vue', () => {
  it('says that it is an example component', () => {
    const wrapper = mount(ExampleComponent);
    expect(wrapper.html()).toContain('Example Component');
  });
});
