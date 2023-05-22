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

// Récupérer la liste des symboles depuis l'API
$symbols = apiGet($apiBaseURL);

// Fonction pour récupérer les catégories d'un symbole
function getCategories($symbol) {
    return implode(', ', array_column($symbol['categories'], 'category_name'));
}

// Fonction pour récupérer les mots-clés d'un symbole
function getKeywords($symbol) {
    return implode(', ', array_column($symbol['keywords'], 'keyword'));
}

// Afficher la liste des symboles dans un tableau
echo '<table>';
echo '<tr>';
echo '<th>Nom du fichier</th>';
echo '<th>Taille</th>';
echo '<th>Actif</th>';
echo '<th>Catégories</th>';
echo '<th>Mots-clés</th>';
echo '<th>Actions</th>';
echo '</tr>';

foreach ($symbols as $symbol) {
    echo '<tr>';
    echo '<td contenteditable="true" data-field="name_file">' . $symbol['name_file'] . '</td>';
    echo '<td contenteditable="true" data-field="size">' . $symbol['size'] . '</td>';
    echo '<td contenteditable="true" data-field="active">' . $symbol['active'] . '</td>';
    echo '<td>' . getCategories($symbol) . '</td>';
    echo '<td>' . getKeywords($symbol) . '</td>';
    echo '<td>';
    echo '<button class="save-btn" data-id="' . $symbol['symbol_id'] . '">Enregistrer</button> ';
    echo '<button class="delete-btn" data-id="' . $symbol['symbol_id'] . '">Supprimer</button>';
    echo '</td>';
    echo '</tr>';
}

echo '</table>';
?>

<script>
    // Récupérer tous les boutons d'enregistrement
    const saveButtons = document.querySelectorAll('.save-btn');

    // Ajouter un écouteur d'événement à chaque bouton d'enregistrement
    saveButtons.forEach(button => {
        button.addEventListener('click', () => {
            const symbolId = button.dataset.id;
            const rowData = getRowData(button.parentNode.parentNode);
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
        const nameFile = row.querySelector('[data-field="name_file"]').textContent;
        const size = row.querySelector('[data-field="size"]').textContent;
        const active = row.querySelector('[data-field="active"]').textContent;
        return {
            name_file: nameFile,
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
                    symbolRow.querySelector('[data-field="name_file"]').textContent = data.name_file;
                    symbolRow.querySelector('[data-field="size"]').textContent = data.size;
                    symbolRow.querySelector('[data-field="active"]').textContent = data.active;
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
</script>
