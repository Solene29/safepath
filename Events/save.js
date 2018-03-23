$(document).ready(function listEvents() {
        
        //anon oauth token
        var token = 'BY5GBM6TSQPL3NTR5S6E';

        var $events = $("#events");
        
        $events.html("<i>Loading events, please stand by...</i>");
        $.get('https://www.eventbriteapi.com/v3/events/search/?token='+token+'&location.address=melbourne,australia&sort_by=date&categories=103&expand=venue', 
            function(res) {
            if(res.events.length) {
                var s = "";
                for(var i=0;i<res.events.length;i++) {
                    var event = res.events[i];
                    var eventTime = moment(event.start.local).format('DD/MM/YYYY h:mm A');
                    console.dir(event);
                    {
                    s += "<div class='eventList'>";
                    s += "<h2><a href='" + event.url + "'>" + event.name.text + "</a></h2>";
                    s += "<b>- " + event.logo_id + "<br/>";
                    s += "<p><b>Location: " + event.venue.address.address_1 + "</b>";
                    s += "<b>, " + event.venue.address.city + "</b>";
                    s += "<b>, " + event.venue.address.region + "</b>";
                    s += "<b> - " + event.venue.address.country + "<br/>";
                    s += "<b>Date/Time: " + eventTime + "</b></p>";
                    s += "<p>" + event.description.text + "</p>";
                    s += "</div>";
                    }
                    
                }
                $events.html(s);
            } else {
                $events.html("<p>Sorry, there are no upcoming events.</p>");
            }
        });
        

        
    });