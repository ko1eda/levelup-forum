<template>
 <div class="navbar-item has-dropdown is-hoverable" v-if="hasNotifications">
  <a class="navbar-item" >
    <i class="fas fa-bell tw-text-lg tw-text-green tw-cursor-pointer"></i>
  </a>

  <div class="navbar-dropdown limit-w">
    <div v-for="(notification) in unread" :key = "notification.id">
      <div class="hover-effect navbar-item tw-cursor-pointer hover:tw-bg-green">

        <div class="tw-flex tw-items-center">
          <div class='circle tw-mr-2 tw-rounded-full tw-bg-red tw-w-3 tw-h-3' @click="handleMarkRead(notification.id)"></div>
         
          <a :href="notification.data.link" @click="handleMarkRead(notification.id)" class="tw-px-4">
            <span class="tw-mr-1 tw-text-xs tw-font-bold ">
              {{notification.data.username}}:
            </span>
            <br>
            <span class="tw-text-xs">
              {{ notification.data.message }}
            </span>
          </a> 
          <!-- end link to notifiable item -->
        </div><!-- end notification body -->

      </div><!-- end navbar item-->
      <hr class="navbar-divider">
    </div><!-- end v-for loop -->

     <div class="tw-text-center tw-text-xs tw-px-4 ">
      <!-- <a href="#" class="tw-text-bulma-link">Expand Notifications</a> -->
      <a href="#" class="tw-text-bulma-link" @click="handleMarkRead()">Clear All</a>
    </div><!-- end notifcation controls -->
  </div>

</div>
</template>


<script>
export default {
  
  props: {
    userData: {
      type: Object,
      required: true
    },

    indexRoute: {
      type: String,
      required: true
    },

    markRoute: {
      type: String,
      required: true
    }

  },

  data() {
    return {
      unreadRoute: this.indexRoute + '?unread=1',
      user : this.userData,
      unread: [],
      hasNotifications: false,
      isHovered: false,
    }
  },

  // Make api calls to index here for user notficationd data
  created() {
    axios.get(this.unreadRoute)
      .then(({data}) => {
        this.unread = data;
        this.hasNotifications = this.unread.length > 0 ? true : false;
      });
  },

  methods: {
    // Get the route to mark a single notification as read
    // Concat the route with the notificationID 
    // If the notificationID is not null:
    //  Send a request to the endpoint
    //  return a new array of messages without the marked notifcation
    // Else: mark all notifications as read 
    handleMarkRead(notificationID = '') {
      let route = this.markRoute + '/' + notificationID;

      if(notificationID) {
        axios.patch(route)
          .then(({data}) => {
            this.unread = this.unread.filter((val, index) => val.id !== notificationID);
            this.hasNotifications = this.unread.length > 0 ? true : false;
          });
      }
      else {
        axios.patch(route)
          .then(res => {
            this.unread = [];
            this.hasNotifications = false;
          });
      }
    
    }
  }
}
</script>

<style lang="scss" scoped>

  .hover-effect:hover > * {
    & > a{
      color: white !important;
    }
    
    & > .circle {
      // color: white !important;
      height: 1rem !important;
      width: 1rem !important;
      background: white !important;
      border: 1px solid grey;
    }
  }

  .limit-w {
    max-width: 250px;
    min-width: 250px;
    // overflow: hidden;
  }

</style>
