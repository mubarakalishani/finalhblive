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

    sidebar.classList.toggle('closed');
    main.classList.toggle('closed');
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