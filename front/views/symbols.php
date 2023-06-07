<style>
	/* Mise en page du tableau */
	table {
		width: 100%;
		border-collapse: collapse;
	}

	th,
	td {
		padding: 8px;
		text-align: left;
		border-bottom: 1px solid #ddd;
	}

	th {
		background-color: #f2f2f2;
	}

	th:not(:last-child),
	td:not(:last-child) {
		border-right: 1px solid #ccc;
	}

	tr:hover {
		background-color: #f5f5f5;
	}

	th:nth-child(1),
	td:nth-child(1) {
		width: 10%;
	}

	th:nth-child(2),
	td:nth-child(2) {
		width: 4%;
	}

	th:nth-child(3),
	td:nth-child(3) {
		width: 4%;
	}

	th:nth-child(4),
	td:nth-child(4) {
		width: 30%;
	}

	th:nth-child(5),
	td:nth-child(5) {
		width: 30%;
	}

	th:nth-child(6),
	td:nth-child(6) {
		width: 10%;
	}

	.save-btn,
	.delete-btn {
		width: 15vh;
		margin: 0.2vh;
		padding: 4px 8px;
		background-color: #4caf50;
		color: white;
		border: none;
		cursor: pointer;
		margin-right: 4px;
		border: 2px outset grey;
		border-radius: 1vh;
	}

	.save-btn:hover,
	.delete-btn:hover {
		border: 2px inset grey;
	}

	.delete-btn {
		background-color: #f44336;
	}

	.contenteditable {
		position: relative;
	}

	.editable-content {
		position: absolute;
		top: 0.7vh;
		left: 0.7vh;
		right: 0.7vh;
		bottom: 0.7vh;
		padding: 0.7vh;
		background-color: silver;
		border: none;
		outline: none;
		border: 1px solid grey;
		border-radius: 0.2vh;
	}

	/* Mise en page pour les catégories */
	.category-list,
	.keyword-list {
		list-style-type: none;
		padding: 0;
		margin: 0;
	}

	.category-list li,
	.keyword-list li {
		display: inline-block;
		position: relative;
		margin: 4px;
	}

	.category-list span,
	.keyword-list span {
		display: inline-block;
		padding: 4px 8px;
		background-color: #f2f2f2;
		border-radius: 1vh;
		vertical-align: middle;
		border: 1px solid grey;
	}

	.category-list .delete-category-btn,
	.keyword-list .delete-keyword-btn {
		display: flex;
		justify-content: center;
		align-items: center;
		width: 2vh;
		height: 2vh;
		background-color: #f44336;
		border: 1px solid grey;
		border-radius: 100%;
		font-size: 1.5vh;
		line-height: 1;
		text-align: center;
		cursor: pointer;
		position: absolute;
		top: 0;
		right: 0;
		transform: translate(50%, -50%);
		color: white;
	}

	.category-list .delete-category-btn:hover,
	.keyword-list .delete-keyword-btn:hover {
		color: black;
		border: 1px solid black;
	}

	/* BOUTONS MODALS */

	.add-category-btn {
		display: flex;
		justify-content: center;
		align-items: center;
		text-align: center;
		color: white;
		font-size: 3.5vh;
		width: 3.5vh;
		height: 3.5vh;
		border-radius: 100%;
		background: limegreen;
		border: 2px solid green;
	}

	.add-category-btn:hover {
		cursor: pointer;
		color: black;
	}

	.add-keyword-btn {
		display: flex;
		justify-content: center;
		align-items: center;
		text-align: center;
		color: white;
		font-size: 3.5vh;
		width: 3.5vh;
		height: 3.5vh;
		border-radius: 100%;
		background: limegreen;
		border: 2px solid green;
	}

	.add-keyword-btn:hover {
		cursor: pointer;
		color: black;
	}

	/*__________________________MODAL________________________________*/
	/* Styles pour la modal */
	.modal {
		display: none;
		position: fixed;
		z-index: 1;
		left: 0;
		top: 0;
		width: 100%;
		height: 100%;
		overflow: auto;
		background-color: rgba(0, 0, 0, 0.4);
	}

	.modal-content {
		background-color: #fefefe;
		margin: 15% auto;
		padding: 20px;
		border: 1px solid #888;
		width: 80%;
		max-height: 70vh;
		overflow-y: auto;
	}

	.scrollable-list {
		max-height: 300px;
		overflow-y: auto;
	}

	.scrollable-list li {
		display: inline-block;
		padding: 4px 8px;
		background-color: #f2f2f2;
		border-radius: 1vh;
		vertical-align: middle;
		border: 1px solid grey;
	}

	.category-hover {
		border: 1px solid gold !important;
		background-color: grey !important;
		color: gold;
		cursor: pointer;
	}
</style>

<h1>SYMBOLS</h1>

<table class="table-symbols">
	<tr>
		<th>Nom du fichier</th>
		<th class="cln-size">Taille</th>
		<th class="cln-size">Actif</th>
		<th>Catégories</th>
		<th>Mots-clés</th>
		<th>Actions</th>
	</tr>
	<!-- Les lignes du tableau seront ajoutées ici par JavaScript -->
</table>




<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
	//_____________________________ URL de base de l'API _____________________________________
	var apiBaseURL = 'http://www.sitetest.local/illustrator2_project/back/index.php';

	// Fonction pour effectuer une requête Ajax GET à l'API pour générer le tableau des Symboles
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
			<div class="editable-content" contenteditable="true">
			  ${symbol.file_name}
			</div>
		  </td>

		  <td class="cln-size contenteditable" data-field="size">
			<div class="editable-content" contenteditable="true">
			  ${symbol.size}
			</div>
		  </td>

		  <td class="cln-size contenteditable" data-field="active">
			<div class="editable-content" contenteditable="true">
			  ${symbol.active}
			</div>
		  </td>

		  <td>
			<ul class="category-list">
			  ${categoriesContent}
			  <li class="add-btn">
				<button class="add-category-btn" data-type="categories">+</button>
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
				<button class="add-keyword-btn" data-type="keywords">+</button>
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
			<button class="save-btn" data-id="${symbol.id}">Enregistrer</button>
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

function addItem(itemElement, itemType) {
  var symbolRow = itemElement.closest("tr");
  var symbolId = symbolRow.querySelector(".save-btn").dataset.id;
  var itemId = itemElement.dataset[itemType + "Id"];

  // Effectuer une requête AJAX POST pour associer l'élément au symbole en base de données
  $.ajax({
    url: apiBaseURL + "/symbols/" + symbolId + "/" + itemType + "s/" + itemId,
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
  var symbolId = symbolRow.querySelector(".save-btn").dataset.id;
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




	// function addCategory(categoryItem) {
	// 	var symbolRow = categoryItem.closest("tr");
	// 	var symbolId = symbolRow.querySelector(".save-btn").dataset.id;
	// 	var categoryId = categoryItem.dataset.categoryId;

	// 	// Effectuer une requête AJAX POST pour associer la catégorie au symbole en base de données
	// 	$.ajax({
	// 		url: apiBaseURL + "/symbols/" + symbolId + "/categories/" + categoryId,
	// 		type: "POST",
	// 		success: function (data) {
	// 			console.log("Catégorie associée au symbole avec succès:", data);

	// 			var categoryContainer = symbolRow.querySelector(".category-list");

	// 			var newCategoryItem = document.createElement("li");
	// 			newCategoryItem.dataset.categoryId = categoryId;

	// 			var categorySpan = document.createElement("span");
	// 			categorySpan.textContent = categoryItem.textContent;
	// 			newCategoryItem.appendChild(categorySpan);

	// 			var deleteButton = document.createElement("button");
	// 			deleteButton.classList.add("delete-category-btn");
	// 			deleteButton.innerHTML = "&times;";
	// 			newCategoryItem.appendChild(deleteButton);

	// 			var addButton = categoryContainer.querySelector(".add-btn");

	// 			// Insérer la nouvelle catégorie avant le bouton "+"
	// 			categoryContainer.insertBefore(newCategoryItem, addButton);

	// 			// Effacer la sélection
	// 			categoryItem.classList.remove("category-hover");

	// 			// Retirer la catégorie de la liste
	// 			categoryItem.remove();

	// 			// closeModal();
	// 		},
	// 		error: function (jqXHR, textStatus, errorThrown) {
	// 			console.log("Erreur lors de l'association de la catégorie au symbole:", textStatus, errorThrown);
	// 		}
	// 	});
	// }

	// function addKeyword(keywordItem) {
	// 	var symbolRow = keywordItem.closest("tr");
	// 	var symbolId = symbolRow.querySelector(".save-btn").dataset.id;
	// 	var keywordId = keywordItem.dataset.keywordId;

	// 	// Effectuer une requête AJAX POST pour associer le mot-clé au symbole en base de données
	// 	$.ajax({
	// 		url: apiBaseURL + "/symbols/" + symbolId + "/keywords/" + keywordId,
	// 		type: "POST",
	// 		success: function (data) {
	// 			console.log("Mot-clé associé au symbole avec succès:", data);

	// 			var keywordContainer = symbolRow.querySelector(".keyword-list");

	// 			var newKeywordItem = document.createElement("li");
	// 			newKeywordItem.dataset.keywordId = keywordId;

	// 			var keywordSpan = document.createElement("span");
	// 			keywordSpan.textContent = keywordItem.textContent;
	// 			newKeywordItem.appendChild(keywordSpan);

	// 			var deleteButton = document.createElement("button");
	// 			deleteButton.classList.add("delete-keyword-btn");
	// 			deleteButton.innerHTML = "&times;";
	// 			newKeywordItem.appendChild(deleteButton);

	// 			var addButton = keywordContainer.querySelector(".add-keyword-item");

	// 			// Insérer le nouveau mot-clé avant le bouton "+"
	// 			keywordContainer.insertBefore(newKeywordItem, addButton);

	// 			// Effacer la sélection
	// 			keywordItem.classList.remove("keyword-hover");

	// 			// Retirer le mot-clé de la liste
	// 			keywordItem.remove();

	// 			// closeModal();
	// 		},
	// 		error: function (jqXHR, textStatus, errorThrown) {
	// 			console.log("Erreur lors de l'association du mot-clé au symbole:", textStatus, errorThrown);
	// 		}
	// 	});
	// }

	// ___________________________________________________________________________
	function deleteCategory() {
		var categoryItem = this.closest("li");
		var categoryId = categoryItem.dataset.categoryId;
		var symbolRow = this.closest("tr");
		var symbolId = symbolRow.querySelector(".save-btn").dataset.id;

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
		var symbolId = symbolRow.querySelector(".save-btn").dataset.id;

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

	// Attacher des gestionnaires d'événements aux éléments du tableau
	$(document).on("click", ".add-category-btn", openModal);
	$(document).on("click", ".add-keyword-btn", openModal);
	$(document).on("click", ".delete-category-btn", deleteCategory);
	$(document).on("click", ".delete-keyword-btn", deleteKeyword);

	// Attacher des gestionnaires d'événements pour enregistrer et supprimer les symboles
	$(document).on("click", ".save-btn", function () {
		var symbolId = $(this).data("id");
		var symbolRow = $(this).closest("tr");
		var fileName = symbolRow.find("[data-field='file_name'] .editable-content").text().trim();
		var size = symbolRow.find("[data-field='size'] .editable-content").text().trim();
		var active = symbolRow.find("[data-field='active'] .editable-content").text().trim();

		console.log(symbolRow);

		var categories = Array.from(symbolRow[0].querySelectorAll(".category-list li[data-category-id]")).map(function (category) {
			return parseInt(category.dataset.categoryId);
		});
		console.log(categories);

		var keywords = Array.from(symbolRow[0].querySelectorAll(".keyword-list li")).map(function (keyword) {
			return parseInt(keyword.dataset.keywordId);
		});

		// Effectuer une requête AJAX PUT pour enregistrer les modifications du symbole
		$.ajax({
			url: apiBaseURL + "/symbols/" + symbolId,
			type: "PUT",
			data: JSON.stringify({
				file_name: fileName,
				size: parseInt(size),
				active: parseInt(active),
				categories: categories,
				keywords: keywords
			}),
			contentType: "application/json",
			success: function (data) {
				console.log("Symbole mis à jour avec succès:", data);
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log("Erreur lors de la mise à jour du symbole:", textStatus, errorThrown);
			}
		});
	});

	$(document).on("click", ".delete-btn", function () {
		var symbolId = $(this).data("id");
		var symbolRow = $(this).closest("tr");

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
	});
</script>