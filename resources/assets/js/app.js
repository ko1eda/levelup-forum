import Vue from 'vue';
import axios from 'axios';
import FlashMessage from './components/Flash/FlashMessage.vue';
import Reply from './components/Reply/Reply.vue';
import ReplyCounter from './components/Reply/ReplyCounter.vue';
import ReplyDivider from './components/Reply/ReplyDivider.vue';
import SubscribeButton from './components/Subscription/SubscribeButton.vue';

// Global axios instance with csrf token
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// Global Vue event bus
window.events = new Vue;

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
      'lu-counter' : ReplyCounter,
      'lu-divider' : ReplyDivider,
      'lu-subscribe-button' : SubscribeButton
    }
});

