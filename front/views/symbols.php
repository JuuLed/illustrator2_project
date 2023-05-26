<style>
  /* mise en page du tableau */
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
  th:not(:last-child) {
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

  /* mise en page pour les case catégories */
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
    color: #f44336;
  }

  .category-list .delete-category-btn:hover {
    background-color: #f44336;
    color: white;
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

function getCategories($symbol)
{
  return implode(', ', array_column($symbol['categories'], 'category'));
}

function getKeywords($symbol)
{
  return implode(', ', array_column($symbol['keywords'], 'keyword'));
}
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

  // Ajoutez un écouteur d'événement à chaque bouton de suppression de catégorie
  const deleteCategoryButtons = document.querySelectorAll('.delete-category-btn');

  deleteCategoryButtons.forEach(button => {
    button.addEventListener('click', () => {
      const categoryId = button.parentNode.dataset.categoryId;
      const symbolId = button.closest('tr').querySelector('.save-btn').dataset.id;
      removeCategoryFromSymbol(symbolId, categoryId)
        .then(() => {
          const categoryItem = button.parentNode;
          categoryItem.parentNode.removeChild(categoryItem);
        })
        .catch(error => {
          console.error('Erreur lors de la suppression de la catégorie:', error);
        });
    });
  });

  // Fonction pour ajouter une catégorie à un symbole
  function addCategory(symbolId, category) {
    const symbolRow = document.querySelector(`tr[data-id="${symbolId}"]`);
    const categoryList = symbolRow.querySelector('.category-list');
    const categoryItem = document.createElement('li');
    categoryItem.dataset.categoryId = category.id;
    const categorySpan = document.createElement('span');
    categorySpan.textContent = category.category;
    const deleteButton = document.createElement('button');
    deleteButton.className = 'delete-category-btn';
    deleteButton.innerHTML = '&times;';
    categoryItem.appendChild(categorySpan);
    categoryItem.appendChild(deleteButton);
    categoryList.appendChild(categoryItem);

    // Ajoutez un écouteur d'événement pour la suppression de catégorie
    deleteButton.addEventListener('click', () => {
      const categoryId = categoryItem.dataset.categoryId;
      removeCategoryFromSymbol(symbolId, categoryId);
      categoryItem.parentNode.removeChild(categoryItem);
    });
  }

  // Fonction pour supprimer visuellement une catégorie d'un symbole
  function removeCategoryFromView(categoryItem) {
    categoryItem.parentNode.removeChild(categoryItem);
  }

  // Ajoutez un écouteur d'événement pour l'ajout de catégorie
  const addCategoryButtons = document.querySelectorAll('.add-category-btn');

  addCategoryButtons.forEach(button => {
    button.addEventListener('click', () => {
      const symbolId = button.parentNode.parentNode.dataset.id;
      const categoryInput = button.parentNode.querySelector('.category-input');
      const category = categoryInput.value.trim();

      if (category !== '') {
        addCategoryToSymbol(symbolId, category)
          .then(data => {
            addCategory(symbolId, data);
            categoryInput.value = '';
          })
          .catch(error => {
            console.error('Erreur lors de l\'ajout de la catégorie:', error);
          });
      }
    });
  });
</script>
