
var pageNames = ["eventArts","eventFood","eventMusic","eventSports","eventOther","eventSearch"];
var pageCounts = [0,0,0,0,0,0];
var pagesLoaded = [0,0,0,0,0,0];

console.log(pageNames.indexOf("eventFood"));
//Function getting events from EventBright
function getEvents(catName,searchString) {
        
        //anon oauth token
    var token = 'BY5GBM6TSQPL3NTR5S6E';

        var $events1 = $("#"+catName);
        
    var mainInput;

//This line puts the search string into the "search input box" that lives inside the search tab
    document.getElementById('seachInputMain').value = searchString;

//Check for event categories
    if(catName != "eventSearch"){
        console.log("here");
        var cat;
        switch (catName) {
           case "eventArts":
                cat = 105;
                break;
            case "eventFood":
                cat = 110;
                break;
            case "eventMusic":
                cat = 103;
                break;
            case "eventOther":
                cat = 107;
                break;
            case "eventSports":
                cat = 108;
                break;
            }
        mainInput = "&sort_by=date&categories="+cat;
    }
    else{
    mainInput = "&q="+searchString;
    }

//Loading events from eventBright according to below endpoints:
        $events1.html("<i>Loading events, please stand by...</i>");
        $.get('https://www.eventbriteapi.com/v3/events/search/?token='+token + mainInput +'&location.address=Melbourne&location.within=3km&expand=venue', function(res) {
            
            if(res.events.length) {
                var eventsPerPage = 8;
                var numOfPages = Math.ceil(res.events.length/eventsPerPage);
                pageCounts[pageNames.indexOf(catName)] = numOfPages;
                console.log(pageCounts);
                var lastPageEvents = (res.events.length % eventsPerPage);
                var s = "";
                if (catName === "eventSearch"){
                    var s = "<b>Results for \"" + searchString + "\":</b>"
                }

                s+=  "<div align=\"center\">"
                s+=  "<div class=\"pageTab\", align=\"center\">";
                s+=  "<button class=\"pageTabLinks\" id=\"pageIncrease" + catName + pageNum + "Top\" onclick=\"decrease(event, \'" + catName + "\' )\">\<</button>";
                for(var pageNum=1; pageNum<=numOfPages;pageNum++) {
                    s+=  "<button class=\"pageTabLinks "+ catName +"\" id=\"pageLink" + catName + pageNum + "Top\" onclick=\"openPageTab(event, \'" + catName + "\',"  + pageNum + ")\">" + pageNum + "</button>";
                }
                s+=  "<button class=\"pageTabLinks\" id=\"pageDecrease" + catName + pageNum + "Top\" onclick=\"increase(event, \'" + catName + "\' )\">\></button>"
                s+=  "</div></div>"

                //This "for loop" creates the pages of the pagination.
                for (var pageNum=1; pageNum<=numOfPages; pageNum++) {
                    //This corrects for the number of elements in the last page.
                    var totalEventsInPage = eventsPerPage;
                    if (pageNum === numOfPages){
                        totalEventsInPage = lastPageEvents;
                    }

                    s += "<div class=\"" + catName + "Pages\" id=\"" + catName + "Page" + pageNum + "\" style=\"display:none;\">";
                    //This "for loop" creates the event elements within the page with page number "pageNum".
                    for (var eventIndexInPage=1; eventIndexInPage<=totalEventsInPage; eventIndexInPage++) {
                        var allEventIndex = (pageNum-1)*eventsPerPage+eventIndexInPage-1;
                        //console.log(allEventIndex);
                        var event = res.events[allEventIndex];
                        var eventTime = moment(event.start.local).format('D/M/YYYY h:mm A');
                        //console.dir(event);
                        s += "<div class=\"accordion\" id=\"accordion"+ event.id  + "\">"; 
                        s += "<table style=\"width:100%\"><tr class=\"mobileOnlyRow\"><td><img src=\"" + testPicture(event.logo) +"\" style=\"width:200px;\"></td></tr><tr>"
                        s += "<td class=\"desktopOnlyCell\"><img src=\"" + testPicture(event.logo) +"\" style=\"width:200px;padding-right:15px\"> </td>"
                        s += "<td style=\"width: 100%\"; align=\"left\"><div class='eventList'>";
                        s += "<h2>" + event.name.text + "</h2>";
                        s += "<table style=\"width:90%\"> <tr> <td>";
                        s += "<p><b>Location: "+ testAddress(event.venue.address.address_1,event.venue.name) + event.venue.address.city+"</b><br/>";
                        s += "<b>Date/Time: " + eventTime + "</b></p> </td>";
                        s += "<td align=\"right\" class=\"desktopOnlyCell\"><a href =\"eventpage.php?" + event.id + "\" style=\"color:black\"><div style=\"padding: 5px;cursor: pointer;background-color: #FEB728; border: 1px; box-shadow: none; border-radius: 0px; width:125px; text-align: center;\">Get there Safely!</div></a>"
                        s += "</td>";
                        s += "<td align=\"right\" class=\"mobileOnlyCell\"><a href =\"eventpage.php?" + event.id + "\" style=\"color:black\"><div style=\"padding: 5px;cursor: pointer;background-color: #FEB728; border: 1px; box-shadow: none; border-radius: 0px; width:75px; text-align: center;\">Get there Safely!</div></a>"
                        s += "</td>";
                        s += "</tr></table></td></tr></table>";
                        s += "<table style=\"width:90%\"><tr><td align=\"center\"><button onclick=\"toggleAccordion('" + event.id + "')\" style=\"border: 0; box-shadow:none; background-color:transparent;\" >";
                        s += "<img id=\"downArrow" + event.id  + "\" src=\"images/downarrow.png\" align=\"right\" alt=\"Expand\" style=\"width:30px; border: 0; box-shadow: none; border-radius: 0px; align: center\">";
                        s += "<img id=\"upArrow"+ event.id  + "\" src=\"images/uparrow.png\" align=\"right\" alt=\"Contract\" style=\"display:none; width:30px; border: 0; box-shadow: none; border-radius: 0px; align: center\">";
                        s += " </button> ";
                        s += "</td></tr></table></div>"
                        s += "<div id=\"panel" + event.id + "\" style=\"display:none;white-space:pre-wrap;\">";
                        s += "<div>" + event.description.text + "<a href='" + event.url + "' target=\"_blank\" style=\"color:orange;font-weight:700;\"> More details here!</a></div>";
                        s += "</div>";

                        
                    }
                    s += "</div>";
                    

                }

                s+=  "<div align=\"center\">"
                s+=  "<div class=\"pageTab\", align=\"center\">";
                s+=  "<button class=\"pageTabLinks\" id=\"pageIncrease" + catName + pageNum + "Bottom\" onclick=\"decrease(event, \'" + catName + "\' )\">\<</button>";
                for(var pageNum=1; pageNum<=numOfPages;pageNum++) {
                    s+=  "<button class=\"pageTabLinks "+ catName +"\" id=\"pageLink" + catName + pageNum + "Bottom\" onclick=\"openPageTab(event, \'" + catName + "\',"  + pageNum + ")\">" + pageNum + "</button>";
                }
                s+=  "<button class=\"pageTabLinks\" id=\"pageIncrease" + catName + pageNum + "Bottom\" onclick=\"increase(event, \'" + catName + "\' )\">\></button>";
                s+=  "</div></div>"
                $events1.html(s);
                document.getElementById("pageLink" + catName + "1Top").click();
                pagesLoaded[pageNames.indexOf(catName)] = 1;
                console.log(pagesLoaded);
            } else {
                $events1.html("<p>Sorry, there are no upcoming events.</p>");
            }
        });


    };







    