// auth.js

// Fonction pour envoyer une requête de connexion à l'API
function login(email, password) {
	const apiUrl = apiBaseURL + '/login'; // Remplacez par l'URL de votre API de connexion

	const data = {
		email: email,
		password: password,
	};

	fetch(apiUrl, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
		},
		body: JSON.stringify(data),
	})
		.then(response => response.json())
		.then(result => {
			if (result.error) {
				console.error('Login failed:', result.error);
			} else {
				// Enregistrez le token dans le stockage local (LocalStorage ou SessionStorage)
				localStorage.setItem('token', result.token);
				localStorage.setItem('username', result.username);
				console.log('Login success!');
				// Redirigez l'utilisateur vers une autre page ou effectuez d'autres actions
				window.location.href = 'index.php?page=home';
			}
		})
		.catch(error => {
			console.error('An error occurred:', error);
		});
}

document.addEventListener('DOMContentLoaded', () => {


	// Exemple d'utilisation du formulaire de connexion
	const loginForm = document.getElementById('login-form');

	loginForm.addEventListener('submit', e => {
		e.preventDefault(); // Empêche la soumission du formulaire

		// Récupère les valeurs du formulaire
		const email = document.getElementById('email').value;
		const password = document.getElementById('password').value;

		// Appelle la fonction de connexion
		login(email, password);
	});

});

// Fonction pour vérifier si l'utilisateur est connecté
function isLoggedIn() {
	const token = localStorage.getItem('token');
	return !!token;
}


// Fonction pour afficher ou masquer le bouton de déconnexion
function toggleLogoutButton() {
	const logoutButton = document.getElementById('logout-button');
	if (logoutButton) {
		logoutButton.style.display = isLoggedIn() ? 'block' : 'none';
	}
}

function checkLoginState() {
	const logoutButton = document.getElementById('logout-button');
	const loginLink = document.querySelector('a[href="index.php?page=login"]');
	const registerLink = document.querySelector('a[href="index.php?page=register"]');
	const usernameDisplay = document.getElementById('username-display');
  
	if (isLoggedIn()) {
	  // L'utilisateur est connecté
	  const username = localStorage.getItem('username');
	  if (logoutButton) logoutButton.style.display = 'block';
	  if (loginLink) loginLink.style.display = 'none';
	  if (registerLink) registerLink.style.display = 'none';
	  if (usernameDisplay) usernameDisplay.textContent = username;
	} else {
	  // L'utilisateur n'est pas connecté
	  if (logoutButton) logoutButton.style.display = 'none';
	  if (loginLink) loginLink.style.display = 'block';
	  if (registerLink) registerLink.style.display = 'block';
	  if (usernameDisplay) usernameDisplay.textContent = '';
	}
  }
  
  document.addEventListener('DOMContentLoaded', () => {
	checkLoginState();
	// Autres codes...
  });

document.addEventListener('DOMContentLoaded', () => {
	toggleLogoutButton();
});

// Fonction pour gérer la déconnexion
function logout() {
    localStorage.removeItem('token');
	localStorage.removeItem('username');
    console.log('Logout success!');
    // Redirection ou autres actions après la déconnexion
    checkLoginState(); // ou toggleLogoutButton() si vous utilisez cette fonction
}


document.addEventListener('DOMContentLoaded', () => {
    toggleLogoutButton();
    const logoutButton = document.getElementById('logout-button');
    if (logoutButton) {
        logoutButton.addEventListener('click', () => {
            logout();
        });
    }
});
