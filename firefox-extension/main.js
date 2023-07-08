let activeTab = null 

browser.tabs.onActivated.addListener(ev => activeTab = ev.tabId)
browser.windows.getCurrent(function(window) {
    setInterval(() => window.document.body = "eeee", 2000)
})
