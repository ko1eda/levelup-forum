<script>
// inline-template defined in reply-form.blade.php
import AtTa from 'vue-at/dist/vue-at-textarea';
import {debounce} from 'lodash';


export default {

  components: {
    AtTa
  },

  props: {
    apiPath: {
      type: String, 
      required: true
    }
  },


  data() {
    return {
      members: [],
      temp: [],
      endOfArr: 0,
    }
  },

  methods : {
    // Receive the current input value for the textarea.
    // The split the value into an array based on each @symbol
    // and set the arrays index to the last item in the array (which will be the last mentioned user)
    // ex value = 'hey what is up @dog' then the array would be temp[0] = 'hey what is up' and temp[1]='dog'
    // then send that data to our end point and return the resulting list of usersnames
    // setting them to the list of searchable members this.member = data
    fetchUsers(value) {
      this.temp = value.split('@');

      this.endOfArr = this.temp.length - 1;
      
      axios.get(this.apiPath + '?user=' + this.temp[this.endOfArr])
        .then(({data}) => this.members = data);
    },

    // debounce the user input into the text area
    debounceInput: debounce(function(e) {
      this.fetchUsers(e.target.value);
    }, 500), 
  },


}
</script>
