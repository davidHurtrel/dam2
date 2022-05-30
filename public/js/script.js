/* **************************************** CONTACT - FORM VALIDATION **************************************** */

    let contactForm = document.querySelector('#contactForm');

    contactForm.addEventListener('submit', function(e){

        // get form data
        let inputFirstName = document.querySelector('#contact_firstName');
        let inputLastName = document.querySelector('#contact_lastName');
        let inputEmail = document.querySelector('#contact_email');
        let inputSubject = document.querySelector('#contact_subject');
        let inputMessage = document.querySelector('#contact_message');

        // regular expressions
        let regexName = /^[a-zA-Z-\séèÉÈàÀòœæêÊâÂìïîÌÎÏñÑùûÙÛüÜöÖ]+$/;
        let regexEmail = /^[A-Za-z0-9+_.-]+@(?:[a-zA-Z0-9-]+\.)+[a-zA-Z]{2,6}$/;
        let regexPassword = /^(?=.*[A-Z])(?=.*[a-z])(?=.[0-9])(?=.*[#?!@%^&*-+]).{8,4096}$/

        // first name  verification
        if (inputFirstName.value.trim() == '') {
            e.preventDefault();
            inputFirstName.placeholder = 'Ce champ ne peut être vide';
            inputFirstName.style.border = '1px solid red';
        } else if (regexName.test(inputFirstName.value) == false) {
            e.preventDefault();
            alert('Le prénom ne doit comporter que des lettres, des tirets ou des espaces');
            inputFirstName.style.border = '1px solid red';
        }

        // last name verification
        if (inputLastName.value.trim() == '') {
            e.preventDefault();
            inputLastName.placeholder = 'Ce champ ne peut être vide';
            inputLastName.style.border = '1px solid red';
        } else if (regexName.test(inputLastName.value) == false) {
            e.preventDefault();
            alert('Le nom ne doit comporter que des lettres, des tirets ou des espaces');
            inputLastName.style.border = '1px solid red';
        }

        // email verification
        if (inputEmail.value.trim() == '') {
            e.preventDefault();
            inputEmail.placeholder = 'Ce champ ne peut être vide';
            inputEmail.style.border = '1px solid red';
        } else if (regexEmail.test(inputEmail.value) == false) {
            e.preventDefault();
            alert('L\'adresse email ne doit comporter que des lettres, des chiffresn, des tirets, des underscores (_) ou des espaces et comporter au moins un arobase (@)');
            inputEmail.style.border = '1px solid red';
        }

        // subject verification
        if (inputSubject.value.trim() == '') {
            e.preventDefault();
            inputSubject.style.border = '1px solid red';
        }

        // message verification
        if (inputMessage.value.trim() == '') {
            e.preventDefault();
            inputMessage.value = 'Ce champ ne peut être vide';
            inputMessage.style.border = '1px solid red';
        } else if (inputMessage.value.trim().length > 65535) {
            e.preventDefault();
            alert('Votre message est trop long');
            inputMessage.style.border = '1px solid red';
        } else if (inputMessage.value.trim().length < 100) {
            e.preventDefault();
            alert('Votre message est trop court, merci de préciser votre demande');
            inputMessage.style.border = '1px solid red';
        }
    });
