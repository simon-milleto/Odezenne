<template>
  <main>
    <o-header></o-header>
    <div class="o-container">
      <form>
        <div class="search">
            <input type="text" v-model="searchString" placeholder="Search" />
                    <i class="material-icons right">search</i>
        </div>
      </form>
      <div class="o-grid o-grid--guttered">
        <search></search>
        <ticket v-for="ticket in tickets"
                :ticket="ticket"
                :key="ticket.id"></ticket>
      </div>
      <cart></cart>
    </div>
  </main>
</template>

<script>
  import { mapGetters } from 'vuex';
  import axios from 'axios';

  import config from '../config';

  import Ticket from '../components/Ticket/Ticket';
  import Cart from '../components/Ticket/Cart';
  import Search from '../components/Ticket/Search';

  export default {
    name: 'ticketing',
    data() {
      return {
        allTickets: [],
        search: '',
      };
    },
    computed: {
      ...mapGetters({
        postCode: 'postCode',
      }),
      tickets() {
        return this.allTickets.filter((ticket) => {
          const lowerCaseCity = ticket.city.toLowerCase();
          const lowerCaseSearch = this.search.toLowerCase();

          return lowerCaseCity.indexOf(lowerCaseSearch) !== -1;
        });
      },
    },
    mounted() {
      this.getTickets();
      this.$on('emitSearch', (search) => {
        this.search = search;
      });
    },
    methods: {
      getTickets() {
        axios.get(`${config.apiEndpoint}/tickets`)
          .then((response) => {
            this.orderTickets(response.data);
          });
      },
      orderTickets(allTickets) {
        const formattedZipcode = this.postCode.substring(0, 2);

        const upcomingTickets = allTickets.filter(ticket => new Date(ticket.date) >= new Date());

        this.allTickets = upcomingTickets.filter((ticket) => {
          const formattedTicketZipcode = ticket.zipcode.substring(0, 2);
          return formattedZipcode === formattedTicketZipcode;
        });

        upcomingTickets.sort((a, b) => new Date(a.date) - new Date(b.date));

        this.allTickets = this.allTickets.concat(upcomingTickets);
      },
    },
    components: {
      Ticket,
      Cart,
      Search,
    },
  };
</script>

<style lang="scss">
  @import '../assets/scss/05_objects/container';
  @import '../assets/scss/01_settings/typography';
  @import '../assets/scss/01_settings/colors';

h1{
font-size: $font-hugest;
font-weight: bold;
text-align: center;
padding-top: 70px;
}

.search{
    background-color:$_black;
    width: 300px;
    padding: 5px;
    margin: 45px auto 80px;
    margin-top: 20px;
    position:relative;
}

.search input{
    background: #fff;
    border: none;
    width: 100%;
    padding: 10px;
    font-size: $font-smaller;
    font-family: inherit;
}

.right{
    float: right;
    position: relative;
    left: 50px;
    bottom: 40px;
    font-size: 40px !important;
}

</style>
