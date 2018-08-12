import Vue from 'vue';
import axios from 'axios';
import Navbar from './components/Navbar/Navbar';
import FlashMessage from './components/Flash/FlashMessage.vue';
import Reply from './components/Reply/Reply.vue';
import ReplyForm from './components/Reply/ReplyForm.vue';
import ReplyCounter from './components/Reply/ReplyCounter.vue';
import ReplyDivider from './components/Reply/ReplyDivider.vue';
import SubscribeButton from './components/Subscription/SubscribeButton.vue';
import AvatarUploader from './components/Upload/AvatarUploader.vue';

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
// level represents the type of message i.e info, danger, warning, etc
window.flash = function(message, level = null) {
  window.events.$emit('flashEvent', {message, level});
}

// Our Vue instance
const app = new Vue({
    el: '#app',
    
    components: {
      'lu-navbar' : Navbar,
      'lu-flash-message' : FlashMessage,
      'lu-reply' : Reply,
      'lu-counter' : ReplyCounter,
      'lu-reply-form': ReplyForm, 
      'lu-divider' : ReplyDivider,
      'lu-subscribe-button' : SubscribeButton,
      'lu-avatar-uploader': AvatarUploader
    }
});

