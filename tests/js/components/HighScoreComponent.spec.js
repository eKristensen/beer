import { mount } from '@vue/test-utils';
import HighScoreComponent from '../../../resources/assets/js/components/HighScoreComponent.vue';

describe('HighScoreComponent.vue', () => {
  it('says that it is an example component', () => {
    const wrapper = mount(HighScoreComponent);
    expect(wrapper.html()).toContain('Example Component');
  });
});
