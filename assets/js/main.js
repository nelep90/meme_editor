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