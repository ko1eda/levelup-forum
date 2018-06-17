<template>
  <div>
    <div v-show="!isSubscribed">
      <div @click="handleSubscription">
        <i class="far fa-bell tw-text-green tw-cursor-pointer" title="Subscribe to this thread"></i>
      </div>
    </div>

    <div v-show="isSubscribed">
      <div @click="handleSubscription">
        <i class="fas fa-bell tw-text-green tw-cursor-pointer" title="Unsubscribe to this thread"></i>
      </div>
    </div>

  </div>
</template>


<script>
export default {

  props : {
    subscribed : {
      type : Object,
      required: true
    },

    endpoint : {
      type : String,
      required: true
    }
  },
  
  data () {
    return {
      isSubscribed : this.subscribed.is_subscribed,
    }
  },

  methods: {

    handleSubscription() {
      // Send post request subscriptions.threads.store endpoint
      this.isSubscribed
        ? this.unsubscribe()
        : this.subscribe();
    },

    subscribe() {
      axios.post(this.endpoint)
        .then(res => {
          this.isSubscribed = true;
          flash('Subscribed to thread!')
        });
    },

    unsubscribe() {

      axios.delete(this.endpoint)
        .then(res => {
          this.isSubscribed = false;
           flash('Unsubscribed from thread!')
        });
    }
  },

}
</script>
