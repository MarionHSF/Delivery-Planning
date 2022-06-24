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

var email = document.getElementById("email2");
if(email){
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
}

function removeUploadFile(fileID){
    const divUploadFile = document.getElementById("uploadFile");
    const divUploadFileNumber = document.getElementById("uploadFile" + fileID);
    divUploadFile.removeChild(divUploadFileNumber);
    try{
        const httpRequest = new XMLHttpRequest();
        httpRequest.open('GET', 'http://localhost:8000/views/file/delete.php?fileId='+fileID, true);
        httpRequest.send();
    }catch (e){
        console.log(e.description);
    }
    console.log(divUploadFile);
    if(divUploadFile.childElementCount === 0){
        const buttonUploadFiles = document.getElementById("uploadFiles");
        buttonUploadFiles.setAttribute('required', 'required');
    }
}

const buttonUploadFiles = document.getElementById("uploadFiles");
if(buttonUploadFiles){
    buttonUploadFiles.addEventListener("change",function(e){
        const loading = document.getElementById("loading");
        loading.textContent = "";
        for (let file of buttonUploadFiles.files){
            loading.innerHTML = loading.innerHTML + '</br>' + file.name;
        }

        const errorFileFR = document.getElementById("errorFileFR");
        if(errorFileFR){
            errorFileFR.textContent = "";
        }

        const errorFileEN = document.getElementById("errorFileEN");
        if(errorFileEN){
            errorFileEN.textContent = "";
        }
    })
}

const submitForm = document.getElementById("submitForm");
if(submitForm){
    submitForm.addEventListener("click",function(e){
        if(buttonUploadFiles.files.length === 0){
            const errorFileFR = document.getElementById("errorFileFR");
            if(errorFileFR){
                errorFileFR.textContent = "Le serveur n'a pas reçu de fichier";
            }

            const errorFileEN = document.getElementById("errorFileEN");
            if(errorFileEN){
                errorFileEN.textContent = "File upload error (no file was uploaded)";
            }
        }
    })
}








