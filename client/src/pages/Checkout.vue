<template>
  <main>
    <div class="o-container">
      <div class="c-checkout__cart">
        <div class="c-checkout__cart-item"
             v-for="(ticket, index) in tickets">
          <div class="c-checkout__cart-item-label">
            <span class="c-checkout__cart-item-title">{{ ticket.city }} - {{ ticket.place }}</span>
            <span class="c-checkout__cart-item-date">{{ ticket.date }}</span>
          </div>
          <div class="c-checkout__cart-item-information">
            <span class="c-checkout__cart-item-price">{{ ticket.price | currency }}€</span>
            <div class="c-checkout__cart-item-person" v-for="(ticketInfo, index) in ticket.information">
              <span class="c-checkout__cart-item-person-title">Informations personnelles du billet n°{{ index + 1
                }}</span>
              <div class="c-checkout__input">
                <label :for="'firstName' + index + '-' + index">Prénom</label>
                <input :id="'firstName' + index + '-' + index" type="text" :name="'firstName' + index + '-' + index"
                       v-model="ticketInfo.firstName">
              </div>
              <div class="c-checkout__input">
                <label :for="'lastName' + index + '-' + index">Nom</label>
                <input :id="'lastName' + index + '-' + index" type="text" :name="'lastName' + index + '-' + index"
                       v-model="ticketInfo.lastName">
              </div>
            </div>
          </div>
        </div>
        <div class="c-checkout__cart-action">
          <span class="c-checkout__cart-total">Total : {{ total | currency }}€</span>
        </div>
      </div>
      <form class="c-checkout__form">
        <div class="c-checkout__input">
          <label for="firstName">Prénom</label>
          <input id="firstName" type="text" name="firstName" v-model="user.firstName" :class="{'input': true, 'is-danger': (errors.has('firstName') || serverErrors.firstName)}" v-validate="'required|alpha_dash'">
          <span v-show="errors.has('firstName')" class="help is-danger">{{ errors.first('firstName') }}</span>
          <span v-if="serverErrors.firstName" class="help is-danger">{{ serverErrors.firstName.msg }}</span>
        </div>
        <div class="c-checkout__input">
          <label for="lastName">Nom</label>
          <input id="lastName" type="text" name="lastName" v-model="user.lastName"  :class="{'input': true, 'is-danger': (errors.has('lastName') || serverErrors.lastName) }" v-validate="'required|alpha_dash'">
          <span v-show="errors.has('lastName')" class="help is-danger">{{ errors.first('lastName') }}</span>
          <span v-if="serverErrors.lastName" class="help is-danger">{{ serverErrors.lastName.msg }}</span>
        </div>
        <div class="c-checkout__input">
          <label for="email">Adresse mail</label>
          <input id="email" type="text" name="email" v-model="user.email" :class="{'input': true, 'is-danger': (errors.has('email') || serverErrors.email) }" v-validate="'required|email'">
          <span v-show="errors.has('email')" class="help is-danger">{{ errors.first('email') }}</span>
          <span v-if="serverErrors.email" class="help is-danger">{{ serverErrors.email.msg }}</span>
        </div>
        <div class="c-checkout__input">
          <label for="address">Adresse</label>
          <input id="address" type="text" name="address" v-model="user.address"  :class="{'input': true, 'is-danger': (errors.has('address') || serverErrors.address) }" v-validate="'required'">
          <span v-show="errors.has('address')" class="help is-danger">{{ errors.first('address') }}</span>
          <span v-if="serverErrors.address" class="help is-danger">{{ serverErrors.address.msg }}</span>
        </div>
        <div class="c-checkout__input">
          <label for="postcode">Code postal</label>
          <input id="postcode" type="text" name="postcode" v-model="user.postcode"  :class="{'input': true, 'is-danger': (errors.has('postcode') || serverErrors.postcode) }" v-validate="'required|digits:5'">
          <span v-show="errors.has('postcode')" class="help is-danger">{{ errors.first('postcode') }}</span>
          <span v-if="serverErrors.postcode" class="help is-danger">{{ serverErrors.postcode.msg }}</span>
        </div>
        <div class="c-checkout__input">
          <label for="city">Ville</label>
          <input id="city" type="text" name="city" v-model="user.city"  :class="{'input': true, 'is-danger': (errors.has('city') || serverErrors.city) }" v-validate="'required|alpha_dash'">
          <span v-show="errors.has('city')" class="help is-danger">{{ errors.first('city') }}</span>
          <span v-if="serverErrors.city" class="help is-danger">{{ serverErrors.city.msg }}</span>
        </div>
        <div class="c-checkout__input">
          <label for="phoneNumber">Numéro de téléphone</label>
          <input id="phoneNumber" type="text" name="phoneNumber" v-model="user.phoneNumber"  :class="{'input': true, 'is-danger': (errors.has('phoneNumber') || serverErrors.phoneNumber) }" v-validate="'required|numeric'">
          <span v-show="errors.has('phoneNumber')" class="help is-danger">{{ errors.first('phoneNumber') }}</span>
          <span v-if="serverErrors.phoneNumber" class="help is-danger">{{ serverErrors.phoneNumber.msg }}</span>
        </div>
        <button @click.prevent="checkout">Commander</button>
      </form>
      <div class="c-checkout__payment" v-if="order.isOrdered">
        <div id="paypal-button"></div>
      </div>
    </div>
  </main>
</template>

<script>
  import { mapGetters } from 'vuex';
  import axios from 'axios';
  import currency from 'currency.js';

  import config from '../config';
  import validationMessage from '../validationMessage';

  export default {
    name: 'checkout',
    data() {
      return {
        order: {
          isOrdered: true,
          isPaid: false,
          id: '',
          total: '',
        },
        user: {
          firstName: '',
          lastName: '',
          email: '',
          address: '',
          postcode: '',
          city: '',
          phoneNumber: '',
        },
        serverErrors: '',
      };
    },
    computed: {
      ...mapGetters({
        tickets: 'cartTickets',
      }),
      total() {
        return this.tickets.reduce((a, b) => a + (b.amount * b.price), 0);
      },
    },
    filters: {
      currency: price => currency(price).format(),
    },
    mounted() {
      this.$validator.updateDictionary(validationMessage);
      this.$validator.setLocale('en');
    },
    methods: {
      checkout() {
        this.$validator.validateAll().then((result) => {
          if (result) {
            const formattedItems = [];

            this.tickets.forEach((ticket) => {
              formattedItems.push({
                id: ticket.id,
                quantity: ticket.amount,
                information: ticket.information,
              });
            });

            axios.post(`${config.apiEndpoint}/tickets/checkout`, { items: formattedItems, user: this.user })
              .then((response) => {
                if (response.data.errors) {
                  const spreadedErrors = { ...response.data.errors };
                  this.serverErrors = spreadedErrors;
                } else {
                  this.order.isOrdered = true;
                  this.order.id = response.data.id;
                  this.order.total = response.data.total;
                  this.showPaymentButton(response.data.id);
                }
              });
          } else {
            console.log('Correct the errors!');
          }
        }).catch(() => {
          console.log('Correct the errors!');
        });
      },
      updateOrder(transactionId, creationTime) {
        axios.put(`${config.apiEndpoint}/tickets/checkout`, { orderId: this.order.id, transactionId, creationTime })
          .then(() => {
            localStorage.removeItem('cartTickets');
            localStorage.removeItem('cartExpiration');
          });
      },
      showPaymentButton(orderId) {
        paypal.Button.render({
          env: 'sandbox',
          commit: true,
          payment() {
            return paypal.request.post(`${config.apiEndpoint}/tickets/checkout/payment/create`, { orderId })
              .then(response => response.paymentID);
          },
          onAuthorize(data) {
            return paypal.request.post(`${config.apiEndpoint}/tickets/checkout/payment/execute`,
              {
                paymentID: data.paymentID,
                payerID: data.payerID,
              })
              .then((response) => {
                axios.put(`${config.apiEndpoint}/tickets/checkout`, { orderId, transactionId: response.id })
                  .then(() => {
                    localStorage.removeItem('cartTickets');
                    localStorage.removeItem('cartExpiration');
                  });
              });
          },
        }, '#paypal-button');
      },
    },
  };
</script>

<style lang="scss">
  @import '../assets/scss/05_objects/container';
  @import '../assets/scss/01_settings/colors';
  @import '../assets/scss/01_settings/typography';   

.c-checkout__cart{
    width: 350px;
    padding: 20px;
    margin: auto;
    position: relative;
    border: 5px solid #000000;}

.c-checkout__cart-item-title{
    display: block;
    font-size: 2.6rem;
    font-weight: bold;
    text-transform: uppercase;
    }

.c-checkout__cart-item-date{
    display: block;
    font-size: 2rem;
    font-weight: lighter;
    text-transform: uppercase;}

.c-checkout__input input{
    width: 100%;
    padding: 10px 20px;
    border: 2px solid $_black;
    font-size: $font-smallest;
}

.c-checkout__form{
    max-width: 350px;
    margin: auto;
    padding-top: 35px;
}

.c-checkout__cart-item-price{
    padding-top: 15px;
    display: block;
}

.c-checkout__cart-item-person-title{
    display: block;
    padding-bottom: 10px;
    font-weight: 600;
    margin-top: 23px;
}

.c-checkout__cart-action{
    padding-top: 25px;
    text-transform: uppercase;
    font-weight: 600;
}

.c-checkout__input label{
    margin-top: 10px;
    margin-bottom: 10px;
    display: block;
}

.c-checkout__form button{
    margin-top: 25px;
    padding: 10px 20px;
    border: 2px solid $_black;
    float: right;
    margin-bottom: 25px;
}

</style>
