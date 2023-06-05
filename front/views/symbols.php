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

  /* Styles pour la modal */
  	.modal {
		display: none; /* Par défaut, la modal est cachée */
		position: fixed;
		z-index: 1;
		left: 0;
		top: 0;
		width: 100%;
		height: 100%;
		overflow: auto;
		background-color: rgba(0, 0, 0, 0.4); /* Fond semi-transparent */
	}

	.modal-content-categoies {
		background-color: #fefefe;
		margin: 15% auto;
		padding: 20px;
		border: 1px solid #888;
		width: 80%;
	}
  
</style>

<h1>SYMBOLS</h1>

<?php
// URL de base de l'API
$apiBaseURL = 'http://www.sitetest.local/illustrator2_project/back/index.php';

// Fonction pour effectuer une requête GET à l'API
function apiGet($url)
{
  global $apiBaseURL;
  $fullURL = $apiBaseURL . $url;
  $ch = curl_init($fullURL);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);
  curl_close($ch);
  return json_decode($response, true);
}

$symbols = apiGet('/symbols');


?>

<table class="table-symbols">
  <tr>
    <th>Nom du fichier</th>
    <th class="cln-size">Taille</th>
    <th class="cln-size">Actif</th>
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
      <td class="cln-size contenteditable" data-field="active">
        <div class="editable-content" contenteditable="true"><?= $symbol['active']; ?></div>
      </td>

      <td>
        <ul class="category-list">
          <?php foreach ($symbol['categories'] as $category) { ?>
            <li data-category-id="<?= $category['id']; ?>">
              <span><?= $category['category']; ?></span>
              <button class="delete-category-btn">&times;</button>
            </li>
          <?php } ?>
			<li class="add-btn">
				<button class="add-category-btn">+</button>
			</li>
        </ul>
      </td>

      <td>
        <ul class="keyword-list">
          <?php foreach ($symbol['keywords'] as $keyword) { ?>
            <li data-keyword-id="<?= $keyword['id']; ?>">
              <span><?= $keyword['keyword']; ?></span>
              <button class="delete-keyword-btn">&times;</button>
            </li>
          <?php } ?>
        </ul>
      </td>

      <td>
        <button class="save-btn" data-id="<?= $symbol['id']; ?>">Enregistrer</button>
        <button class="delete-btn" data-id="<?= $symbol['id']; ?>">Supprimer</button>
      </td>
    </tr>
  <?php } ?>
</table>

<div id="myModalCategories" class="modal">
	<div class="modal-content-categories">
		<h2>Ma Modal</h2>
		<p>Contenu de la modal...</p>
		<button id="closeModalCategories">Fermer</button>
	</div>
</div>

<!-- <div id="myModal" class="modal">
	<div class="modal-content">
		<h2>Ma Modal</h2>
		<p>Contenu de la modal...</p>
		<button id="closeModal">Fermer</button>
	</div>
</div> -->

<script>
// modals :

	// Récupérer les éléments
	var myModalCategories = document.getElementById("myModalCategories");
    var btn = document.getElementsByClassName("add-category-btn")[0];
    var closeBtn = document.getElementById("closeModalCategories");

    // Ouvrir la modal lorsque le bouton "+" est cliqué
    btn.onclick = function() {
		myModalCategories.style.display = "block";
    }

    // Fermer la modal lorsque le bouton "Fermer" est cliqué
    closeBtn.onclick = function() {
		myModalCategories.style.display = "none";
    }

    // Fermer la modal lorsque l'utilisateur clique en dehors de la modal
    window.onclick = function(event) {
      if (event.target == myModalCategories) {
        myModalCategories.style.display = "none";
      }
    }









// ______________________________________________________________________
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
      categories.push({
        id: item.dataset.categoryId,
        category: item.querySelector('span').textContent
      });
    });
    return categories;
  }

  // Fonction pour récupérer les mots-clés d'un symbole
  function getKeywords(symbolRow) {
    const keywordItems = symbolRow.querySelectorAll('.keyword-list li');
    const keywords = [];
    keywordItems.forEach(item => {
      keywords.push({
        id: item.dataset.keywordId,
        keyword: item.querySelector('span').textContent
      });
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
  function addCategoryToSymbol(symbolId, category) {
    const url = `<?= $apiBaseURL; ?>/symbols/${symbolId}/categories`;
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

  

  // Mettre à jour la liste des catégories d'un symbole
  function updateCategoryList(symbolRow, categories) {
    const categoryList = symbolRow.querySelector('.category-list');
    categoryList.innerHTML = renderCategories(categories);

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


  // Supprimer visuellement un mot-clé d'un symbole
  function removeKeywordFromView(keywordItem) {
    keywordItem.remove();
  }

  // Sélectionnez tous les boutons de suppression de mot-clé
  const removeKeywordButtons = document.querySelectorAll('.delete-keyword-btn');

  // Ajoutez un écouteur d'événement à chaque bouton de suppression de mot-clé
  removeKeywordButtons.forEach(button => {
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

  


  
</script>
