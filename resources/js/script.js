document.addEventListener("DOMContentLoaded", function () {
    const sidebar1 = document.getElementById('sidebar');
    const windowWidth = window.innerWidth;

    // Display or hide the sidebar based on the screen width
    if (windowWidth <= 767) {
        sidebar1.style.display = 'none';
    } else {
        sidebar1.style.display = 'block';
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

    sidebar.classList.toggle('closed');
    main.classList.toggle('closed');
}


