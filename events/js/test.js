//Testing that event address is in the right format and changing it if it is not
function testAddress(address1,name) {
    if(address1===null){
        if(name===null){
            return "";
        }
        else{
            return name + ", ";
        }
    }
    return address1 + ", ";
}

// Testing that picture logo exists in EventBright for an event, if not, display dummy picture
function testPicture(logo) {
    if(!logo){
        return '../images/abouta.jpeg'
    }
    if(!logo.url){
        return '../images/abouta.jpeg'
    }
    if(UrlExists(logo.url)){
        return logo.url
    }
    return '../images/abouta.jpeg'

}

// Testing whether url exists
function UrlExists(url)
{
    var http = new XMLHttpRequest();
    http.open('HEAD', url, false);
    http.send();
    //console.log(http.status!=404);
    return http.status!=404;
}