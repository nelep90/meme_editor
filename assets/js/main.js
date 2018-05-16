$(document).ready(function(){
  $(".owl-carousel").owlCarousel();
});

$('.owl-carousel').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    responsive:{
        0:{
            items:2,
            nav : true
        },
        600:{
            items:2,
            nav : true
        },
        1000:{
            items:4,
            nav : true
        }
    }
})

var txt1 = document.getElementById('txt-1');
var txt2 = document.getElementById('txt-2');
var data1 = document.getElementById('data-1');
var data2 = document.getElementById('data-2');



txt1.addEventListener('keydown', (event) => {

        data1.innerHTML = txt1.value;
  });

  txt2.addEventListener('keydown', (event) => {

    data2.innerHTML = txt2.value;

});


var color1 = document.getElementById('color-1');
var data1 = document.getElementById('data-1');

var defaultColor = "#121d2c";

window.addEventListener("load", startup, false);
function startup() {
  color1.value = defaultColor;
  color1.addEventListener("input", updateFirst, false);
  color1.select();
}
function updateFirst(event) {
  var data1 = document.getElementById('data-1');

  if (data1) {
    data1.style.color = event.target.value;
  }
}
