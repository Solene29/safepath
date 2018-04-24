
//Function getting events from EventBright
function getEvents(input,searchString) {
        
        //anon oauth token
    var token = 'BY5GBM6TSQPL3NTR5S6E';

        var $events1 = $("#"+input);
        
    var mainInput;

//This line puts the search string into the "search input box" that lives inside the search tab
    document.getElementById('seachInputMain').value = searchString;

//Check for event categories
    if(input != "eventSearch"){
        console.log("here");
        var cat;
        switch (input) {
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
                var s = "";
                if (input === "eventSearch"){
                    var s = "<b>Results for \"" + searchString + "\":</b>"
                }

                for(var i=0; i<res.events.length;i++) {
                    var event = res.events[i];
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
                    s += "</div>";
                }
                $events1.html(s);
            } else {
                $events1.html("<p>Sorry, there are no upcoming events.</p>");
            }
        });

    };





    