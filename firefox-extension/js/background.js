
chrome.windows.getAll({}, function(windows) {
    windows.forEach(function(window) {
        if (window.state == "fullscreen") {
            chrome.windows.update(window.id, {
                state: oldwindowstatus
            });
        } else {
            oldwindowstatus = window.state;
            chrome.windows.update(window.id, {
                state: "fullscreen"
            });
        }
    });
});
