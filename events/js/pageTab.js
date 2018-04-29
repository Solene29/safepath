
function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

function openPageTab(evt, catName, pageNum) {
    var i, tabcontent, tablinks;
    //console.log(catName + "Pages");
    tabcontent = document.getElementsByClassName(catName + "Pages");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    tablinks = document.getElementsByClassName("pageTabLinks "+catName);
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    console.log(catName + "Page" + pageNum);
    document.getElementById(catName + "Page" + pageNum).style.display = "block";
    //evt.currentTarget.className += " active";
    console.log("pageLink"+ catName+pageNum+"Top")
    document.getElementById("pageLink"+ catName + pageNum + "Top").className += " active";
    document.getElementById("pageLink"+ catName + pageNum + "Bottom").className += " active";

    console.log(evt.currentTarget.id);
    if(evt.currentTarget.id.slice(-6) !="Top") {
        topFunction();
    }
}

function increase(evt,catName) {
    var pageNum = parseInt(document.getElementsByClassName("pageTabLinks "+ catName +" active")[1].innerHTML);

    if(pageNum < pageCounts[pageNames.indexOf(catName)]){
       newPageNum = pageNum+1;
       var newPageNum
       openPageTab(evt,catName,newPageNum);
    }
}

function decrease(evt,catName) {
    var pageNum = parseInt(document.getElementsByClassName("pageTabLinks "+ catName +" active")[1].innerHTML);

    if(pageNum > 1){
       newPageNum = pageNum-1;
       var newPageNum
       openPageTab(evt,catName,newPageNum);
    }
}