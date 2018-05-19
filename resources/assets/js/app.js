import Vue from 'vue';
import FlashMessage from './components/FlashMessage.vue';

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
      'lu-flash-message' : FlashMessage
    }
});

