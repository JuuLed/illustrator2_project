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




	/* MODALS */
	.add-btn {
		/* background: green;(()) */
		/* border-radius: 100%; */
	}

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

	.modal-content-categoies {
		background-color: #fefefe;
		margin: 15% auto;
		padding: 20px;
		border: 1px solid #888;
		width: 80%;
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
	<tbody id="symbolsTableBody"></tbody>
</table>

<!-- _________________________MODAL_______________________ -->

<div class="modal">
	<div class="modal-content-categoies">
		<h2>Liste des catégories</h2>
		<ul id="categoryList" class="scrollable-list">
			<!-- Les catégories seront ajoutées ici -->
		</ul>
	</div>
</div>





<script>
	// _____________________________AJAX__________________________________
 // Code JavaScript pour récupérer et afficher les données

 const symbolsUrl = 'http://www.sitetest.local/illustrator2_project/back/index.php/symbols/';

// Fonction pour effectuer l'appel AJAX
function fetchSymbols() {
  fetch(symbolsUrl)
	.then(response => {
	  if (response.ok) {
		return response.json();
	  }
	  throw new Error("Erreur lors de la récupération des symboles.");
	})
	.then(data => {
	  console.log(data); // Afficher les données dans la console

	  const symbolsTableBody = document.getElementById('symbolsTableBody');

	  // Parcourir les symboles
	  data.forEach(symbol => {
		// Créer une nouvelle ligne pour chaque symbole
		const symbolRow = document.createElement('tr');
		symbolRow.setAttribute('data-id', symbol.id);

		// Créer les cellules de données pour chaque colonne du tableau
		const fileNameCell = document.createElement('td');
		fileNameCell.textContent = symbol.file_name;

		const sizeCell = document.createElement('td');
		sizeCell.textContent = symbol.size;

		const activeCell = document.createElement('td');
		activeCell.textContent = symbol.active;

		const categoriesCell = document.createElement('td');
		symbol.categories.forEach(category => {
		  const categorySpan = document.createElement('span');
		  categorySpan.textContent = category.category;
		  categoriesCell.appendChild(categorySpan);
		});

		const keywordsCell = document.createElement('td');
		symbol.keywords.forEach(keyword => {
		  const keywordSpan = document.createElement('span');
		  keywordSpan.textContent = keyword.keyword;
		  keywordsCell.appendChild(keywordSpan);
		});

		const actionsCell = document.createElement('td');
		// Ajoutez les boutons d'action pour chaque symbole (par exemple, enregistrer, supprimer, etc.) à cette cellule

		// Ajoutez les cellules à la ligne du tableau
		symbolRow.appendChild(fileNameCell);
		symbolRow.appendChild(sizeCell);
		symbolRow.appendChild(activeCell);
		symbolRow.appendChild(categoriesCell);
		symbolRow.appendChild(keywordsCell);
		symbolRow.appendChild(actionsCell);

		// Ajoutez la ligne du tableau au corps du tableau
		symbolsTableBody.appendChild(symbolRow);
	  });
	})
	.catch(error => {
	  console.error("Erreur lors de la récupération des symboles :", error);
	});
}

// Appelez la fonction pour récupérer les symboles
fetchSymbols();

function updateCategoryList(symbolRow, categories) {
    const categoryList = symbolRow.querySelector('.category-list');
    categoryList.innerHTML = renderCategories(categories);

    // Ajoutez les classes de style au nouvel élément de catégorie
    const newCategoryItem = categoryList.querySelector('li:last-child');
    newCategoryItem.classList.add('category-item');
    newCategoryItem.querySelector('button').classList.add('delete-category-btn');

    // Ajoutez un écouteur d'événement pour la suppression de catégorie
    const deleteCategoryButtons = categoryList.querySelectorAll('.delete-category-btn');
    deleteCategoryButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.stopPropagation(); // Arrêter la propagation de l'événement

            const categoryItem = this.parentNode;
            const categoryId = categoryItem.dataset.categoryId;
            const symbolRow = this.closest('tr');
            const symbolId = symbolRow.querySelector('.save-btn').dataset.id;

            // Appeler l'API pour supprimer la catégorie du symbole
            removeCategoryFromSymbol(symbolId, categoryId)
                .then(() => {
                    // Supprimer l'élément de catégorie de l'affichage
                    removeCategoryFromView(categoryItem);
                })
                .catch(error => {
                    console.error('Erreur lors de la suppression de la catégorie:', error);
                });
        });
    });
}

// Mettre à jour la liste des mots-clés d'un symbole
function updateKeywordList(symbolRow, keywords) {
    const keywordList = symbolRow.querySelector('.keyword-list');
    keywordList.innerHTML = renderKeywords(keywords);

    // Ajoutez les classes de style au nouvel élément de mot-clé
    const newKeywordItem = keywordList.querySelector('li:last-child');
    newKeywordItem.classList.add('keyword-item');
    newKeywordItem.querySelector('button').classList.add('delete-keyword-btn');

    // Ajoutez un écouteur d'événement pour la suppression de mot-clé
    const deleteKeywordButtons = keywordList.querySelectorAll('.delete-keyword-btn');
    deleteKeywordButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.stopPropagation(); // Arrêter la propagation de l'événement

            const keywordItem = this.parentNode;
            const keywordId = keywordItem.dataset.keywordId;
            const symbolRow = this.closest('tr');
            const symbolId = symbolRow.querySelector('.save-btn').dataset.id;

            // Appeler l'API pour supprimer le mot-clé du symbole
            removeKeywordFromSymbol(symbolId, keywordId)
                .then(() => {
                    // Supprimer l'élément de mot-clé de l'affichage
                    removeKeywordFromView(keywordItem);
                })
                .catch(error => {
                    console.error('Erreur lors de la suppression du mot-clé:', error);
                });
        });
    });
}

// Rendu HTML pour les catégories
function renderCategories(categories) {
    return categories
        .map(
            category => `
        <li data-category-id="${category.id}" class="category-item">
            <span>${category.category}</span>
            <button class="delete-category-btn">&times;</button>
        </li>
    `
        )
        .join('');
}

// Rendu HTML pour les mots-clés
function renderKeywords(keywords) {
    return keywords
        .map(
            keyword => `
        <li data-keyword-id="${keyword.id}" class="keyword-item">
            <span>${keyword.keyword}</span>
            <button class="delete-keyword-btn">&times;</button>
        </li>
    `
        )
        .join('');
}

// Supprimer visuellement une catégorie d'un symbole
function removeCategoryFromView(categoryItem) {
    const symbolRow = categoryItem.closest('tr');
    const categoryList = symbolRow.querySelector('.category-list');
    categoryItem.remove();

    // Mettre à jour la classe de la première catégorie s'il y en a une
    const firstCategory = categoryList.querySelector('li');
    if (firstCategory) {
        firstCategory.classList.add('first-category');
    }
}

// Supprimer visuellement un mot-clé d'un symbole
function removeKeywordFromView(keywordItem) {
    keywordItem.remove();
}

// Mettre à jour le symbole via l'API
function updateSymbol(symbolId, data) {
    const url = `<?= $apiBaseURL; ?>/symbols/${symbolId}`;
    fetch(url, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            // Afficher un message ou actualiser la page après la mise à jour
            console.log(data);
            // Actualiser la ligne modifiée dans le tableau
            const symbolRow = document.querySelector(`tr[data-id="${symbolId}"]`);
            if (symbolRow) {
                symbolRow.querySelector('[data-field="file_name"]').textContent = data.file_name;
                symbolRow.querySelector('[data-field="size"]').textContent = data.size;
                symbolRow.querySelector('[data-field="active"]').textContent = data.active;
                // Mettre à jour les catégories affichées dans la colonne correspondante
                updateCategoryList(symbolRow, data.categories);
                // Mettre à jour les mots-clés affichés dans la colonne correspondante
                updateKeywordList(symbolRow, data.keywords);
            }
        })
        .catch(error => {
            console.error('Erreur lors de la mise à jour du symbole:', error);
        });
}




	// _____________________________MODAL__________________________________

	function openModal() {
  var symbolRow = this.closest("tr");
  var categoryList = symbolRow.querySelector("#categoryList");

  // Effectuer l'appel à l'API pour obtenir la liste des catégories
  fetch("http://www.sitetest.local/illustrator2_project/back/index.php/categories/")
    .then(function (response) {
      if (response.ok) {
        return response.json();
      }
      throw new Error("Erreur lors de la récupération des catégories.");
    })
    .then(function (categories) {
      // Videz la liste des catégories existantes
      categoryList.innerHTML = "";

      // Récupérez les catégories déjà présentes dans la ligne actuelle
      var existingCategories = getExistingCategories(symbolRow);

      // Filtrer et trier les catégories
      var newCategories = categories.filter(function (category) {
        return !existingCategories.includes(category.category_id.toString());
      });

      // Trier les catégories par ordre alphabétique
      newCategories.sort(function (a, b) {
        return a.category.localeCompare(b.category);
      });

      // Ajoutez les catégories triées à la liste
      newCategories.forEach(function (category) {
        var categoryItem = document.createElement("li");
        categoryItem.textContent = category.category;
        categoryItem.dataset.categoryId = category.category_id.toString(); // Utilisez l'ID de la catégorie comme attribut personnalisé

        // Ajoutez des écouteurs d'événements pour les clics et le survol
        categoryItem.addEventListener("click", handleCategoryClick);
        categoryItem.addEventListener("mouseover", handleCategoryMouseOver);
        categoryItem.addEventListener("mouseout", handleCategoryMouseOut);

        categoryList.appendChild(categoryItem);
      });

      // Affichez la modal
      symbolRow.querySelector(".modal").style.display = "block";
    })
    .catch(function (error) {
      alert(error.message);
    });
}

function handleCategoryClick() {
  var categoryId = this.dataset.categoryId;
  var symbolRow = this.closest("tr");
  addCategoryToSymbol(symbolRow, categoryId)
    .then(function(data) {
      console.log(data);
      closeModal(); // Fermer la modal après l'ajout de la catégorie
      // Vous pouvez effectuer d'autres actions après l'ajout de la catégorie
    })
    .catch(function(error) {
      console.error("Erreur lors de l'ajout de la catégorie :", error);
    });
}


function handleCategoryMouseOver() {
  // Code à exécuter lorsque la souris survole la catégorie
  this.classList.add("category-hover");
}

function handleCategoryMouseOut() {
  // Code à exécuter lorsque la souris quitte la catégorie
  this.classList.remove("category-hover");
}





function getExistingCategories(symbolRow) {
  const categoryItems = symbolRow.querySelectorAll('.category-list li');
  const categories = [];
  categoryItems.forEach(item => {
    if (item.dataset.categoryId) {
      categories.push(item.dataset.categoryId);
    }
  });
  return categories;
}



function closeModal() {
  var modals = document.querySelectorAll(".modal");
  for (var i = 0; i < modals.length; i++) {
    var modal = modals[i];
    modal.style.display = "none";
  }
}


	var addCategoryButtons = document.querySelectorAll(".add-category-btn");
	addCategoryButtons.forEach(function (button) {
		button.addEventListener("click", openModal);
	});

	document.addEventListener("click", function (event) {
		var modals = document.querySelectorAll(".modal");
		for (var i = 0; i < modals.length; i++) {
			var modal = modals[i];
			if (event.target === modal) {
				modal.style.display = "none";
			}
		}
	});




	// _______________________________________________________________
	// Récupérer tous les boutons d'enregistrement
	const saveButtons = document.querySelectorAll('.save-btn');

	// Ajouter un écouteur d'événement à chaque bouton d'enregistrement
	saveButtons.forEach(button => {
		button.addEventListener('click', () => {
			const symbolId = button.dataset.id;
			const rowData = getRowData(button.parentNode.parentNode);
			rowData.categories = getCategories(button.parentNode.parentNode);
			rowData.keywords = getKeywords(button.parentNode.parentNode);

			// Appeler l'API pour mettre à jour le symbole
			updateSymbol(symbolId, rowData);
		});
	});

	// Récupérer tous les boutons de suppression
	const deleteButtons = document.querySelectorAll('.delete-btn');

	// Ajouter un écouteur d'événement à chaque bouton de suppression
	deleteButtons.forEach(button => {
		button.addEventListener('click', () => {
			const symbolId = button.dataset.id;
			// Confirmer la suppression avant de procéder
			if (confirm('Êtes-vous sûr de vouloir supprimer ce symbole ?')) {
				// Effectuer l'appel à l'API pour supprimer le symbole
				deleteSymbol(symbolId);
			}
		});
	});

	// Fonction pour récupérer les données de la ligne modifiée
	function getRowData(row) {
		const nameFile = row.querySelector('[data-field="file_name"] .editable-content').textContent.trim();
		const size = row.querySelector('[data-field="size"] .editable-content').textContent.trim();
		const active = row.querySelector('[data-field="active"] .editable-content').textContent.trim();
		return {
			file_name: nameFile,
			size: size,
			active: active
		};
	}

	// Fonction pour récupérer les catégories d'un symbole
	function getCategories(symbolRow) {
		const categoryItems = symbolRow.querySelectorAll('.category-list li');
		const categories = [];
		categoryItems.forEach(item => {
			if (item.querySelector('span')) {
				categories.push({
					id: item.dataset.categoryId,
					category: item.querySelector('span').textContent
				});
			}
		});
		return categories;
	}

	// Fonction pour récupérer les mots-clés d'un symbole
	function getKeywords(symbolRow) {
		const keywordItems = symbolRow.querySelectorAll('.keyword-list li');
		const keywords = [];
		keywordItems.forEach(item => {
			if (item.querySelector('span')) {
				keywords.push({
					id: item.dataset.keywordId,
					keyword: item.querySelector('span').textContent
				});
			}
		});
		return keywords;
	}

	// Fonction pour mettre à jour le symbole via l'API
	function updateSymbol(symbolId, data) {
		const url = `<?= $apiBaseURL; ?>/symbols/${symbolId}`;
		fetch(url, {
			method: 'PUT',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(data)
		})
			.then(response => response.json())
			.then(data => {
				// Afficher un message ou actualiser la page après la mise à jour
				console.log(data);
				// Actualiser la ligne modifiée dans le tableau
				const symbolRow = document.querySelector(`tr[data-id="${symbolId}"]`);
				if (symbolRow) {
					symbolRow.querySelector('[data-field="file_name"]').textContent = data.file_name;
					symbolRow.querySelector('[data-field="size"]').textContent = data.size;
					symbolRow.querySelector('[data-field="active"]').textContent = data.active;
					// Mettre à jour les catégories affichées dans la colonne correspondante
					symbolRow.querySelector('[data-field="categories"]').innerHTML = renderCategories(data.categories);
					// Mettre à jour les mots-clés affichés dans la colonne correspondante
					symbolRow.querySelector('[data-field="keywords"]').innerHTML = renderKeywords(data.keywords);
				}
			})
			.catch(error => {
				console.error('Erreur lors de la mise à jour du symbole:', error);
			});
	}

	// Fonction pour supprimer le symbole via l'API
	function deleteSymbol(symbolId) {
		const url = `<?= $apiBaseURL; ?>/symbols/${symbolId}`;
		fetch(url, {
			method: 'DELETE',
		})
			.then(response => response.json())
			.then(data => {
				// Afficher un message ou actualiser la page après la suppression
				console.log(data);
				// Actualiser la page pour refléter les modifications
				location.reload();
			})
			.catch(error => {
				console.error('Erreur lors de la suppression du symbole:', error);
			});
	}

	// Fonction pour supprimer une catégorie d'un symbole via l'API
	function removeCategoryFromSymbol(symbolId, categoryId) {
		const url = `<?= $apiBaseURL; ?>/symbols/${symbolId}/categories/${categoryId}`;
		return fetch(url, {
			method: 'DELETE'
		})
			.then(response => response.json())
			.then(data => {
				console.log(data);
			});
	}

	// Fonction pour ajouter une catégorie à un symbole via l'API
	function addCategoryToSymbol(symbolRow, categoryId) {
  var symbolId = symbolRow.querySelector(".save-btn").dataset.id;
  var url = "http://www.sitetest.local/illustrator2_project/back/index.php/symbols/" + symbolId + "/categories/" + categoryId;
  return fetch(url, {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({})
  })
  .then(function(response) {
    if (response.ok) {
      return response.json();
    }
    throw new Error("Erreur lors de l'ajout de la catégorie.");
  });
}




	// Fonction pour supprimer un mot-clé d'un symbole via l'API
	function removeKeywordFromSymbol(symbolId, keywordId) {
		const url = `<?= $apiBaseURL; ?>/symbols/${symbolId}/keywords/${keywordId}`;
		return fetch(url, {
			method: 'DELETE'
		})
			.then(response => response.json())
			.then(data => {
				console.log(data);
			});
	}

	// Fonction pour ajouter un mot-clé à un symbole via l'API
	function addKeywordToSymbol(symbolId, keyword) {
		const url = `<?= $apiBaseURL; ?>/symbols/${symbolId}/keywords`;
		return fetch(url, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify({ keyword })
		})
			.then(response => response.json())
			.then(data => {
				console.log(data);
			});
	}

	// Supprimer visuellement une catégorie d'un symbole
	function removeCategoryFromView(categoryItem) {
		const symbolRow = categoryItem.closest('tr');
		const categoryList = symbolRow.querySelector('.category-list');
		categoryItem.remove();

		// Mettre à jour la classe de la première catégorie s'il y en a une
		const firstCategory = categoryList.querySelector('li');
		if (firstCategory) {
			firstCategory.classList.add('first-category');
		}
	}

	// Supprimer visuellement un mot-clé d'un symbole
	function removeKeywordFromView(keywordItem) {
		keywordItem.remove();
	}

	// Rendu HTML pour les catégories
	function renderCategories(categories) {
		return categories
			.map(category => `
		<li data-category-id="${category.id}">
		  <span>${category.category}</span>
		  <button class="delete-category-btn">&times;</button>
		</li>
	  `)
			.join('');
	}

	// Rendu HTML pour les mots-clés
	function renderKeywords(keywords) {
		return keywords
			.map(keyword => `
		<li data-keyword-id="${keyword.id}">
		  <span>${keyword.keyword}</span>
		  <button class="delete-keyword-btn">&times;</button>
		</li>
	  `)
			.join('');
	}

	// Sélectionnez tous les boutons de suppression de catégorie
	const removeCategoryButtons = document.querySelectorAll('.delete-category-btn');

	// Ajoutez un écouteur d'événement à chaque bouton de suppression de catégorie
	removeCategoryButtons.forEach(button => {
		button.addEventListener('click', function (event) {
			event.stopPropagation(); // Arrêter la propagation de l'événement

			const categoryItem = this.parentNode;
			const categoryId = categoryItem.dataset.categoryId;
			const symbolRow = this.closest('tr');
			const symbolId = symbolRow.querySelector('.save-btn').dataset.id;

			// Appeler l'API pour supprimer la catégorie du symbole
			removeCategoryFromSymbol(symbolId, categoryId)
				.then(() => {
					// Supprimer l'élément de catégorie de l'affichage
					removeCategoryFromView(categoryItem);
				})
				.catch(error => {
					console.error('Erreur lors de la suppression de la catégorie:', error);
				});
		});
	});



	// Mettre à jour la liste des catégories d'un symbole
	function updateCategoryList(symbolRow, categories) {
		const categoryList = symbolRow.querySelector('.category-list');
		categoryList.innerHTML = renderCategories(categories);

		// Ajoutez un écouteur d'événement pour la suppression de catégorie
		const deleteCategoryButtons = categoryList.querySelectorAll('.delete-category-btn');
		deleteCategoryButtons.forEach(button => {
			button.addEventListener('click', function (event) {
				event.stopPropagation(); // Arrêter la propagation de l'événement

				const categoryItem = this.parentNode;
				const categoryId = categoryItem.dataset.categoryId;
				const symbolRow = this.closest('tr');
				const symbolId = symbolRow.querySelector('.save-btn').dataset.id;

				// Appeler l'API pour supprimer la catégorie du symbole
				removeCategoryFromSymbol(symbolId, categoryId)
					.then(() => {
						// Supprimer l'élément de catégorie de l'affichage
						removeCategoryFromView(categoryItem);
					})
					.catch(error => {
						console.error('Erreur lors de la suppression de la catégorie:', error);
					});
			});
		});
	}


	// Supprimer visuellement un mot-clé d'un symbole
	function removeKeywordFromView(keywordItem) {
		keywordItem.remove();
	}

	// Sélectionnez tous les boutons de suppression de mot-clé
	const removeKeywordButtons = document.querySelectorAll('.delete-keyword-btn');

	// Ajoutez un écouteur d'événement à chaque bouton de suppression de mot-clé
	removeKeywordButtons.forEach(button => {
		button.addEventListener('click', function (event) {
			event.stopPropagation(); // Arrêter la propagation de l'événement

			const keywordItem = this.parentNode;
			const keywordId = keywordItem.dataset.keywordId;
			const symbolRow = this.closest('tr');
			const symbolId = symbolRow.querySelector('.save-btn').dataset.id;

			// Appeler l'API pour supprimer le mot-clé du symbole
			removeKeywordFromSymbol(symbolId, keywordId)
				.then(() => {
					// Supprimer l'élément de mot-clé de l'affichage
					removeKeywordFromView(keywordItem);
				})
				.catch(error => {
					console.error('Erreur lors de la suppression du mot-clé:', error);
				});
		});
	});

	// Ajouter un mot-clé à un symbole
	function addKeyword(symbolRow, symbolId, keyword) {
		addKeywordToSymbol(symbolId, keyword)
			.then(() => {
				// Mettre à jour la liste des mots-clés
				const updatedKeywords = symbolRow.querySelectorAll('.keyword-list li');
				updateKeywordList(symbolRow, updatedKeywords);
			})
			.catch(error => {
				console.error('Erreur lors de l\'ajout du mot-clé:', error);
			});
	}

	// Mettre à jour la liste des mots-clés d'un symbole
	function updateKeywordList(symbolRow, keywords) {
		const keywordList = symbolRow.querySelector('.keyword-list');
		keywordList.innerHTML = renderKeywords(keywords);

		// Ajoutez un écouteur d'événement pour la suppression de mot-clé
		const deleteKeywordButtons = keywordList.querySelectorAll('.delete-keyword-btn');
		deleteKeywordButtons.forEach(button => {
			button.addEventListener('click', function (event) {
				event.stopPropagation(); // Arrêter la propagation de l'événement

				const keywordItem = this.parentNode;
				const keywordId = keywordItem.dataset.keywordId;
				const symbolRow = this.closest('tr');
				const symbolId = symbolRow.querySelector('.save-btn').dataset.id;

				// Appeler l'API pour supprimer le mot-clé du symbole
				removeKeywordFromSymbol(symbolId, keywordId)
					.then(() => {
						// Supprimer l'élément de mot-clé de l'affichage
						removeKeywordFromView(keywordItem);
					})
					.catch(error => {
						console.error('Erreur lors de la suppression du mot-clé:', error);
					});
			});
		});
	}





</script>