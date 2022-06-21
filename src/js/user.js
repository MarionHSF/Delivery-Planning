const upcomingAppointementToogler = document.getElementById('upcoming-appointement-toogler');
const upcomingAppointementTooglerContent = document.getElementById('upcoming-appointement-toogler-content');

upcomingAppointementToogler.addEventListener("click",function(e){
    if(upcomingAppointementTooglerContent.style.display == "none"){
        upcomingAppointementTooglerContent.style.display ='block';
    }else{
        upcomingAppointementTooglerContent.style.display='none';
    }
})

const pastAppointementToogler = document.getElementById('past-appointement-toogler');
const pastAppointementTooglerContent = document.getElementById('past-appointement-toogler-content');

pastAppointementToogler.addEventListener("click",function(e){
    if(pastAppointementTooglerContent.style.display == "none"){
        pastAppointementTooglerContent.style.display ='block';
    }else{
        pastAppointementTooglerContent.style.display='none';
    }
})