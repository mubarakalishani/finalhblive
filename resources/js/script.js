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


