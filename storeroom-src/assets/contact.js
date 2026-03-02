document.addEventListener('DOMContentLoaded', function(e) {

    const _CONTACT_FORM_FAIL = document.getElementById( 'contact-form-fail' );
    document.getElementById( 'contact-form' ).onsubmit = function(e) {
        e.preventDefault();

        _CONTACT_FORM_FAIL.innerHTML = "";
        _CONTACT_FORM_FAIL.style.display = 'none';

        if( !e.target.name || !e.target.email || !e.target.message ){
            return;
        }

        let form = new FormData(e.target);
        form.append('action', 'submit_contact_form');

        let options = {
            "method": "POST",
            "body": form
        }

        fetch(e.target.action, options).then(r =>{
            r.json().then(data => {
                if( data.success === true ){
                    let currentURLNow = window.location.href;
                    let url = new URL(currentURLNow);
                    let resultValue = 'success';
                    url.searchParams.set('sent', resultValue);
                    window.location.href = url.toString();
                } else {
                    let error_string = "<ul>";
                    data.data.forEach(e => {
                        error_string += '<li>' + e + '</li>';
                    })
                    error_string += '</ul>';
                    _CONTACT_FORM_FAIL.innerHTML = "An error occurred during form submission." + error_string;
                    _CONTACT_FORM_FAIL.style.display = 'block';
                }
            }).catch(err => {
                _CONTACT_FORM_FAIL.innerHTML = "An error occurred during form submission." + err;
                _CONTACT_FORM_FAIL.style.display = 'block';
            });
        });
     }
})