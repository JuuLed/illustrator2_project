function displayError(formId, message) {
    const errorElement = document.querySelector(`#${formId}-error`);
    errorElement.textContent = message;
}

function register(username, email, password) {
	const apiUrl = apiBaseURL + '/register'; // Remplacez par l'URL de votre API d'inscription

	const data = {
		username: username,
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
				console.error('Registration failed:', result.error);
				displayError('register', 'Cette adresse mail existe déja.');
			} else {
				console.log('Registration success!');
				// Vous pouvez rediriger l'utilisateur vers la page de connexion, ou faire autre chose
				window.location.href = 'index.php?page=login';
			}
		})
		.catch(error => {
			console.error('An error occurred:', error);
		});
}

// Fonction pour envoyer une requête de connexion à l'API
function login(email, password) {
	const apiUrl = apiBaseURL + '/login';

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
		credentials: 'include',  // les cookies sont inclus avec la requête
	})
		.then(response => response.json())
		.then(result => {
			if (result.error) {
				console.error('Login failed:', result.error);
				displayError('login', "Adresse mail ou mot de passe invalide.");
			} else {
				// Enregistrez le token dans le stockage local (LocalStorage ou SessionStorage)
				// localStorage.setItem('token', result.token);
				// localStorage.setItem('username', result.username);
				console.log('Login success!');
				// Redirigez l'utilisateur vers une autre page ou effectuez d'autres actions
				window.location.href = 'index.php?page=symbols';
			}
		})
		.catch(error => {
			console.error('An error occurred:', error);
		});
}

// Fonction pour vérifier si l'utilisateur est connecté
function isLoggedIn() {
    const apiUrl = apiBaseURL + '/auth/status';
    return fetch(apiUrl, {
        method: 'GET',
        credentials: 'include',  // les cookies sont inclus avec la requête
    })
    .then(response => response.json())
    .then(result => {
        if (result.error) {
            console.error('Failed to check login status:', result.error);
            return { isLoggedIn: false, username: null };
        } else {
			return { isLoggedIn: result.status === 'connected', username: result.username };
        }
    })
    .catch(error => {
        console.error('An error occurred:', error);
        return { isLoggedIn: false, username: null };
    });
}

isLoggedIn().then(isLoggedInStatus => {
    if (isLoggedInStatus.isLoggedIn) {
        console.log('User is logged in');
    } else {
        console.log('User is not logged in');
    }
});




// Fonction pour gérer la déconnexion
function logout() {
    const apiUrl = apiBaseURL + '/logout';
    fetch(apiUrl, {
        method: 'GET',
        credentials: 'include', // les cookies sont inclus avec la requête
    })
    .then(response => response.json())
    .then(result => {
        if (result.error) {
            console.error('Logout failed:', result.error);
        } else {
            console.log('Logout success!');
            // Mettre à jour l'état de connexion
            checkLoginState();
            // Redirection ou autres actions après la déconnexion
            window.location.href = 'index.php?page=login';
        }
    })
    .catch(error => {
        console.error('An error occurred:', error);
    });
}


async function checkLoginState() {
    const logoutButton = document.getElementById('logout-button');
    const loginLink = document.querySelector('a[href="index.php?page=login"]');
    const registerLink = document.querySelector('a[href="index.php?page=register"]');
    const usernameDisplay = document.getElementById('username-display');
    const welcomeBlock = document.querySelector('.welcome');

    const loginStatus = await isLoggedIn();

    if (loginStatus.isLoggedIn) {
        // L'utilisateur est connecté
        const username = loginStatus.username; // suppose que le serveur renvoie le nom d'utilisateur
        if (logoutButton) logoutButton.style.display = 'block';
        if (loginLink) loginLink.style.display = 'none';
        if (registerLink) registerLink.style.display = 'none';
        if (usernameDisplay) usernameDisplay.textContent = username;
        if (welcomeBlock) welcomeBlock.style.display = 'block';
    } else {
        // L'utilisateur n'est pas connecté
        if (logoutButton) logoutButton.style.display = 'none';
        if (loginLink) loginLink.style.display = 'block';
        if (registerLink) registerLink.style.display = 'block';
        if (usernameDisplay) usernameDisplay.textContent = '';
        if (welcomeBlock) welcomeBlock.style.display = 'none';
    }
}



// Attachement des gestionnaires d'événements lors du chargement du DOM
document.addEventListener('DOMContentLoaded', () => {
	const loginForm = document.getElementById('login-form');
	const registerForm = document.getElementById('register-form');
	const logoutButton = document.getElementById('logout-button');

	if (loginForm) {
		loginForm.addEventListener('submit', e => {
			e.preventDefault(); // Empêche la soumission du formulaire

			// Récupère les valeurs du formulaire
			const email = document.getElementById('email').value;
			const password = document.getElementById('password').value;

			// Appelle la fonction de connexion
			login(email, password);
		});
	}

	if (registerForm) {
		registerForm.addEventListener('submit', e => {
			e.preventDefault(); // Empêche la soumission du formulaire

			// Récupère les valeurs du formulaire
			const username = document.getElementById('username').value;
			const email = document.getElementById('email').value;
			const password = document.getElementById('password').value;

			// Appelle la fonction d'inscription
			register(username, email, password);
		});
	}

	if (logoutButton) {
		logoutButton.addEventListener('click', () => {
			logout();
		});
	}
	
	// Obtenir l'élément .content
	const contentElement = document.querySelector('.content');

	// Vérifiez si vous êtes sur la page 'login' ou 'register'
	const currentPage = window.location.href.split('page=')[1];
	const allowedPages = ['login', 'register'];

	isLoggedIn().then(loginStatus => {
		if (allowedPages.includes(currentPage) || loginStatus.isLoggedIn) {
			// Si vous êtes sur la page 'login', 'register', ou si l'utilisateur est connecté, alors rendre le contenu visible
			contentElement.style.visibility = 'visible';
		} else {
			// Sinon, cacher le contenu
			contentElement.style.visibility = 'hidden';

			// Gérer la redirection pour les utilisateurs non connectés
			if (!allowedPages.includes(currentPage)) {
				window.location.href = 'index.php?page=login';
			}
		}

		// Verifier l'etat de la connexion à chaque chargement de la page
		checkLoginState();
	});
});
