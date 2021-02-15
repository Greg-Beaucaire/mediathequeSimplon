let mainDiv = document.querySelector('#divWrapperScroll');

$(mainDiv).scroll(function() {
    sessionStorage.scrollTop = $(this).scrollTop();
});
  
$(document).ready(function() {
if (sessionStorage.scrollTop != "undefined") {
    $(mainDiv).scrollTop(sessionStorage.scrollTop);
}
});


// 
// init
"use strict";
let searchBarEl = document.querySelector("#filmRecherche"); // la barre de recherche

// function
let searchQuerySelector = function (event) {
  let needle = event.target.value; // la valeur à chercher
  if (needle) {
    // éléments contenant un bout de cette classe
    for (const el of document.querySelectorAll(`#wrapperLeft > #film > div[class*="${needle}"]`)) {
      el.style.display = "grid";
    }
    // éléments ne contenant pas un bout de cette classe
    for (const el of document.querySelectorAll(`#wrapperLeft #film div:not([class*="${needle}"])`)) {
      el.style.display = "none";
    }
  } else {
    // if the search bar is empty
    for (const el of document.querySelectorAll("#wrapperLeft #film div")) {
      el.style.display = "grid";
    }
  }
}

// events
searchBarEl.addEventListener("input", searchQuerySelector);

