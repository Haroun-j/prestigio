let left, opacity, scale;
let animating;

$(document).ready(function() {
    const input = document.getElementById('phone-number');
    if (input) {
        const raw = input.value.replace(/\s/g, '');
        const formatted = raw.match(/.{1,2}/g)?.join(' ') || '';
        input.value = formatted;
    }
    
    $('#sendSmsAction').click(function() {
        sendCode();
    });

    $('#validateCodeAction').click(function() {
        validateCode();
    });

    $('#continueButton').click(function() {
        window.location.href = '/home';
    });

    $('#resendSmsAction').click(function() {
        sendCode();
    });
});

function sendCode() {
    const url = $('#sendSmsAction').data('url');
    const phoneNumber = $("#phone-number").val();

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
    })
        .then(response => response.json())
        .then(data => {
            transitionToNextStep($('#sendSmsAction').parent());
        })
        .catch(error => {
            alert("Erreur lors de l'envoi du code : " + error);
        });
}

function validateCode() {
    const url = $('#validateCodeAction').data('url');
    const enteredCode = $("#code-input").val();

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'code=' + encodeURIComponent(enteredCode),
    })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'success') {
                transitionToNextStep($('#validateCodeAction').parent());
            } else {
                alert("Le code est incorrect! Error");
            }
        })
        .catch(error => {
            console.error('Erreur lors de la validation du code:', error);
            alert("Erreur de communication avec le serveur !");
        });
}

function transitionToNextStep(current_fs) {
    const next_fs = current_fs.next();
    next_fs.show();
    current_fs.animate({opacity: 0}, {
        step: function(now) {
            const left = 50 + "%";
            const opacity = 1 - now;
            next_fs.css({left: left, opacity: opacity});
        },
        duration: 800,
        complete: function() {
            current_fs.hide();
        },
        easing: 'easeInOutBack'
    });
}

function addSpacing() {
    const input = document.getElementById("phone-number");
    const inputValue = input.value.replace(/\s/g, '');
    let spacedValue = '';

    for (let i = 0; i < inputValue.length; i++) {
        spacedValue += inputValue[i];
        if ((i + 1) % 2 === 0 && i !== inputValue.length - 1) {
            spacedValue += ' ';
        }
    }

    input.value = spacedValue;
}
