// add and remove order input
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

// automatic filling of the phone when the email is selected
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

// deletes the attachments in edit.event and puts a required on the input if all attachments are deleted
function removeUploadFile(fileID){
    const divUploadFile = document.getElementById("uploadFile");
    const divUploadFileNumber = document.getElementById("uploadFile" + fileID);
    divUploadFile.removeChild(divUploadFileNumber);
    try{
        const httpRequest = new XMLHttpRequest();
        httpRequest.onreadystatechange = function() {
            if (httpRequest.readyState === XMLHttpRequest.DONE && httpRequest.status === 200) {
                if(httpRequest.responseText == "errorDB"){
                    const errorDBFR = document.getElementById("errorDBFR");
                    if(errorDBFR){
                        errorDBFR.textContent = "Une erreur est survenue, veuillez contacter le service technique.";
                    }

                    const errorDBEN = document.getElementById("errorDBEN");
                    if(errorDBEN){
                        errorDBEN.textContent = "An error has occurred, please contact technical support.";
                    }
                }
            }
        };
        httpRequest.open('GET', 'http://localhost:8000/views/file/delete.php?fileId='+fileID, true);
        httpRequest.send();
    }catch (e){
        console.log(e.description);
    }
    if(divUploadFile.childElementCount === 0){
        const buttonUploadFiles = document.getElementById("uploadFiles");
        buttonUploadFiles.setAttribute('required', 'required');
    }
}

// generate or not errors when submitting attachments
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
                errorFileFR.textContent = "Le serveur n'a pas re??u de fichier";
            }

            const errorFileEN = document.getElementById("errorFileEN");
            if(errorFileEN){
                errorFileEN.textContent = "File upload error (no file was uploaded)";
            }
        }
    })
}

// display the requested week and hide the others
function displayWeek(weekNumber){
    const tooglerWeek = document.getElementById('week'+weekNumber);
    tooglerWeek.style.display ='';

    for (let week of document.getElementById('weekDiv').children){
       if(week.id.substr(4,2) != weekNumber){
          const tooglerWeek = document.getElementById('week'+week.id.substr(4,2));
           tooglerWeek.style.display ='none';
       }
    }
}

// displays or non the text of the add appointment button on mouseover and mouseout
function displayTextAddButton(){
    const textAddButton = document.getElementById('textAddButton');
    textAddButton.style.display ='';
}

function undisplayTextAddButton(){
    const textAddButton = document.getElementById('textAddButton');
    textAddButton.style.display ='none';
}

// prevents the selection of weekends in the date picker
// Checks if the limit of floor meters has been reached on the day
const picker = document.getElementById('date');
if(picker){
    picker.addEventListener('input', function(e){
        var day = new Date(this.value).getUTCDay();
        if([6,0].includes(day)){
            this.value = '';
            const errorPickerFR = document.getElementById("errorPickerFR");
            if(errorPickerFR){
                errorPickerFR.textContent = "Les week-ends ne sont pas autoris??s.";
            }
            const errorPickerEN = document.getElementById("errorPickerEN");
            if(errorPickerEN){
                errorPickerEN.textContent = "Weekends not allowed";
            }
        }

        try{
            const httpRequest = new XMLHttpRequest();
            httpRequest.onreadystatechange = function() {
                if (httpRequest.readyState === XMLHttpRequest.DONE && httpRequest.status === 200) {
                    if(httpRequest.responseText == "limitReached"){
                        const startAlert = document.getElementById("startAlert");
                        startAlert.style.display ='none';

                        const start = document.getElementById("start");
                        start.style.display ='none';

                        const scheduleFR = document.getElementById("scheduleFR");
                        if(scheduleFR){
                            scheduleFR.textContent = "Aucun cr??neau disponible sur cette date.";
                        }

                        const scheduleEN = document.getElementById("scheduleEN");
                        if(scheduleEN){
                            scheduleEN.textContent = "No schedule available on this date.";
                        }
                        console.log(startAlert);
                        console.log(start);
                        console.log(scheduleFR);
                        console.log(scheduleEN);

                    }else{
                        const startAlert = document.getElementById("startAlert");
                        startAlert.style.display ='none';

                        const start = document.getElementById("start");
                        start.style.display ='';

                        const scheduleFR = document.getElementById("scheduleFR");
                        if(scheduleFR){
                            scheduleFR.textContent = "";
                        }

                        const scheduleEN = document.getElementById("scheduleEN");
                        if(scheduleEN){
                            scheduleEN.textContent = "";
                        }
                        console.log(startAlert);
                        console.log(start);
                        console.log(scheduleFR);
                        console.log(scheduleEN);
                    }
                }
            };
            httpRequest.open('GET', 'http://localhost:8000/views/day/dayFloorMeterLimit.php?date='+this.value, true);
            httpRequest.send();
        }catch (e){
            console.log(e.description);
        }





    });
}

// display or not the inputs according to the selection of standard pallets or other format
const standardFormat = document.getElementById('standardFormat');
const otherFormat = document.getElementById('otherFormat');

const standardFormatDiv = document.getElementById('standardFormatDiv');
const otherFormatDiv = document.getElementById('otherFormatDiv');

const palletNumberStandardFormat = document.getElementById('palletNumberStandardFormat');
const palletNumberOtherFormat = document.getElementById('palletNumberOtherFormat');
const floorNumber = document.getElementById('floor_meter');

function otherFormatFunction(){
    standardFormatDiv.style.display ='none';
    otherFormatDiv.style.display ='';
    palletNumberStandardFormat.disabled = true;
    palletNumberOtherFormat.disabled = false;
    floorNumber.disabled = false;
}

function standartFormatFunction(){
    standardFormatDiv.style.display ='';
    otherFormatDiv.style.display ='none';
    palletNumberStandardFormat.disabled = false;
    palletNumberOtherFormat.disabled = true;
    floorNumber.disabled = true;
}

if(standardFormat && otherFormat && standardFormatDiv && otherFormatDiv && palletNumberStandardFormat && palletNumberOtherFormat && floorNumber){
    standardFormat.addEventListener('change', function(){
        if(this.checked){
            standartFormatFunction();
        }else{
            otherFormatFunction();
        }
    })
    otherFormat.addEventListener('change', function(){
        if(this.checked){
            otherFormatFunction();
        }else{
            standartFormatFunction();
        }
    })
}

//



