<template>
  <main role="main">
    <div class="o-container">
      <div class="login-page">
        <h1><img src="../assets/images/odezenne_logotype.svg"></h1>
          <form class="login-form">
            <div class="inputs-container">
              <div class="email-input">
                <input type="email" v-model="email" placeholder="Adresse mail *" required @click="toolTipAction" />
                <img src="../assets/images/trait_2.svg">
              </div>
              <div class="postcode-input">
                <input type="text" v-model="postCode" placeholder="Code postal *" required @click="toolTipAction" maxlength="5"/>
                <img src="../assets/images/trait_2.svg">
              </div>
              <div class="grammage-input">
                <input type="text" v-model="grammage" :placeholder="question" @click="toolTipAction" required maxlength="5"/>
                <img src="../assets/images/trait_2.svg">
              </div>
              <div class="infos" :class="{active: toolTip}">
                <p>Pourquoi donner mes infos ?</p>
                <ul>
                  <li> - Faire partie du truc</li>
                  <li> - Avoir des trucs exclusifs</li>
                  <li> - Être informés des derniers trucs</li>
                </ul>
              </div>
            </div>
            <p>* Champs obligatoires</p>
            <div class="mendatory">
              <input for="mendatory" type="checkbox" v-model="mendatory"/>
              <label id="mendatory"> J'accepte qu'Odezenne m'informent de leurs actualités</label>
            </div>
            <div class="drawbox" @click.prevent="onLogin">
              <div class="draw">
                <input type="submit" value="VALIDER" />
              </div>
            </div>
            <transition-group mode="out-in" name="translate">
              <div class="error-log" v-if="errorMailLog != ''" :key="1">{{errorMailLog}}</div>
              <div class="error-log" v-if="errorPostcodeLog != ''" :key="2">{{errorPostcodeLog}}</div>
              <div class="error-log" v-if="errorQuestionLog != ''" :key="3">{{errorQuestionLog}}</div>
              <div class="error-log" v-if="errorMendatoryLog != ''" :key="4">{{errorMendatoryLog}}</div>
            </transition-group>
          </form>
      </div>
    </div>
  </main>
</template>

<script>
  import { mapActions } from 'vuex';

  export default {
    name: 'Login',
    data() {
      return {
        email: '',
        errorMailLog: '',
        errorPostcodeLog: '',
        errorQuestionLog: '',
        errorMendatoryLog: '',
        postCode: '',
        grammage: '',
        mendatory: '',
        toolTip: false,
        questions: ['Tu aimes le chocolat ?', 'Tu fais cramer la barraque ?', 'Jai pas didées', 'Tu as dit bonjour ?'],
        question: '',
      };
    },
    mounted() {
      this.question = this.randomQuestion();
    },
    methods: {
      ...mapActions({
        login: 'login',
      }),
      postCodeTest(code) {
        if (code.length !== 5 || isNaN(code) || code.length === 0) {
          return false;
        }
        return true;
      },
      onLogin() {
        /* eslint-disable no-useless-escape*/
        const reMail = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
        if (reMail.test(this.email) && this.postCodeTest(this.postCode) && this.grammage !== '' && this.mendatory) {
          this.login({ email: this.email, postCode: this.postCode });
          this.errorMailLog = 'Chargement ...';
          this.errorPostcodeLog = '';
        } else {
          if (!reMail.test(this.email)) {
            this.errorMailLog = 'Veuillez entrer un e-mail valide';
          } else {
            this.errorMailLog = '';
          }
          if (this.postCodeTest(this.postCode)) {
            this.errorPostcodeLog = '';
          } else {
            this.errorPostcodeLog = 'Veuillez entrer un code postal valide';
          }
          if (this.grammage !== '') {
            this.errorQuestionLog = '';
          } else {
            this.errorQuestionLog = 'Bah réponds à la question';
          }
          if (this.mendatory) {
            this.errorMendatoryLog = '';
          } else {
            this.errorMendatoryLog = 'Accepte les conditions';
          }
        }
      },
      toolTipAction() {
        this.toolTip = true;
      },
      randomQuestion() {
        const x = 0;
        const y = this.questions.length - 1;
        /* eslint-disable no-mixed-operators */
        const i = Math.floor(Math.random() * ((y - x) + 1) + x);
        return this.questions[i];
      },
      checkMendatory() {
        console.log('Okay');
      },
    },
  };
</script>

<style lang="scss" scoped>
  .login-page {
    display: flex;
    align-items: center;
    flex-direction: column;
    width: 100%;
    font-weight: 700;
  }
  .login-page h1 {
    text-align: center;
    width: 80%;
    margin-top: 30px;
    margin-bottom: 80px;
  }
  .login-page img {
    width: 80%;
  }
  .login-page>div {
    text-align: center;
    margin-bottom: 30px;
  }
  .login-form {
    display: flex;
    flex-direction: column;
    width: 80%;
    margin: 0 auto;
    align-items: left;
    max-width: 300px;
    >p {
      font-size: 13px;
      font-family: "Roboto Condensed";
      font-weight: 100;
      margin-top: 12px;
    }
    input[type=checkbox] {
      width:22px;
      height: 22px;
      margin-right: 15px;
    }
    .mendatory {
      display: flex;
      margin : 40px 0 40px 0;
      font-family:"Roboto Condensed";
      font-weight: 100;
    }
  }
  .inputs-container {
    position: relative;
  }
  .email-input, .postcode-input, .grammage-input {
    position: relative;
  }
  .infos {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    font-family: "Roboto Condensed";
    transition: all 0.5s ease;
    max-width: 300px;
    position: absolute;
    right: -275px;
    height:100%;
    border: 1px solid black;
    padding: 18px;
    top: 100px;
    opacity: 0;
    &.active {
      opacity: 1;
      top: 0;
    }
    ul {
      margin-top: 25px;
      li {
        padding: 8px 0;
        font-weight: 100;
      }
    }
  }
  .postcode-input {
    margin: 18px 0;
  }
  .email-input img,
  .postcode-input img,
  .grammage-input img
  {
    position: absolute;
    bottom: 8px;
    left:0;
    width:100%;
    right: 0;
    margin: auto;
  }
  .drawbox {
    position:relative;
    padding:8px 30px;
    cursor: pointer;
    margin: 0 auto;
  }

  .draw:after {
    transition: all 0.4s ease;
    border: 0.1em solid transparent;
    border-image: url('http://emptyeasel.com/wp-content/uploads/2012/11/creatingtexture2-carrielewis.jpg') 0 27 27 27 round stretch;
    transform: rotate(-1deg);
    content: '';
    top:0px;
    left:0px;
    position:absolute;
    width:100%;
    height:100%;

  }
  .draw:before:hover {
    transform: rotate(-50deg);
  }
  .draw:before {
    transition: all 0.4s ease;
    border-top: 0.1em solid transparent;
    border-image: url('http://emptyeasel.com/wp-content/uploads/2012/11/creatingtexture2-carrielewis.jpg') 27 0 0 0 stretch round;
    content: '';
    height: 3em;
    left: 0;
    top: 0;
    transform: rotate(-1deg);
    width:100%;
    position:absolute;
  }
  input[type=email], input[type=text] {
    width: 100%;
    height: 50px;
    background-color: transparent;
    border: none;
    outline: none;
    font-family: "Roboto Condensed";
    font-size: 1rem;
    font-weight: 700;
    position: relative;
    text-align: left;
  }
  .error-log {
    display: inline-flex;
    background-color: black;
    padding: 8px;
    margin-top: 20px;
    transform: translateY(0);
    color: #fff;
  }
  .error-log.active {
    transform: translateY(0px);
    opacity: 1;
  }
  @media(min-width: 720px) {
    .login-form {
      width: 30%;
    }
    .inputs-container {
      width: 100%;
    }
  }
  .translate-enter-active, .translate-leave-active {
    transition: opacity 0.3s, transform .5s ease;
  }
  .translate-enter, .translate-leave-to {
    opacity: 0;
    transform: translateY(50px);
  }
</style>
