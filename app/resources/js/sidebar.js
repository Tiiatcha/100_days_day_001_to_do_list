(()=>
{
    const toggle_menu_button = document.querySelector('#side_menu_btn');
    const side_menu = document.querySelector('.wrapper')
    toggle_menu_button.addEventListener('click',e=>{
        side_menu.classList.toggle('sidebar__hidden');
    })

   
})();