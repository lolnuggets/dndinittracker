function getCookie(name) {
    const cookieString = document.cookie;
    const cookies = cookieString.split('; ');

    for (const cookie of cookies) {
        const [cookieName, cookieValue] = cookie.split('=');
        if (cookieName === name) {
            return cookieValue;
        }
    }

    return null; // Cookie not found
}

function getHost() {
    let hostname = window.location.hostname;
    if (hostname == "localhost")
        hostname += "/dndinittracker";
    return hostname;
}

function unsetCookie(cookieName) {
  // Set the expiration date to a past time
  document.cookie = `${cookieName}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
}

var button = document.createElement("li");
var a = document.createElement("a");
a.href = "http://"+ getHost() + "/login";
a.innerHTML = "Signout";
button.append(a);
a.addEventListener('click', function (event) {
    if (getCookie("loggedperms") != null)
    unsetCookie("loggedperms");

    if (getCookie("password") != null)
        unsetCookie("password");
});

document.getElementById("links").append(button);