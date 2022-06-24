const toggler = document.getElementById('toggler');
const togglerContent = document.getElementById('toggler-content');

if(toggler){
    toggler.addEventListener("click",function(e){
        if(togglerContent.style.display == "none"){
            togglerContent.style.display ='flex';
            togglerContent.style.flexDirection ='column';
        }else{
            togglerContent.style.display='none';
        }
    })
}
