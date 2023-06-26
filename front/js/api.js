
//_____________________________ URL de base de l'API _____________________________________
//* Chemin de base de votre API REST
//! _____ docker : _____
// var apiBaseURL = 'http://localhost:8000/index.php';
//! _____ local : _____
var apiBaseURL = 'http://www.sitetest.local/illustrator2_project/back/index.php';

// Fonction pour effectuer une requête Ajax GET à l'API 
function apiGet(url, callback) {
	var fullURL = apiBaseURL + url;
	$.ajax({
		url: fullURL,
		type: 'GET',
		success: function (data) {
			callback(data);
		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.log("Erreur: " + textStatus, errorThrown);
		}
	});
}
