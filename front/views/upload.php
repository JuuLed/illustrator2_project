<style>
	#drop_zone {
		margin: 30px;
		padding: 20px;
		border: 2px dashed #ccc;
		width: 50%;
		height: 200px;
		text-align: center;
		position: relative;
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		overflow: hidden;
	}

	#drop_zone i {
		font-size: 40px;
		margin-bottom: 10px;
		padding: 2vh;
	}

	#drop_zone.hover {
		border-color: #888;
	}

	/* Style pour les barres de progression */
	.upload-progress {
		margin-top: 10px;
		padding: 3px;
		border: 1px solid #ccc;
		text-align: center;
		color: #000;
	}

	/* Style pour les messages d'erreur */
	.upload-error {
		color: red;
	}

	/* Style pour les messages de succès */
	.upload-success {
		color: green;
	}


	.content-upload {
		display: flex;
		width: 60%;
	}

	.upload-progress {
  display: flex;
  flex-wrap: wrap;
  /* width: 50%; */
}

.upload-progress > div:nth-child(1) {
  flex: 0 0 33%;
  display: flex;
}

.upload-progress .upload-error {
  flex-basis: 100%;
  margin-top: 10px;
}

</style>



<div class="content-upload">



	<div id="drop_zone">
		<div>
			Glissez et déposez le fichier ici
		</div>
		<i class="fa-solid fa-download"></i>
	</div>
	<input type="file" id="uploadedFile" style="display:none;" multiple>

	<div id="uploadProgress"></div>


</div>


<script>
	// Attachez un gestionnaire d'événements 'change' au champ de fichier
	var fileInput = document.getElementById('uploadedFile');
	var dropZone = document.getElementById('drop_zone');
	var uploadProgress = document.getElementById('uploadProgress');

	dropZone.ondragover = function () {
		this.className = 'hover';
		return false;
	};

	dropZone.ondragend = function () {
		this.className = '';
		return false;
	};

	dropZone.ondrop = function (e) {
		this.className = '';
		e.preventDefault();

		fileInput.files = e.dataTransfer.files;

		//  gérer plusieurs fichiers
		for (var i = 0; i < fileInput.files.length; i++) {
			handleFile(fileInput.files[i])
				.then(uploadFile)
				.catch(function (errorMessage) {
					// Si le fichier n'est pas correct, affichez le message d'erreur
					var errorElement = document.createElement('div');
					errorElement.innerText = 'Erreur : ' + errorMessage;
					uploadProgress.appendChild(errorElement);
				});
		}
	};

	function uploadFile(file) {
		var url = apiBaseURL + '/symbols';
		var xhr = new XMLHttpRequest();
		var formData = new FormData();

		// Créez un nouvel élément DOM pour afficher la progression de cet upload
		var progressBarContainer = document.createElement('div');
		progressBarContainer.className = 'upload-progress';

		var progressBar = document.createElement('div');
		progressBar.innerText = file.name + ' : 0% uploaded';
		progressBarContainer.appendChild(progressBar);

		// Ajoutez un champ de saisie pour renommer le fichier
		var renameInput = document.createElement('input');
		renameInput.type = 'text';
		renameInput.placeholder = 'Nom du symbole';
		renameInput.required = true;  // Rend le champ obligatoire
		renameInput.style.marginTop = '10px'; // Ajoutez du style à votre champ de saisie
		renameInput.style.padding = '5px';
		renameInput.style.border = '1px solid #ccc';
		renameInput.style.borderRadius = '5px';
		progressBarContainer.appendChild(renameInput);

		// Ajoutez un bouton de validation
		var submitButton = document.createElement('button');
		submitButton.innerText = 'Valider';
		submitButton.style.marginTop = '10px'; // Ajoutez du style à votre bouton
		submitButton.style.padding = '5px 10px';
		submitButton.style.border = 'none';
		submitButton.style.backgroundColor = '#4CAF50';
		submitButton.style.color = 'white';
		submitButton.style.cursor = 'pointer';
		submitButton.style.borderRadius = '5px';

		// Quand on clique sur le bouton "Valider"
		submitButton.onclick = function (e) {
			e.preventDefault();  // Empêche le comportement par défaut du bouton

			// Récupérer le nom du symbole
			var symbolName = renameInput.value;

			// Si le nom du symbole n'a pas été renseigné, afficher un message d'erreur et arrêter la fonction
			if (!symbolName || symbolName.trim() === "") {
				var errorElement = document.createElement('div');
				errorElement.className = 'upload-error';
				errorElement.innerText = 'Veuillez entrer un nom pour le symbole avant de télécharger.';
				progressBarContainer.appendChild(errorElement);
				return;
			}

			// Ajouter le nom du symbole à la FormData
			formData.append('symbol_name', symbolName);

			// Ajouter le fichier à la FormData
			formData.append('file', file);

			// Ouvrir la requête
			xhr.open('POST', url, true);

			// Envoyer la requête
			xhr.send(formData);

			// Désactiver l'input et le bouton pour éviter plusieurs soumissions
			renameInput.disabled = true;
			this.disabled = true;
		}


		progressBarContainer.appendChild(submitButton);
		uploadProgress.appendChild(progressBarContainer);

		// Mettre à jour la barre de progression pendant le chargement du fichier
		xhr.upload.onprogress = function (e) {
			if (e.lengthComputable) {
				var percentComplete = (e.loaded / e.total) * 100;
				progressBar.innerText = file.name + ' : ' + Math.round(percentComplete) + '% uploaded';

				if (percentComplete === 100) {
					progressBar.className = 'upload-success';
				}
			}
		};

		// Ajouter le fichier à la FormData
		formData.append('file', file);

		// Quand la touche "Enter" est pressée
		renameInput.addEventListener('keypress', function (e) {
			// Si la touche pressée est 'Enter'
			if (e.key === 'Enter') {
				e.preventDefault();  // Empêche le comportement par défaut de la touche "Enter"

				// Récupérer le nom du symbole
				var symbolName = renameInput.value;

				// Si le nom du symbole n'a pas été renseigné, afficher un message d'erreur et arrêter la fonction
				if (!symbolName || symbolName.trim() === "") {
					var errorElement = document.createElement('div');
					errorElement.className = 'upload-error';
					errorElement.innerText = 'Veuillez entrer un nom pour le symbole avant de télécharger.';
					progressBarContainer.appendChild(errorElement);
					return;
				}

				// Ajouter le nom du symbole à la FormData
				formData.append('symbol_name', symbolName);

				// Ajouter le fichier à la FormData
				formData.append('file', file);

				// Ouvrir la requête
				xhr.open('POST', url, true);

				// Envoyer la requête
				xhr.send(formData);

				// Disable the input and button to prevent multiple submissions
				renameInput.disabled = true;
				submitButton.disabled = true;
			}
		});



	}



	function handleFile(file) {
		return new Promise((resolve, reject) => {
			// Si aucun fichier n'a été sélectionné, terminez la fonction
			if (!file) {
				reject();
				return;
			}

			// Vérifiez si le fichier a une extension .svg
			var fileName = file.name;
			var fileExtension = fileName.slice((fileName.lastIndexOf(".") - 1 >>> 0) + 2);
			if (fileExtension.toLowerCase() !== 'svg') {
				reject("Le fichier '" + file.name + "' n'est pas un fichier SVG.");
				return;
			}

			// Créez un nouvel objet FileReader pour lire le contenu du fichier
			var reader = new FileReader();
			reader.onload = function (e) {
				// Récupérez le contenu du fichier (code source SVG)
				var contents = e.target.result;

				// Créez un nouvel objet DOMParser pour analyser le code source SVG
				var parser = new DOMParser();
				var doc = parser.parseFromString(contents, "image/svg+xml");

				// Sélectionnez tous les éléments avec les attributs 'fill' et 'stroke', et les balises de style
				var fills = doc.querySelectorAll('[fill]');
				var strokes = doc.querySelectorAll('[stroke]');
				var styles = doc.querySelectorAll('style');

				// Créez un Set pour stocker les couleurs trouvées (élimine les doublons)
				var colors = new Set();

				// Vérifiez les attributs 'fill'
				for (var i = 0; i < fills.length; i++) {
					var color = fills[i].getAttribute('fill');
					// Si une couleur autre que #000000 est trouvée, refusez le fichier
					if (color && color !== "#000000") {
						reject("Le fichier '" + file.name + "' contient une couleur 'fill' autre que #000000.");
						return;
					}
					colors.add(color);
				}

				// Vérifiez les attributs 'stroke'
				for (var i = 0; i < strokes.length; i++) {
					var color = strokes[i].getAttribute('stroke');
					// Si une couleur autre que #000000 est trouvée, refusez le fichier
					if (color && color !== "#000000") {
						reject("Le fichier '" + file.name + "' contient une couleur 'stroke' autre que #000000.");
						return;
					}
					colors.add(color);
				}

				// Vérifiez les déclarations de couleur dans les balises de style
				for (var i = 0; i < styles.length; i++) {
					var styleContent = styles[i].textContent;
					var regex = /#[0-9a-fA-F]{6}/g; // Toutes les couleurs en hexadécimal
					var matches = styleContent.match(regex);
					for (var j = 0; matches && j < matches.length; j++) {
						var color = matches[j];
						if (color !== "#000000") {
							reject("Le fichier '" + file.name + "' contient une couleur dans la balise 'style' autre que #000000.");
							return;
						}
						colors.add(color);
					}
				}

				resolve(file);
			};
			reader.readAsText(file);
		});
	}


</script>