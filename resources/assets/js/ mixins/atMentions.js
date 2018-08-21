import AtTa from 'vue-at/dist/vue-at-textarea';
import {debounce} from 'lodash';

export default {

  components: {
    AtTa
  },

  props: {
    usersPath: {
      type: String, 
      required: true
    },
  },


  data() {
    return {
      members: [],
      temp: [],
      endOfArr: 0,
      prevSearchedTerm: '',
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

      // if there is no @ split at all dont run any logic
      if (this.endOfArr === 0) {
        return ;
      }
      
      let search = this.temp[this.endOfArr];

      // if there is a fresh @split bring up the previously searched term
      // as options
      if(this.temp[this.endOfArr] === '' && this.endOfArr > 1) {
        search = this.prevSearchedTerm;
      }
      
      axios.get(this.usersPath + '?user=' + search)
        .then(({data}) => {
          this.members = data;

          // if a matched was returned then the previous search was a good search
          // so it should be saved to use again
          if(data.length !== 0 ) {
            this.prevSearchedTerm = this.temp[this.endOfArr];
          }
        });
    },

    // debounce the user input into the text area
    debounceInput: debounce(function(e) {
      console.log(e)
      this.fetchUsers(e.target.value);
    }, 500), 
  },
}
