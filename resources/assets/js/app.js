import Vue from 'vue';
import axios from 'axios';
import Navbar from './components/Navbar/Navbar';
import Thread from './components/Thread/Thread.vue';
import Card from './components/Etc/Card.vue';
import TextEditor from './components/Etc/TextEditor.vue';
import HtmlRenderer from './components/Etc/HtmlRenderer.vue';
import ReplyCounter from './components/Reply/ReplyCounter.vue';
import FlashMessage from './components/Flash/FlashMessage.vue';
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

// etc components
Vue.component('lu-text-editor', TextEditor);
Vue.component('lu-html-renderer', HtmlRenderer);

// Our Vue instance
const app = new Vue({
    el: '#app',
    
    components: {
      'lu-navbar' : Navbar,
      'lu-flash-message' : FlashMessage,
      'lu-thread' : Thread,
      'lu-card' : Card,
      'lu-counter' : ReplyCounter,
      'lu-avatar-uploader': AvatarUploader
    }
});

