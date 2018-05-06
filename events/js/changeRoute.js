
function changeRoute(evt, routeNumber) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("directionstabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("routetablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    document.getElementById('routeTab' + routeNumber).style.display = "block";
    evt.currentTarget.className += " active";
    document.getElementById("changeroute-"+routeNumber).click()
}
