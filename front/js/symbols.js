
//*********** PAGE SYMBOLS ***********//
//_________________ Génération du tableau via ajax api symbols __________________
apiGet('/symbols', function (symbols) {
	var tableContent = symbols.map(function (symbol) {
		var categoriesContent = symbol.categories.map(function (category) {
			return `
					<li data-category-id="${category.id}">
						<span>${category.category}</span>
						<button class="delete-category-btn">&times;</button>
					</li>
				`;
		}).join('');

		var keywordsContent = symbol.keywords.map(function (keyword) {
			return `
					<li data-keyword-id="${keyword.id}">
						<span>${keyword.keyword}</span>
						<button class="delete-keyword-btn">&times;</button>
					</li>
				`;
		}).join('');

		return `
				<tr>
					<td class="contenteditable" data-field="symbol_name">
						<div class="editable-content" contenteditable="false">
							${symbol.symbol_name}
						</div>
					</td>

					<td class="contenteditable" data-field="size">
						<div class="editable-content" contenteditable="false">
							${symbol.size}
						</div>
					</td>

					<td data-field="active">
						<button class="toggle-btn ${symbol.active ? 'active' : ''}" data-id="${symbol.id}">
							${symbol.active ? 'ON' : 'OFF'}
						</button>
					</td>


					<td>
						<img class="preview-img" src="${symbol.src}" alt="${symbol.symbol_name}" >
					</td>


					<td>
						<ul class="category-list">
						${categoriesContent}
						<li class="add-btn">
							<button class="add-category-btn" data-type="categories">
								<i class="fa-solid fa-circle-plus add-icon"></i>
							</button>
							<div class="modal category-modal">
							<div class="modal-content">
								<h2>Liste des catégories</h2>
								<ul id="categoryList" class="scrollable-list"></ul>
							</div>
							</div>
						</li>
						</ul>
					</td>

					<td>
						<ul class="keyword-list">
							${keywordsContent}
						<li class="add-btn">
							<button class="add-keyword-btn" data-type="keywords">
								<i class="fa-solid fa-circle-plus add-icon"></i>
							</button>
							<div class="modal keyword-modal">
							<div class="modal-content">
								<h2>Liste des mots-clés</h2>
								<ul id="keywordList" class="scrollable-list"></ul>
							</div>
							</div>
						</li>
						</ul>
					</td>



					<td>
						<button class="edit-btn" data-id="${symbol.id}">Modifier</button>
					</td>

					<td>
						<a href="${symbol.src}" download="${symbol.src}">Télécharger</a>
					</td>



					
					<td>
						<input type="checkbox" class="symbolCheckbox" data-id="${symbol.id}">
						<button class="delete-btn" data-id="${symbol.id}">Supprimer</button>
					</td>
				</tr>
			`;
	}).join('');

	$('.table-symbols').append(tableContent);
});

// _____________________________MODAL__________________________________

function openModal() {
	var symbolRow = this.closest("tr");
	var buttonType = this.dataset.type;
	console.log(buttonType);

	var modal;
	var list;
	var itemType;
	var itemId;

	if (buttonType === "categories") {
		modal = symbolRow.querySelector(".category-modal");
		list = symbolRow.querySelector("#categoryList");
		itemType = "category";
		itemId = "data-category-id";
	} else if (buttonType === "keywords") {
		modal = symbolRow.querySelector(".keyword-modal");
		list = symbolRow.querySelector("#keywordList");
		itemType = "keyword";
		itemId = "data-keyword-id";
	}

	fetch(apiBaseURL + "/" + buttonType)
		.then(function (response) {
			if (response.ok) {
				return response.json();
			}
			throw new Error("Erreur lors de la récupération des " + buttonType + ".");
		})
		.then(function (items) {
			var existingItems = Array.from(symbolRow.querySelectorAll("[" + itemId + "]")).map(function (item) {
				return item.dataset[itemType + "Id"];
			});

			list.innerHTML = "";

			items.forEach(function (item) {
				if (!existingItems.includes(item[itemType + "_id"].toString())) {
					var itemElement = document.createElement("li");
					itemElement.dataset[itemType + "Id"] = item[itemType + "_id"];
					itemElement.textContent = item[itemType];

					itemElement.addEventListener("click", function () {
						console.log(itemType.charAt(0).toUpperCase() + itemType.slice(1) + " cliqué :", item[itemType]);
						addItem(itemElement, itemType);
					});

					itemElement.addEventListener("mouseover", handleItemMouseOver);
					itemElement.addEventListener("mouseout", handleItemMouseOut);

					list.appendChild(itemElement);
				}
			});
		})
		.catch(function (error) {
			console.log(error);
		});

	modal.style.display = "block";

	// Fermer la modal lorsque l'utilisateur clique en dehors de celle-ci
	window.onclick = function (event) {
		if (event.target == modal) {
			closeModal();
		}
	};
}

function closeModal() {
	var modals = document.querySelectorAll(".modal");
	for (var i = 0; i < modals.length; i++) {
		var modal = modals[i];
		modal.style.display = "none";

		var list = modal.querySelector(".scrollable-list");
		list.innerHTML = "";
	}
}

function handleItemMouseOver() {
	// Code à exécuter lorsque la souris survole la catégorie
	this.classList.add("category-hover");
}

function handleItemMouseOut() {
	// Code à exécuter lorsque la souris quitte la catégorie
	this.classList.remove("category-hover");
}

function addItem(itemElement, itemType) {
	var symbolRow = itemElement.closest("tr");
	var symbolId = symbolRow.querySelector(".delete-btn").dataset.id;
	var itemId = itemElement.dataset[itemType + "Id"];
	var apiUrl = apiBaseURL + "/symbols/" + symbolId;

	if (itemType === "category") {
		apiUrl += "/categories";
	} else if (itemType === "keyword") {
		apiUrl += "/keywords";
	}

	// Effectuer une requête AJAX POST pour associer l'élément au symbole en base de données
	$.ajax({
		url: apiUrl + "/" + itemId,
		type: "POST",
		success: function (data) {
			console.log(itemType.charAt(0).toUpperCase() + itemType.slice(1) + " associé au symbole avec succès:", data);

			var itemContainer = symbolRow.querySelector("." + itemType + "-list");

			var newItemElement = document.createElement("li");
			newItemElement.dataset[itemType + "Id"] = itemId;

			var itemSpan = document.createElement("span");
			itemSpan.textContent = itemElement.textContent;
			newItemElement.appendChild(itemSpan);

			var deleteButton = document.createElement("button");
			deleteButton.classList.add("delete-" + itemType + "-btn");
			deleteButton.innerHTML = "&times;";
			newItemElement.appendChild(deleteButton);

			var addButton = itemContainer.querySelector(".add-btn");

			// Insérer le nouvel élément avant le bouton "+"
			itemContainer.insertBefore(newItemElement, addButton);

			// Effacer la sélection
			itemElement.classList.remove(itemType + "-hover");

			// Retirer l'élément de la liste
			itemElement.remove();

			// closeModal();
		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.log("Erreur lors de l'association de l'" + itemType + " au symbole:", textStatus, errorThrown);
		}
	});
}

// __________________________________ Supprimer la liaison categories ou mots-clés sur les symboles _________________________________________
function deleteCategory() {
	var categoryItem = this.closest("li");
	var categoryId = categoryItem.dataset.categoryId;
	var symbolRow = this.closest("tr");
	var symbolId = symbolRow.querySelector(".delete-btn").dataset.id;

	// Effectuer une requête AJAX DELETE pour supprimer la catégorie du symbole
	$.ajax({
		url: apiBaseURL + "/symbols/" + symbolId + "/categories/" + categoryId,
		type: "DELETE",
		success: function () {
			console.log("Catégorie supprimée avec succès.");
			categoryItem.remove();
		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.log("Erreur lors de la suppression de la catégorie:", textStatus, errorThrown);
		}
	});
}

function deleteKeyword() {
	var keywordItem = this.closest("li");
	var keywordId = keywordItem.dataset.keywordId;
	var symbolRow = this.closest("tr");
	var symbolId = symbolRow.querySelector(".delete-btn").dataset.id;

	// Effectuer une requête AJAX DELETE pour supprimer le mot-clé du symbole
	$.ajax({
		url: apiBaseURL + "/symbols/" + symbolId + "/keywords/" + keywordId,
		type: "DELETE",
		success: function () {
			console.log("Mot-clé supprimé avec succès.");
			keywordItem.remove();
		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.log("Erreur lors de la suppression du mot-clé:", textStatus, errorThrown);
		}
	});
}

//________________ Fonction pour gérer le basculement du bouton ON/OFF ______________
function toggleActive(symbolId) {
	var toggleButton = $(`.toggle-btn[data-id="${symbolId}"]`);
	var isActive = toggleButton.hasClass("active") ? 0 : 1; // Convertir la valeur en entier

	console.log("toggleButton" + toggleButton);
	console.log("isActive" + isActive);
	// Effectuer une requête AJAX PUT pour mettre à jour l'état actif du symbole
	$.ajax({
		url: apiBaseURL + "/symbols/" + symbolId,
		type: "PUT",
		data: JSON.stringify({
			active: isActive === 1 ? 1 : 0 // Assurer que la valeur est soit 1 soit 0
		}),
		contentType: "application/json",
		success: function (data) {
			console.log("État actif du symbole mis à jour avec succès");
			// Mettre à jour le bouton ON/OFF et son état actif
			toggleButton.toggleClass("active");
			toggleButton.text(toggleButton.hasClass("active") ? "ON" : "OFF");

			// Mettre à jour le symbole dans le tableau
			var symbolRow = toggleButton.closest("tr");
			
			updateSymbol(symbolId, symbolRow);
		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.log("Erreur lors de la mise à jour de l'état actif du symbole:", textStatus, errorThrown);
		}
	});
}

// Gestionnaire d'événement pour le basculement de l'état actif
$(document).on("click", ".toggle-btn", function () {
	var symbolId = $(this).data("id");
	var isActive = $(this).hasClass("active");

	toggleActive(symbolId, !isActive);
});

//_____________________ UPDATE SYMBOLE ____________________________________
// Fonction pour gérer la mise à jour du symbole
function updateSymbol(symbolId, symbolRow) {
	var symbolNameElement = symbolRow.find("[data-field='symbol_name'] .editable-content");
	var sizeElement = symbolRow.find("[data-field='size'] .editable-content");
	var activeElement = symbolRow.find("[data-field='active'] .toggle-btn");

	var symbolName = symbolNameElement.text().trim();
	var size = parseInt(sizeElement.text().trim());
	var active = activeElement.hasClass("active") ? 1 : 0;

	var categories = Array.from(symbolRow[0].querySelectorAll(".category-list li[data-category-id]")).map(function (category) {
		return parseInt(category.dataset.categoryId);
	});

	var keywords = Array.from(symbolRow[0].querySelectorAll(".keyword-list li[data-keyword-id]")).map(function (keyword) {
		return parseInt(keyword.dataset.keywordId);
	});
	
	console.log("symbolId :" + symbolId);
	console.log("symbolName :" + symbolName);
	console.log("size :" + size);
	console.log("active :" + active);
	console.log("categories :" + categories);
	console.log("keywords :" + keywords);
	
	var loadingElement = symbolRow.find(".editable-content");

	// Ajouter la classe 'loading' à l'élément d'animation
	loadingElement.addClass("loading");

	// Effectuer une requête AJAX PUT pour enregistrer les modifications du symbole
	$.ajax({
		url: apiBaseURL + "/symbols/" + symbolId,
		type: "PUT",
		data: JSON.stringify({
			symbol_name: symbolName,
			size: size,
			active: active,
			categories: categories,
			keywords: keywords
		}),
		contentType: "application/json",
		success: function (data) {
			console.log("Symbole mis à jour avec succès:", data);
			// Supprimer la classe 'loading' de l'élément d'animation en cas d'erreur
			loadingElement.removeClass("loading");
		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.log("Erreur lors de la mise à jour du symbole:", textStatus, errorThrown);
			// Supprimer la classe 'loading' de l'élément d'animation en cas d'erreur
			loadingElement.removeClass("loading");
		}
	});
}

// Attacher un gestionnaire d'événement au double-clic sur le champ "Nom du symbole"
$(document).on("dblclick", "[data-field='symbol_name'] .editable-content", function () {
	$(this).attr("contenteditable", "true").focus();
});

// Attacher un gestionnaire d'événement au double-clic sur le champ "Taille"
$(document).on("dblclick", "[data-field='size'] .editable-content", function () {
	$(this).attr("contenteditable", "true").focus();
});

// Attacher un gestionnaire d'événement à la perte de focus du champ "Nom du symbole"
$(document).on("blur", "[data-field='symbol_name'] .editable-content", function () {
	$(this).removeAttr("contenteditable");
	var symbolId = $(this).closest("tr").find(".delete-btn").data("id");
	var symbolRow = $(this).closest("tr");
	updateSymbol(symbolId, symbolRow);
});

// Attacher un gestionnaire d'événement à la perte de focus du champ "Taille"
$(document).on("blur", "[data-field='size'] .editable-content", function () {
	$(this).removeAttr("contenteditable");
	var symbolId = $(this).closest("tr").find(".delete-btn").data("id");
	var symbolRow = $(this).closest("tr");
	updateSymbol(symbolId, symbolRow);
});

// Attacher un gestionnaire d'événement à l'appui sur la touche "Entrée"
$(document).on("keydown", "[data-field='symbol_name'] .editable-content, [data-field='size'] .editable-content", function (event) {
	if (event.which === 13) {
		event.preventDefault();
		$(this).blur(); // Fait sortir de l'input
	}
});


//___________________ gestionnaires d'événements aux éléments du tableau _______________________
$(document).on("click", ".add-category-btn", openModal);
$(document).on("click", ".add-keyword-btn", openModal);
$(document).on("click", ".delete-category-btn", deleteCategory);
$(document).on("click", ".delete-keyword-btn", deleteKeyword);




//__________________ SUPPRESSION D'UN OU PLUSIEURS SYMBOLES ______________________
$(document).on("click", ".delete-btn", function () {
	var symbolId = $(this).data("id");
	var symbolRow = $(this).closest("tr");

	// Afficher une boîte de dialogue de confirmation avant la suppression
	if (confirm("Êtes-vous sûr de vouloir supprimer ce symbole ?")) {
		// Effectuer une requête AJAX DELETE pour supprimer le symbole
		$.ajax({
			url: apiBaseURL + "/symbols/" + symbolId,
			type: "DELETE",
			success: function () {
				console.log("Symbole supprimé avec succès.");
				symbolRow.remove();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log("Erreur lors de la suppression du symbole:", textStatus, errorThrown);
			}
		});
	}
});

// Gestionnaire d'événement pour les cases à cocher des symboles individuels
$(document).on("change", ".symbolCheckbox", function () {
	var anyChecked = $(".symbolCheckbox:checked").length > 0;
	var allChecked = $(".symbolCheckbox:checked").length === $(".symbolCheckbox").length;

	// Activer/désactiver les boutons "Supprimer" sur chaque ligne
	$(".delete-btn").prop("disabled", anyChecked);

	// Mettre à jour l'état du bouton "Supprimer la sélection"
	$("#deleteSelectedBtn").prop("disabled", !anyChecked);

	// Activer/désactiver le bouton "supp.select."
	$("#selectAllCheckbox").prop("disabled", false); // Définit explicitement le disabled à false
});

$("#selectAllCheckbox").on("change", function () {
	var isChecked = $(this).prop("checked");

	// Cocher ou décocher toutes les cases individuelles
	$(".symbolCheckbox").prop("checked", isChecked);

	// Activer/désactiver les boutons "Supprimer" sur chaque ligne
	$(".delete-btn").prop("disabled", isChecked);

	// Mettre à jour l'état du bouton "Supprimer la sélection"
	$("#deleteSelectedBtn").prop("disabled", !isChecked);
});

// Gestionnaire d'événement pour la case à cocher "Tout sélectionner"
$("#selectAllCheckbox").on("change", function () {
	$(".symbolCheckbox").prop("checked", $(this).prop("checked"));
});

// Gestionnaire d'événement pour les cases à cocher des symboles individuels
$(document).on("change", ".symbolCheckbox", function () {
	$("#selectAllCheckbox").prop("checked", $(".symbolCheckbox:checked").length === $(".symbolCheckbox").length);
});


$(document).on("click", "#deleteSelectedBtn", function () {
	// Récupérer les identifiants des symboles sélectionnés qui sont visibles
	var selectedSymbols = $(".symbolCheckbox:checked").map(function () {
		// Vérifier si la ligne est visible
		if ($(this).closest("tr").css("display") !== "none") {
			return $(this).data("id");
		}
	}).get();

	// Afficher une boîte de dialogue de confirmation avant la suppression
	if (confirm("Êtes-vous sûr de vouloir supprimer les symboles sélectionnés ?")) {
		// Parcourir les symboles sélectionnés et effectuer une requête AJAX DELETE pour supprimer chaque symbole
		selectedSymbols.forEach(function (symbolId) {
			$.ajax({
				url: apiBaseURL + "/symbols/" + symbolId,
				type: "DELETE",
				success: function () {
					console.log("Symbole supprimé avec succès :", symbolId);
					// Supprimer la ligne du symbole supprimé de la table
					$(".symbolCheckbox[data-id='" + symbolId + "']").closest("tr").remove();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.log("Erreur lors de la suppression du symbole", symbolId, ":", textStatus, errorThrown);
				}
			});
		});

		// Décocher toutes les cases à cocher
		$(".symbolCheckbox").prop("checked", false);
		// Décocher la case "Tout sélectionner"
		$("#selectAllCheckbox").prop("checked", false);
		// Réactiver tous les boutons supprimer
		$(".delete-btn").prop("disabled", false);
		// Réinitialiser l'état du bouton "Supprimer la sélection"
		$("#deleteSelectedBtn").prop("disabled", true);
	}
});

//____________________________ BARRE DE RECHERCHE _____________________

function performSearch() {
	var searchInput = document.getElementById("searchInput");
	var searchTerm = searchInput.value.toLowerCase();
	var categoryFilter = document.getElementById("categoryFilter").value;

	var symbols = document.querySelectorAll(".table-symbols tr:not(:first-child)");

	Array.from(symbols).forEach(function (symbol) {
		var symbolNameElement = symbol.querySelector("[data-field='symbol_name'] .editable-content");
		var keywords = symbol.querySelectorAll(".keyword-list li");

		// Recherche sur le nom du fichier
		var isFileNameMatch = false;
		if (symbolNameElement) {
			var symbolName = symbolNameElement.textContent.toLowerCase();
			if (symbolName.includes(searchTerm)) {
				isFileNameMatch = true;
			}
		}

		// Recherche sur les mots-clés
		var isKeywordMatch = Array.from(keywords).some(function (keyword) {
			var keywordSpan = keyword.querySelector("span");
			if (keywordSpan) {
				var keywordName = keywordSpan.textContent.toLowerCase();
				return keywordName.includes(searchTerm);
			}
			return false;
		});

		// Filtre par catégorie
		var hasCategoryFilter = categoryFilter !== "";
		var categoryList = Array.from(symbol.querySelectorAll(".category-list li[data-category-id]"));
		var hasCategory = Array.from(categoryList).some(function (category) {
			return category.dataset.categoryId === categoryFilter;
		});

		// Afficher ou masquer le symbole en fonction des résultats de recherche et du filtre de catégorie
		var shouldDisplay = (isFileNameMatch || isKeywordMatch) && (!hasCategoryFilter || hasCategory);
		symbol.style.display = shouldDisplay ? "table-row" : "none";

		// Ajouter la classe "filtered" aux lignes qui correspondent aux critères de recherche
		if (shouldDisplay) {
			symbol.classList.add("filtered");
		}
	});
}

// Fonction pour gérer la modification du filtre de catégorie
function handleCategoryFilter() {
	performSearch();
}

// Attacher un gestionnaire d'événement pour la modification du filtre de catégorie
var categoryFilter = document.getElementById("categoryFilter");
categoryFilter.addEventListener("change", handleCategoryFilter);

// Attacher un gestionnaire d'événement pour la modification du champ de recherche
var searchInput = document.getElementById("searchInput");
searchInput.addEventListener("input", performSearch);

var searchInput = document.getElementById("searchInput");
searchInput.addEventListener("input", performSearch);
// Récupérer les catégories et générer les options dans le filtre de catégorie
apiGet('/categories', function (categories) {
	var categoryFilter = document.getElementById("categoryFilter");
	categories.forEach(function (category) {
		var option = document.createElement("option");
		option.value = category.category_id;
		option.textContent = category.category;
		categoryFilter.appendChild(option);
	});
});

// Gestionnaire d'événement pour le bouton de réinitialisation
var resetFiltersBtn = document.getElementById("resetFiltersBtn");
resetFiltersBtn.addEventListener("click", resetFilters);

// Fonction pour réinitialiser les filtres
function resetFilters() {
	// Réinitialiser la valeur du champ de recherche
	var searchInput = document.getElementById("searchInput");
	searchInput.value = "";

	// Réinitialiser la valeur du filtre de catégorie
	var categoryFilter = document.getElementById("categoryFilter");
	categoryFilter.selectedIndex = 0;

	// Afficher tous les symboles
	var symbols = document.querySelectorAll(".table-symbols tr:not(:first-child)");
	Array.from(symbols).forEach(function (symbol) {
		symbol.style.display = "table-row";
	});

	// Décocher toutes les cases à cocher
	$(".symbolCheckbox").prop("checked", false);
	// Décocher la case "Tout sélectionner"
	$("#selectAllCheckbox").prop("checked", false);
	// Réactiver tous les boutons supprimer
	$(".delete-btn").prop("disabled", false);
	// Réinitialiser l'état du bouton "Supprimer la sélection"
	$("#deleteSelectedBtn").prop("disabled", true);
}





//!___________________________ MIS DE COTÉ POUR LE MOMENT ____________________
//____________________________ MODAL D'AJOUT DE SYMBOLES _____________________



// 	// Sélectionnez le bouton "AJOUTER SYMBOLES"
// 	var openModalButton = document.getElementById('openUploadModal');

// 	// Sélectionnez la modal et son contenu
// 	var uploadModal = document.querySelector('.uploadModal');
// 	var modalContent = document.querySelector('.uploadModal-content');

// 	// Attachez un gestionnaire d'événements au bouton pour ouvrir la modal
// 	openModalButton.addEventListener('click', function () {
// 		uploadModal.style.display = 'block';
// 		// Ajoutez le contenu du formulaire à la modal
// 		modalContent.innerHTML = `
//   <style>
// 	.content-upload {
// 		display: flex;
// 		flex-direction: row;
// 		justify-content: space-between;
// 	}

// 	#drop_zone {
// 		margin: 30px;
// 		padding: 20px;
// 		border: 2px dashed #ccc;
// 		width: 45%;
// 		height: 400px;
// 		text-align: center;
// 		position: relative;
// 		display: flex;
// 		flex-direction: column;
// 		justify-content: center;
// 		align-items: center;
// 		overflow: hidden;
// 	}

// 	#uploadProgress {
// 		margin: 30px;
// 		padding: 20px;
// 		width: 45%;
// 		height: 400px;
// 		overflow-y: auto;
// 		border: 2px solid #ccc;
// 	}

// 	#drop_zone.hover {
// 		border-color: #888;
// 	}

// 	.upload-progress {
// 		margin-top: 10px;
// 		padding: 3px;
// 		border: 1px solid #ccc;
// 		color: #000;
// 		width: 100%;
// 	}

// 	.upload-error {
// 		color: red;
// 	}

// 	.upload-success {
// 		color: green;
// 	}

// 	.upload-progress>div:nth-child(1) {
// 		flex: 0 0 33%;
// 		display: flex;
// 	}

// 	.upload-progress .upload-error {
// 		flex-basis: 100%;
// 		margin-top: 10px;
// 	}
// </style>


// <div class="content-upload">



// 	<div id="drop_zone">
// 		<div>
// 			Glissez et déposez le fichier ici
// 		</div>
// 		<i class="fa-solid fa-download"></i>
// 	</div>
// 	<input type="file" id="uploadedFile" style="display:none;" multiple>

// 	<div id="uploadProgress"></div>


// </div>
//   `;

// 		// Attachez un gestionnaire d'événements 'change' au champ de fichier
// 		var fileInput = document.getElementById('uploadedFile');
// 		var dropZone = document.getElementById('drop_zone');
// 		var uploadProgress = document.getElementById('uploadProgress');

// 		dropZone.ondragover = function () {
// 			this.className = 'hover';
// 			return false;
// 		};

// 		dropZone.ondragend = function () {
// 			this.className = '';
// 			return false;
// 		};

// 		dropZone.ondrop = function (e) {
// 			this.className = '';
// 			e.preventDefault();

// 			// fileInput.files = e.dataTransfer.files;
// 			var dataTransfer = new DataTransfer();
// 			for (var i = 0; i < e.dataTransfer.files.length; i++) {
// 				dataTransfer.items.add(e.dataTransfer.files[i]);
// 			}
// 			fileInput.files = dataTransfer.files;

// 			//  gérer plusieurs fichiers
// 			for (var i = 0; i < fileInput.files.length; i++) {
// 				handleFile(fileInput.files[i])
// 					.then(uploadFile)
// 					.catch(function (errorMessage) {
// 						// Si le fichier n'est pas correct, affichez le message d'erreur
// 						var errorElement = document.createElement('div');
// 						errorElement.innerText = 'Erreur : ' + errorMessage;
// 						uploadProgress.appendChild(errorElement);
// 					});
// 			}
// 		};

// 		function uploadFile(file) {
// 			var url = apiBaseURL + '/symbols';
// 			var xhr = new XMLHttpRequest();
// 			var formData = new FormData();

// 			// Créez un nouvel élément DOM pour afficher la progression de cet upload
// 			var progressBarContainer = document.createElement('div');
// 			progressBarContainer.className = 'upload-progress';

// 			var progressBar = document.createElement('div');
// 			progressBar.innerText = file.name + ' : 0% uploaded';
// 			progressBarContainer.appendChild(progressBar);

// 			// Ajoutez un champ de saisie pour renommer le fichier
// 			var renameInput = document.createElement('input');
// 			renameInput.type = 'text';
// 			renameInput.placeholder = 'Nom du symbole';
// 			renameInput.required = true;  // Rend le champ obligatoire
// 			renameInput.style.marginTop = '10px'; // Ajoutez du style à votre champ de saisie
// 			renameInput.style.padding = '5px';
// 			renameInput.style.border = '1px solid #ccc';
// 			renameInput.style.borderRadius = '5px';
// 			progressBarContainer.appendChild(renameInput);

// 			// Ajoutez un bouton de validation
// 			var submitButton = document.createElement('button');
// 			submitButton.innerText = 'Valider';
// 			submitButton.style.marginTop = '10px'; // Ajoutez du style à votre bouton
// 			submitButton.style.padding = '5px 10px';
// 			submitButton.style.border = 'none';
// 			submitButton.style.backgroundColor = '#4CAF50';
// 			submitButton.style.color = 'white';
// 			submitButton.style.cursor = 'pointer';
// 			submitButton.style.borderRadius = '5px';

// 			// Quand on clique sur le bouton "Valider"
// 			submitButton.onclick = function (e) {
// 				e.preventDefault();  // Empêche le comportement par défaut du bouton

// 				// Récupérer le nom du symbole
// 				var symbolName = renameInput.value;

// 				// Si le nom du symbole n'a pas été renseigné, afficher un message d'erreur et arrêter la fonction
// 				if (!symbolName || symbolName.trim() === "") {
// 					var errorElement = document.createElement('div');
// 					errorElement.className = 'upload-error';
// 					errorElement.innerText = 'Veuillez entrer un nom pour le symbole avant de télécharger.';
// 					progressBarContainer.appendChild(errorElement);
// 					return;
// 				}

// 				// Ajouter le nom du symbole à la FormData
// 				formData.append('symbol_name', symbolName);

// 				// Ajouter le fichier à la FormData
// 				formData.append('file', file);

// 				// Ouvrir la requête
// 				xhr.open('POST', url, true);

// 				// Envoyer la requête
// 				xhr.send(formData);

// 				// Désactiver l'input et le bouton pour éviter plusieurs soumissions
// 				renameInput.disabled = true;
// 				this.disabled = true;
// 			}

// 			progressBarContainer.appendChild(submitButton);
// 			uploadProgress.appendChild(progressBarContainer);

// 			// Mettre à jour la barre de progression pendant le chargement du fichier
// 			xhr.upload.onprogress = function (e) {
// 				if (e.lengthComputable) {
// 					var percentComplete = (e.loaded / e.total) * 100;
// 					progressBar.innerText = file.name + ' : ' + Math.round(percentComplete) + '% uploaded';

// 					if (percentComplete === 100) {
// 						progressBar.className = 'upload-success';
// 					}
// 				}
// 			};

// 			// Ajouter le fichier à la FormData
// 			formData.append('file', file);

// 			// Quand la touche "Enter" est pressée
// 			renameInput.addEventListener('keypress', function (e) {
// 				// Si la touche pressée est 'Enter'
// 				if (e.key === 'Enter') {
// 					e.preventDefault();  // Empêche le comportement par défaut de la touche "Enter"

// 					// Récupérer le nom du symbole
// 					var symbolName = renameInput.value;

// 					// Si le nom du symbole n'a pas été renseigné, afficher un message d'erreur et arrêter la fonction
// 					if (!symbolName || symbolName.trim() === "") {
// 						var errorElement = document.createElement('div');
// 						errorElement.className = 'upload-error';
// 						errorElement.innerText = 'Veuillez entrer un nom pour le symbole avant de télécharger.';
// 						progressBarContainer.appendChild(errorElement);
// 						return;
// 					}

// 					// Ajouter le nom du symbole à la FormData
// 					formData.append('symbol_name', symbolName);

// 					// Ajouter le fichier à la FormData
// 					formData.append('file', file);

// 					// Ouvrir la requête
// 					xhr.open('POST', url, true);

// 					// Envoyer la requête
// 					xhr.send(formData);

// 					// Disable the input and button to prevent multiple submissions
// 					renameInput.disabled = true;
// 					submitButton.disabled = true;
// 				}
// 			});



// 		}

// 		function handleFile(file) {
// 			return new Promise((resolve, reject) => {
// 				// Si aucun fichier n'a été sélectionné, terminez la fonction
// 				if (!file) {
// 					reject();
// 					return;
// 				}

// 				// Vérifiez si le fichier a une extension .svg
// 				var fileName = file.name;
// 				var fileExtension = fileName.slice((fileName.lastIndexOf(".") - 1 >>> 0) + 2);
// 				if (fileExtension.toLowerCase() !== 'svg') {
// 					reject("Le fichier '" + file.name + "' n'est pas un fichier SVG.");
// 					return;
// 				}

// 				// Créez un nouvel objet FileReader pour lire le contenu du fichier
// 				var reader = new FileReader();
// 				reader.onload = function (e) {
// 					// Récupérez le contenu du fichier (code source SVG)
// 					var contents = e.target.result;

// 					// Créez un nouvel objet DOMParser pour analyser le code source SVG
// 					var parser = new DOMParser();
// 					var doc = parser.parseFromString(contents, "image/svg+xml");

// 					// Sélectionnez tous les éléments avec les attributs 'fill' et 'stroke', et les balises de style
// 					var fills = doc.querySelectorAll('[fill]');
// 					var strokes = doc.querySelectorAll('[stroke]');
// 					var styles = doc.querySelectorAll('style');

// 					// Créez un Set pour stocker les couleurs trouvées (élimine les doublons)
// 					var colors = new Set();

// 					// Vérifiez les attributs 'fill'
// 					for (var i = 0; i < fills.length; i++) {
// 						var color = fills[i].getAttribute('fill');
// 						// Si une couleur autre que #000000 est trouvée, refusez le fichier
// 						if (color && color !== "#000000") {
// 							reject("Le fichier '" + file.name + "' contient une couleur 'fill' autre que #000000.");
// 							return;
// 						}
// 						colors.add(color);
// 					}

// 					// Vérifiez les attributs 'stroke'
// 					for (var i = 0; i < strokes.length; i++) {
// 						var color = strokes[i].getAttribute('stroke');
// 						// Si une couleur autre que #000000 est trouvée, refusez le fichier
// 						if (color && color !== "#000000") {
// 							reject("Le fichier '" + file.name + "' contient une couleur 'stroke' autre que #000000.");
// 							return;
// 						}
// 						colors.add(color);
// 					}

// 					// Vérifiez les déclarations de couleur dans les balises de style
// 					for (var i = 0; i < styles.length; i++) {
// 						var styleContent = styles[i].textContent;
// 						var regex = /#[0-9a-fA-F]{6}/g; // Toutes les couleurs en hexadécimal
// 						var matches = styleContent.match(regex);
// 						for (var j = 0; matches && j < matches.length; j++) {
// 							var color = matches[j];
// 							if (color !== "#000000") {
// 								reject("Le fichier '" + file.name + "' contient une couleur dans la balise 'style' autre que #000000.");
// 								return;
// 							}
// 							colors.add(color);
// 						}
// 					}

// 					resolve(file);
// 				};
// 				reader.readAsText(file);
// 			});
// 		}

// 	});