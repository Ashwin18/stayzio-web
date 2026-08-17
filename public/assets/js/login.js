function switchTab(tab) {
    document.getElementById('tab-password').classList.toggle('active', tab === 'password');
    document.getElementById('tab-otp').classList.toggle('active', tab === 'otp');
    document.getElementById('fields-password').classList.toggle('show', tab === 'password');
    document.getElementById('fields-otp').classList.toggle('show', tab === 'otp');
}


function switchTab(tab) {
    document.getElementById('tab-password').classList.toggle('active', tab === 'password');
    document.getElementById('tab-otp').classList.toggle('active', tab === 'otp');
    document.getElementById('fields-password').classList.toggle('show', tab === 'password');
    document.getElementById('fields-otp').classList.toggle('show', tab === 'otp');
}

function showSignup() {
    document.getElementById('login-card').style.display = 'none';
    document.getElementById('signup-card').style.display = 'block';
}

function showLogin() {
    document.getElementById('signup-card').style.display = 'none';
    document.getElementById('login-card').style.display = 'block';
}