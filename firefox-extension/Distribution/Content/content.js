/**
 * Reset status for tracking etc....
 * stored temporary in the background script
 */
// @ts-ignore
browser.runtime.sendMessage({ action: 'reset-data' });

/**
 * If user leaves the tab
 */
window.onbeforeunload = () => {
  // @ts-ignore
  browser.runtime.sendMessage({ action: 'reset-data' });
};

class NetworkMonitor {

  constructor() {
    this.urlLocation = window.location;
  }

  /**
   * Get stored elements from the background script
   */
  getBackgroundstorage() {
    // @ts-ignore
    return browser.runtime
      .sendMessage({
        action: 'get-user-settings-dashboard',
      })
      .then(response => response)
      .catch(error => { });
  }

  /**
   * Main initialisation function
   */
  initProcess() {
    this.setOnClickEventListenerToTheDom();
  }

  /**
   * Check if the website using a react-router
   * and if the url changed then reload the protector functionalitty
   */
  setOnClickEventListenerToTheDom() {
    document.removeEventListener('click', this.checkLocation);
    document.addEventListener('click', this.checkLocation);
  }

  /**
   * Check location, if the stored href are changed then
   * reload the security context of this extension
   * This feature needed for websites based on
   * react framework
   */
  checkLocation() {
    const self = this;
    let count = 5;
    let shouldCheck = false;
    clearInterval(x);

    var x = setInterval(() => {
      if (self.urlLocation.href && self.urlLocation.href != window.location.href && count > 0) {
        self.urlLocation.href = window.location.href;
        shouldCheck = true;
        clearInterval(x);
      }

      if (self.urlLocation.hash && self.urlLocation.hash != window.location.hash && count > 0) {
        self.urlLocation.hash = window.location.hash;
        shouldCheck = true;
        clearInterval(x);
      }

      if (shouldCheck) {
        try {
          if (this.userData.securityIsOn) {
            // @ts-ignore
            browser.runtime
              .sendMessage({
                action: 'reset-data',
              })
              .then(() => {
                this.initProcess();
              })
              .catch(error => { });
          }
        } catch (error) { }
      }

      if (!count) {
        return clearInterval(x);
      }
      count--;
    }, 100);
  }
}

/**
 * Wait for "document"
 */
var timeouter;

const checkElementsToStart = () => {
  if (window.location == '' || document == undefined || document.body == undefined) {
    clearTimeout(timeouter);

    return (timeouter = setTimeout(() => {
      checkElementsToStart();
    }, 10));
  }

  clearTimeout(timeouter);
  new NetworkMonitor().initProcess();
};

checkElementsToStart();

// @ts-ignore
browser.runtime.onMessage.addListener(request => {
  switch (request.action) {
    case 'check-addons-availablitity' : {
      return Promise.resolve(true);
      break;      
    }
    default: {
      break;
    }
  }
  return true;
});