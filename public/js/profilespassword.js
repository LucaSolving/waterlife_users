
// ---------------------ProfoleValidation----------------------------//

(document.getElementById('field')
    .addEventListener('input', function(evt) {
        const field = evt.target,
        valido    = document.getElementById('fieldstar');
        validoResponsive    = document.getElementById('fieldstarResponsive');
        regex     =   /((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,64})/g;

                if (regex.test(field.value)) {
                    valido.style.color = "#000000";
                    valido.style.background = "#23d703";
                    valido.innerText = "Fuerte";
                    valido.style.width = "100%";

                    //responsive
                    validoResponsive.style.color = "#000000";
                    validoResponsive.style.background = "#23d703";
                    validoResponsive.innerText = "Fuerte";
                    validoResponsive.style.width = "100%";
                } else {
                    valido.style.color = "#000000";
                    valido.innerText =  "Débil";
                    valido.style.background = "#ff5310";
                    valido.style.width = "50%";

                    //responsive
                    validoResponsive.style.color = "#000000";
                    validoResponsive.innerText =  "Débil";
                    validoResponsive.style.background = "#ff5310";
                    validoResponsive.style.width = "50%";
                }
}));


