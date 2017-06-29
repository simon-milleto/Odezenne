<template>
  <main>
    <div class="o-container">
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
</style>
