import Vue from 'vue';
import axios from 'axios';
import FlashMessage from './components/Flash/FlashMessage.vue';
import Reply from './components/Reply/Reply.vue';
import ReplyCounter from './components/Reply/ReplyCounter.vue';

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
      'lu-reply' : Reply,
      'lu-counter' : ReplyCounter
    }
});

