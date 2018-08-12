<script>
import NotificationWidget from '../Notification/NotificationWidget'
export default {

  components: {
     'lu-notification-widget': NotificationWidget,
  },


  data() {
    return {

      isActive: false

    }
  }

}
</script>

<style lang="scss" scoped>
//  Anything above a tablets max portrait width
//  will not have the second notification widget visible
@media (min-width: 1025px) {
  .hidden-non-touch {
    display:none;
  }
}
// this hides the original notification widget
// on anything below 1025px
@media (max-width: 1025px) {
  .hidden-touch {
    display:none;
  }
}
</style>
  