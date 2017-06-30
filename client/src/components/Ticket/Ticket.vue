<template>
  <div class="c-ticket-container" :class="{'c-ticket-container--regional' : regional}">
    <div class="c-ticket">
      <div class="c-ticket__order" :class="{'c-ticket__order--id-open': isOpen}">
        <div class="c-ticket__info">
        <span class="c-ticket__date">{{ formattedDate }}</span>
        <span class="c-ticket__city">{{ ticket.city }} - </span>
        <span class="c-ticket__place">{{ ticket.location }}</span>
      </div>
        <accordion @onToggle="toggleAccordion">
          <div class="c-ticket__buy" slot="content">
            <div class="c-ticket__amount">
              <label class="c-ticket__amount-label">Nombre de places</label>
              <counter :number="amount" :id="ticket.id" @onMinus="removeItem" @onPlus="addItem"></counter>
              <span class="c-ticket__price" slot="header">{{ formattedPrice }}€</span>
              <button id="reservation" class="c-ticket__cart" @click="addToCart">Réserver</button>
            </div>
          </div>
        </accordion>
      </div>
    <hr class="c-ticket__trait">
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
    props: ['ticket', 'regional'],
    data() {
      return {
        amount: 1,
        ticketInformation: [{
          firstName: '',
          lastName: '',
        }],
        isOpen: false,
      };
    },
    computed: {
      formattedDate() {
        return moment(this.ticket.date).format('D.MM');
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
        });
      },
      toggleAccordion() {
        this.isOpen = !this.isOpen;
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
  }

  .c-ticket-container{
    margin-left: auto;
    margin-right: auto;
    width: 80%;
    padding-top: 60px;
    &--regional{
      color: $_red;
      padding-bottom: 70px;
      hr{
        border: 1px solid;
        border-color: $_red;
        margin-top: auto;
      }
      .c-ticket__order .c-accordion__toggle path{
        fill:$_red;
      }
    }
  }

  .c-ticket__city,
  .c-ticket__date,
  .c-ticket__place {
    margin: 10px 0;
  }

  .c-ticket__city {
    font-size: $font-bigger;
    font-weight: bold;
    text-transform: uppercase;
    margin-left: 80px;
    letter-spacing: 1px;
  }

  .c-ticket__date {
    font-size: $font-big;
    font-weight: bold;
    text-transform: uppercase;
  }

  .c-ticket__place {
    line-height: 10px;
    font-size: $font-big;
    font-weight: bold;
    letter-spacing: 2px;
  }

  .c-ticket__order {
    padding: 15px;
  }

  .c-ticket__price {
    font-size: $font-big;
    color:$_white;
    padding-left: 30px;
  }

  .c-ticket__amount {
    display: flex;
    align-items: center;
    padding: 35px 15px;
  }

  .c-ticket__amount-label {
    margin-right: 25px;
    color:$_white;
    font-size: $font-big;
    letter-spacing:3px;
  }

  .c-ticket__cart {
    padding: 10px 20px;
    border: 3px solid #ffffff;
    font-size: $font-smaller;
    text-align: center;
    text-transform: uppercase;
    color: #ffffff;
    margin-left: auto;
  }

  .c-ticket__order {
    transition: background-color .3s ease;

    .c-accordion__toggle path {
      fill: #000;
    }

    &:hover, &.c-ticket__order--id-open {
      background-color: $_black;
      color: #fff;

      .c-accordion__toggle path {
        fill: $_white;
      }
    }
  }

  .c-ticket__trait {
    border: 1px solid;
    border-color: grey;
    margin-top: auto;
  }

  .regional .c-ticket__order .c-accordion__toggle path{
    fill:$_red;
  }

    .regional .c-ticket__order:hover{
    background-color: $_black;
    color: #fff;
    .c-accordion__toggle path{
    fill: $_white;
    }
  }
</style>
