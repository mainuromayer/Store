document.addEventListener('DOMContentLoaded', function (){
    console.log('loaded');
    let links = document.querySelectorAll(".delete");
    for (let i=1; i<links.length; i++){
        links[i].addEventListener('click', function (e){
            if (!confirm("Are you sure?")){
                e.preventDefault();
            }
        })
    }
});