// Changing tabs and getting the event data
function openCategory(evt, catName, searchString) {
    // Declare all variables
    var i, tabcontent, tablinks;
    
    if(catName==="eventSearch" && searchString===""){

    }
    else{
        if(pagesLoaded[pageNames.indexOf(catName)] === 0){
            getEvents(catName, searchString)
        }
    }
    

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the link that opened the tab
    document.getElementById(catName + 'Tab').style.display = "block";

    console.log(searchString);
    console.log(searchString.length);

    if(catName!="eventSearch"){  
    evt.currentTarget.className += " active";
    }  
    else{
        document.getElementById("searchOpen").className += " active";
    }
}
