<style>
	table {
		width: 100%;
		border-collapse: collapse;
	}

	th, td {
		padding: 8px;
		text-align: left;
		border-bottom: 1px solid #ddd;
	}

	th {
		background-color: #f2f2f2;
	}

	tr:hover {
		background-color: #f5f5f5;
	}

	.save-btn, .delete-btn {
		padding: 4px 8px;
		background-color: #4CAF50;
		color: white;
		border: none;
		cursor: pointer;
		margin-right: 4px;
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








	.category-list {
  list-style-type: none;
  padding: 0;
  margin: 0;
}

.category-list li {
  display: inline-block;
  position: relative;
  margin: 4px;
}

.category-list span {
  display: inline-block;
  padding: 4px 8px;
  background-color: #f2f2f2;
  border-radius: 1vh;
  vertical-align: middle;

  border: 1px solid grey;
}

.category-list .delete-category-btn {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 2.5vh;
  height: 2.5vh;
  background-color: #ccc;
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
  color:#f44336;

  
}

.category-list .delete-category-btn:hover {
  background-color: #f44336;
  color: white;
}



</style>

<h1>SYMBOLS</h1>

<?php
// URL de base de l'API
$apiBaseURL = 'http://www.sitetest.local/illustrator2_project/back/index.php/symbols';

// Fonction pour effectuer une requête GET à l'API
function apiGet($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

$symbols = apiGet($apiBaseURL);

function getCategories($symbol) {
    return implode(', ', array_column($symbol['categories'], 'category'));
}

function getKeywords($symbol) {
    return implode(', ', array_column($symbol['keywords'], 'keyword'));
}
?>

<table class="table-symbols">
	<tr>
		<th>Nom du fichier</th>
		<th class="cln-size">Taille</th>
		<th class="cln-actif">Actif</th>
		<th>Catégories</th>
		<th>Mots-clés</th>
		<th>Actions</th>
	</tr>

<?php foreach ($symbols as $symbol) { ?>
	<tr>
		<td class="contenteditable" data-field="file_name">
			<div class="editable-content" contenteditable="true"><?= $symbol['file_name']; ?></div>
		</td>
		<td class="cln-size contenteditable" data-field="size">
			<div class="editable-content" contenteditable="true"><?= $symbol['size']; ?></div>
		</td>
		<td class="cln-actif contenteditable" data-field="active">
			<div class="editable-content" contenteditable="true"><?= $symbol['active']; ?></div>
		</td>
		<!-- <td><?= getCategories($symbol); ?></td> -->


		<td>
			<ul class="category-list">
				<?php foreach ($symbol['categories'] as $category) { ?>
				<li data-category-id="<?= $category['id']; ?>">
					<span><?= $category['category']; ?></span>
					<button class="delete-category-btn">&times;</button>
				</li>
				<?php } ?>
			</ul>
		</td>



		<td><?= getKeywords($symbol); ?></td>
		<td>
			<button class="save-btn" data-id="<?= $symbol['id']; ?>">Enregistrer</button> 
			<button class="delete-btn" data-id="<?= $symbol['id']; ?>">Supprimer</button>
		</td>
    </tr>
<?php } ?>

</table>

<script>
    // Récupérer tous les boutons d'enregistrement
    const saveButtons = document.querySelectorAll('.save-btn');

    // Ajouter un écouteur d'événement à chaque bouton d'enregistrement
    saveButtons.forEach(button => {
        button.addEventListener('click', () => {
            const symbolId = button.dataset.id;
            const rowData = getRowData(button.parentNode.parentNode);
			rowData.categories = []; // Ajoutez ici les catégories mises à jour
			rowData.keywords = []; // Ajoutez ici les mots-clés mis à jour

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


    // Fonction pour mettre à jour le symbole via l'API
	function updateSymbol(symbolId, data) {
		const url = '<?php echo $apiBaseURL; ?>/' + symbolId;
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
					symbolRow.querySelector('[data-field="categories"]').textContent = getCategories(data);
					// Mettre à jour les mots-clés affichés dans la colonne correspondante
					symbolRow.querySelector('[data-field="keywords"]').textContent = getKeywords(data);
				}
			})
			.catch(error => {
				console.error('Erreur lors de la mise à jour du symbole:', error);
			});
	}


    // Fonction pour supprimer le symbole via l'API
    function deleteSymbol(symbolId) {
        const url = '<?php echo $apiBaseURL; ?>/' + symbolId;
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






	// ________________________________________________________________

	// Sélectionnez tous les boutons de suppression de catégorie
	const removeCategoryButtons = document.querySelectorAll('.remove-category-btn');

	// Ajoutez un écouteur d'événement à chaque bouton de suppression de catégorie
	removeCategoryButtons.forEach(button => {
		button.addEventListener('click', () => {
			const categoryItem = button.parentNode;
			const category = categoryItem.firstChild.textContent.trim();
			const symbolRow = button.closest('tr');
			const symbolId = symbolRow.querySelector('.save-btn').dataset.id;

			// Appeler l'API pour supprimer la catégorie du symbole
			removeCategoryFromSymbol(symbolId, category)
				.then(() => {
					// Supprimer l'élément de catégorie de l'affichage
					categoryItem.remove();
				})
				.catch(error => {
					console.error('Erreur lors de la suppression de la catégorie:', error);
				});
		});
	});

	// Sélectionnez tous les boutons d'ajout de catégorie
	const addCategoryButtons = document.querySelectorAll('.add-category-btn');

	// Ajoutez un écouteur d'événement à chaque bouton d'ajout de catégorie
	addCategoryButtons.forEach(button => {
		button.addEventListener('click', () => {
			const newCategoryInput = button.previousSibling;
			const category = newCategoryInput.value.trim();
			const symbolRow = button.closest('tr');
			const symbolId = symbolRow.querySelector('.save-btn').dataset.id;

			// Vérifiez si la catégorie n'est pas vide
			if (category !== '') {
				// Appeler l'API pour ajouter la catégorie au symbole
				addCategoryToSymbol(symbolId, category)
					.then(() => {
						// Créer un nouvel élément de liste pour afficher la catégorie ajoutée
						const categoryList = symbolRow.querySelector('.category-list');
						const newCategoryItem = document.createElement('li');
						newCategoryItem.textContent = category;
						const removeCategoryButton = document.createElement('button');
						removeCategoryButton.className = 'remove-category-btn';
						removeCategoryButton.innerHTML = '&times;';
						newCategoryItem.appendChild(removeCategoryButton);
						categoryList.appendChild(newCategoryItem);

						// Réinitialiser l'entrée de la nouvelle catégorie
						newCategoryInput.value = '';
					})
					.catch(error => {
						console.error('Erreur lors de l\'ajout de la catégorie:', error);
					});
			}
		});
	});

	// Fonction pour supprimer une catégorie d'un symbole via l'API
	// Fonction pour supprimer une catégorie d'un symbole via l'API
	function removeCategoryFromSymbol(symbolId, category) {
		const url = `${apiBaseURL}/${symbolId}/categories/${category}`;
		return fetch(url, {
			method: 'DELETE'
		})
			.then(response => response.json())
			.then(data => {
				// Supprimer la catégorie de l'affichage
				const symbolRow = document.querySelector(`tr[data-id="${symbolId}"]`);
				const categoryItem = symbolRow.querySelector(`[data-category="${category}"]`);
				if (categoryItem) {
					categoryItem.remove();
				}
				console.log(data);
			});
	}


	// Fonction pour ajouter une catégorie à un symbole via l'API
	function addCategoryToSymbol(symbolId, category) {
		const url = `${apiBaseURL}/${symbolId}/categories`;
		return fetch(url, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify({ category })
		})
			.then(response => response.json())
			.then(data => {
				console.log(data);
			});
	}

</script>
