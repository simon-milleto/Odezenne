<template>
  <div class="o-grid__cell o-grid__cell--4/12">
    <div class="c-ticket">
      <div class="c-ticket__info">
        <span class="c-ticket__city">{{ ticket.city }}</span>
        <span class="c-ticket__date">{{ formattedDate }}</span>
        <span class="c-ticket__place">{{ ticket.location }}</span>
      </div>
      <div class="c-ticket__order">
        <accordion>
          <span class="c-ticket__price" slot="header">{{ formattedPrice }}€</span>
          <div class="c-ticket__buy" slot="content">
            <div class="c-ticket__amount">
              <label class="c-ticket__amount-label">Nombre de places</label>
              <counter :number="amount" :id="ticket.id" @onMinus="removeItem" @onPlus="addItem"></counter>
            </div>
            <button class="c-ticket__cart" @click="addToCart">Ajouter au panier</button>
          </div>
        </accordion>
      </div>
    </div>
  </div>
</template>

<script>
  import moment from 'moment';
  import currency from 'currency.js';

  import store from '../../store';

  import Accordion from './Accordion';
  import Counter from './Counter';

  export default {
    name: 'ticket',
    props: ['ticket'],
    data() {
      return {
        amount: 1,
        ticketInformation: [{
          firstName: '',
          lastName: '',
        }],
      };
    },
    computed: {
      formattedDate() {
        return moment(this.ticket.date).format('dddd Do MMMM');
      },
      formattedPrice() {
        currency.settings.separator = ' ';
        currency.settings.decimal = ',';
        return currency(this.ticket.price).format();
      },
    },
    methods: {
      removeItem() {
        if (this.amount > 0) {
          this.amount -= 1;
          this.ticketInformation.splice(0, 1);
        }
      },
      addItem() {
        this.amount += 1;
        this.ticketInformation.push({
          firstName: '',
          lastName: '',
        });
      },
      addToCart() {
        store.commit('addToCart', {
          id: this.ticket.id,
          amount: this.amount,
          city: this.ticket.city,
          place: this.ticket.location,
          date: this.formattedDate,
          price: this.ticket.price,
          information: this.ticketInformation,
        })
        ;
      },
    },
    components: {
      Accordion,
      Counter,
    },
  };
</script>

<style lang="scss">
  @import '../../assets/scss/01_settings/colors';
  @import '../../assets/scss/01_settings/typography';
  @import '../../assets/scss/05_objects/grid';

  .c-ticket {
    position: relative;
    margin: 35px 50px;
    border: 5px solid $_black;
    background-color: $_white;
  }

  .c-ticket__info {
    position: relative;
    top: -40px;
    left: -50px;
    background-color: white;
    padding-left: 15px;
    display: block;
    padding-bottom: 20px;
    line-height: 25px;
  }

  .c-ticket__city,
  .c-ticket__date,
  .c-ticket__place {
    margin: 10px 0;
  }

  .c-ticket__city {
    display: block;
    font-size: $font-biggest;
    font-weight: bold;
    text-transform: uppercase;
  }

  .c-ticket__date {
    display: block;
    font-size: $font-big;
    font-weight: lighter;
    text-transform: uppercase;
  }

  .c-ticket__place {
    display: block;
    line-height: 10px;
    font-size: $font-big;
    font-weight: lighter;
  }

  .c-ticket__order {
    padding: 20px;
    background-color: $_black;
  }

  .c-ticket__price {
    font-size: $font-normal;
    color:$_white;
  }

  .c-ticket__amount {
    display: flex;
    align-items: center;
    padding: 30px 0;
  }

  .c-ticket__amount-label {
    margin-right: 25px;
    color:$_white;
  }

  .c-ticket__cart {
    width: calc(100% - 30px);
    margin: 0 15px;
    padding: 10px 20px;
    border: 3px solid $neutral-color;
    font-size: $font-small;
    text-align: center;
    text-transform: uppercase;
    color:$_white;
  }
</style>
