document.addEventListener("DOMContentLoaded", function () {
    const windowWidth = window.innerWidth;

    // Display or hide the sidebar based on the screen width
    if (windowWidth <= 767) {
        document.querySelector("#sidebar").classList.toggle("collapsed");
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main');
        const footer = document.getElementById('footer');

        sidebar.classList.toggle('closed');
        main.classList.toggle('closed');
        footer.classList.toggle('closed');
    }
});


const toggler = document.querySelector(".btn");
toggler.addEventListener("click",function(){
    document.querySelector("#sidebar").classList.toggle("collapsed");
});
// Jobs-area-clickable-kaliya
function navigateToPage(pageUrl) {
        window.location.href = pageUrl;
    }


function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const main = document.getElementById('main');
    const footer = document.getElementById('footer');

    sidebar.classList.toggle('closed');
    main.classList.toggle('closed');
    footer.classList.toggle('closed');
}


var splide1 = new Splide('.splide1', {
                type: 'loop',
                perPage: 3,
                rewind: true,
                breakpoints: {
                    640: {
                        perPage: 2,
                        gap: '.7rem',
                        height: '12rem',
                    },
                    480: {
                        perPage: 1,
                        gap: '.7rem',
                        height: '12rem',
                    },
                },
            });
            splide1.mount();



var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/65b3c6590ff6374032c53c55/1hl33tkdj';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();