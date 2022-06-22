function addOrderInput(){
    const divOrder = document.getElementById("divOrder");
    let i = 0;
    if(divOrder.lastElementChild.nodeName == "P"){
        i = divOrder.lastElementChild.previousElementSibling.id.substring(8,15);
    }else{
        i = divOrder.lastElementChild.id.substring(8,15);
    }
    i++;

    const divOrderNumber = document.createElement("div");
    divOrderNumber.id = "divOrder" + i;
    divOrderNumber.className = "col-sm-3 mb-2 d-flex";

    const input = document.createElement("input");
    input.id = "inputOrder" + i;
    input.type = "text";
    input.className = "form-control";
    input.name = "order[]";

    const removeButton = document.createElement("a");
    removeButton.id = "buttonOrder" + i;
    removeButton.className = "btn btn-primary form remove";
    removeButton.textContent = "-";
    removeButton.addEventListener("click",function(e){
        const orderNumber = e.target.id.substring(11,15);
        const divOrderNumber = document.getElementById("divOrder" + orderNumber);
        divOrder.removeChild(divOrderNumber);
    })

    divOrderNumber.appendChild(input);
    divOrderNumber.appendChild(removeButton);
    divOrder.appendChild(divOrderNumber);
}

function removeOrderInput(i){
    const divOrder = document.getElementById("divOrder");
    const divOrderNumber = document.getElementById("divOrder" + i);
    divOrder.removeChild(divOrderNumber);
}

var email = document.getElementById("email");
email.addEventListener("change",function(e){
    try{
        const httpRequest = new XMLHttpRequest();
        httpRequest.onreadystatechange = function() {
            if (httpRequest.readyState === XMLHttpRequest.DONE && httpRequest.status === 200) {
                var phone = document.getElementById("phone");
                phone.value = httpRequest.responseText;
            }
        };
        httpRequest.open('GET', 'http://localhost:8000/views/user/userPhone.php?email='+email.value, true);
        httpRequest.send();
    }catch (e){
        console.log(e.description);
    }
})





