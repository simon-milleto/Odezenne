// For authoring Nightwatch tests, see
// http://nightwatchjs.org/guide#usage

module.exports = {
  'ticketing e2e tests': function test(browser) {
    // automatically uses dev Server port from /config.index.js
    // default: http://localhost:8080
    // see nightwatch.conf.js
    const devServer = browser.globals.devServerURL;

    browser
      .url(devServer + '/billetterie')
      .waitForElementVisible('.o-container', 5000)
      .assert.elementPresent('.c-cart')
      .end();
  },
};
