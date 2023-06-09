
//_____________________________ URL de base de l'API _____________________________________
//* Chemin de base de votre API REST
//! _____ docker : _____
var apiBaseURL = 'http://localhost:8000/index.php';
//! _____ local : _____
// var apiBaseURL = 'http://www.sitetest.local/illustrator2_project/back/index.php';

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
					<td class="contenteditable" data-field="file_name">
						<div class="editable-content" contenteditable="false">
							${symbol.file_name}
						</div>
					</td>

					<td class="cln-size contenteditable" data-field="size">
						<div class="editable-content" contenteditable="false">
							${symbol.size}
						</div>
					</td>

					<td class="cln-size" data-field="active">
						<button class="toggle-btn ${symbol.active ? 'active' : ''}" data-id="${symbol.id}">
							${symbol.active ? 'ON' : 'OFF'}
						</button>
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
	var fileNameElement = symbolRow.find("[data-field='file_name'] .editable-content");
	var sizeElement = symbolRow.find("[data-field='size'] .editable-content");
	var activeElement = symbolRow.find("[data-field='active'] .toggle-btn");

	var fileName = fileNameElement.text().trim();
	var size = parseInt(sizeElement.text().trim());
	var active = activeElement.hasClass("active") ? 1 : 0;

	var categories = Array.from(symbolRow[0].querySelectorAll(".category-list li[data-category-id]")).map(function (category) {
		return parseInt(category.dataset.categoryId);
	});

	var keywords = Array.from(symbolRow[0].querySelectorAll(".keyword-list li")).map(function (keyword) {
		return parseInt(keyword.dataset.keywordId);
	});

	var loadingElement = symbolRow.find(".editable-content");

	// Ajouter la classe 'loading' à l'élément d'animation
	loadingElement.addClass("loading");

	// Effectuer une requête AJAX PUT pour enregistrer les modifications du symbole
	$.ajax({
		url: apiBaseURL + "/symbols/" + symbolId,
		type: "PUT",
		data: JSON.stringify({
			file_name: fileName,
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

// Attacher un gestionnaire d'événement au double-clic sur le champ "Nom du fichier"
$(document).on("dblclick", "[data-field='file_name'] .editable-content", function () {
	$(this).attr("contenteditable", "true").focus();
});

// Attacher un gestionnaire d'événement au double-clic sur le champ "Taille"
$(document).on("dblclick", "[data-field='size'] .editable-content", function () {
	$(this).attr("contenteditable", "true").focus();
});

// Attacher un gestionnaire d'événement à la perte de focus du champ "Nom du fichier"
$(document).on("blur", "[data-field='file_name'] .editable-content", function () {
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
$(document).on("keydown", "[data-field='file_name'] .editable-content, [data-field='size'] .editable-content", function (event) {
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



//__________________ SUPPRESSION D'UN SYMBOLE ______________________
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


//____________________________ BARRE DE RECHERCHE _____________________

function performSearch() {
	var searchInput = document.getElementById("searchInput");
	var searchTerm = searchInput.value.toLowerCase();
	var categoryFilter = document.getElementById("categoryFilter").value;

	var symbols = document.querySelectorAll(".table-symbols tr:not(:first-child)");

	Array.from(symbols).forEach(function (symbol) {
		var fileNameElement = symbol.querySelector("[data-field='file_name'] .editable-content");
		var keywords = symbol.querySelectorAll(".keyword-list li");

		// Recherche sur le nom du fichier
		var isFileNameMatch = false;
		if (fileNameElement) {
			var fileName = fileNameElement.textContent.toLowerCase();
			if (fileName.includes(searchTerm)) {
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
}

