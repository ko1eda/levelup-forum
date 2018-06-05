import Vue from 'vue';
import axios from 'axios';
import FlashMessage from './components/FlashMessage.vue';
import Reply from './components/Reply.vue';

// Global Vue event bus
window.events = new Vue;
window.axios = axios;

// Global flash function for emiting
// flash messages 
window.flash = function(message) {
  window.events.$emit('flashEvent', message);
}

// Our Vue instance
const app = new Vue({
    el: '#app',
    
    components: {
      'lu-flash-message' : FlashMessage,
      'lu-reply' : Reply
    }
});

