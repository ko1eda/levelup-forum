import Vue from 'vue';
import FlashMessage from './components/FlashMessage.vue';

// window.Vue = require('vue');

const app = new Vue({
    el: '#app',
    
    components: {
      'lu-flash-message' : FlashMessage
    }
});

