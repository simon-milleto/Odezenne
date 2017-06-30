<template>
  <main>
    <div class="o-container">
      <div class="o-grid o-grid--guttered">
        <search v-if="isLoaded"></search>
        <loader v-if="!isLoaded"></loader>
        <ticket v-for="ticket in tickets"
                :ticket="ticket"
                :key="ticket.id"></ticket>
      </div>
    </div>
  </main>
</template>

<script>
  import { mapGetters } from 'vuex';
  import axios from 'axios';

  import config from '../config';

  import Ticket from '../components/Ticket/Ticket';
  import Search from '../components/Ticket/Search';
  import Loader from '../components/Loader';

  export default {
    name: 'ticketing',
    data() {
      return {
        allTickets: [],
        search: '',
        isLoaded: false,
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
            this.isLoaded = true;
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
      Search,
      Loader,
    },
  };
</script>

<style lang="scss" scoped>
  @import '../assets/scss/05_objects/container';
  @import '../assets/scss/01_settings/typography';
  @import '../assets/scss/01_settings/colors';

.logo {
    text-align: center;
    margin: 80px 0;
    width: 100%;
  }

.logo img {
    width: 40%;
  }

.search{
  width: 300px;
  margin: 45px auto 80px;
  margin-top: 20px;
  position:relative;
}

.search input{
  -webkit-appearance: none;
  border: none;
  border-radius: 0;
  background-color: transparent;
  border-bottom: 1px solid $_black;
  display: block;
  width: 300px;
  font-size: $font-smaller;
  padding: .25em;
  box-sizing: border-box;
  outline: none;
  font-family: $primary-font-family;
  letter-spacing: 2px;
}

.right{
  float: right;
  position: relative;
  left: 50px;
  bottom: 40px;
  font-size: 40px !important;
}

</style>
