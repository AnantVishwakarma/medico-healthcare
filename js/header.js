/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
const btnProfile = document.querySelector("#btn-profile");
btnProfile.addEventListener("click", function(event) {    
    document.querySelector("#profile-dropdown").classList.toggle("show-dropdown");
});


// Close the dropdown if the user clicks outside of it

window.onclick = function(event) 
{
    if (!event.target.matches('#btn-profile') && !event.target.matches('#btn-profile i'))
    {
        const dropdowns = document.getElementsByClassName("dropdown-content");
        for (let i = 0; i < dropdowns.length; i++) 
        {
            let openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show-dropdown')) 
            {
                openDropdown.classList.remove('show-dropdown');
            }
        }
    }
}