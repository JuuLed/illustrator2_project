//*********** PAGE CATEGORIES ***********//
//_________________ Génération du tableau via ajax api categories __________________
apiGet('/categories', function (categories) {
	var tableContent = categories.map(function (category) {
	  var translations = category.translations;
	  var row = `
		<tr>
		  <td>${category.category}</td>
		  <td>${translations.DE}</td>
		  <td>${translations.EN}</td>
		  <td>${translations.ES}</td>
		  <td>${translations.FR}</td>
		  <td>${translations.IT}</td>
		  <td>${translations.PT}</td>
		  <td><button class="btn-delete" data-id="${category.category_id}">Supprimer</button></td>
		</tr>
	  `;
	  return row;
	}).join('');
  
	$('.table-symbols').append(tableContent);
  
	// Ajouter un gestionnaire d'événement pour le bouton de suppression
	$('.btn-delete').on('click', function () {
	  var categoryId = $(this).data('id');
	  // Appeler la fonction de suppression avec l'identifiant de la catégorie
	  deleteCategory(categoryId);
	});
  });


//_________________________ Ordre des catégories _______________________


function updateOrderNumbers() {
    $('.category-order-item').each(function(index) {
        $(this).find('.order-number').text(index + 1);
    });
}

$(document).ready(function() {
	$('.category-order-list').hide();

    apiGet('/categories', function(categories) {
        var categoryOrderList = $('.category-order-list');
        categories.forEach(function(category, index) {
            var categoryName = category.category;
            var categoryId = category.category_id;
            
            var categoryOrderItem = $('<div>', {
                class: 'category-order-item',
                'data-category-id': categoryId
            }).appendTo(categoryOrderList);

            $('<div>', {
                class: 'order-number',
                text: index + 1
            }).appendTo(categoryOrderItem);

            $('<div>', {
                class: 'category-name',
                text: categoryName
            }).appendTo(categoryOrderItem);
        });

        new Sortable(categoryOrderList.get(0), {
            animation: 500,
            onUpdate: function(event) {
                updateOrderNumbers();

                $('.category-order-item').each(function(index) {
                    var categoryId = $(this).data('category-id');
                    var categoryName = $(this).find('.category-name').text();
                    
                    var url = apiBaseURL + "/categories/" + categoryId; 
                    var jsonData = {
                        category: categoryName,
                        order: index + 1 
                    };
                
                    $.ajax({
                        url: url,
                        type: 'PUT',
                        data: JSON.stringify(jsonData),
                        contentType: 'application/json',
                        success: function(response) {
							console.log("La catégorie " + categoryName + " a été mise à jour avec succès à la position " + (index + 1) + ".");
						},
                        error: function(xhr, status, error) {
							console.log("Erreur lors de la mise à jour de la catégorie " + categoryName + ":", status, error);
						}
                    });
                }); 
            }
        });
    });
    
    $('.category-order-bar').on('click', function() {
        $(this).toggleClass('open');
		$('.category-order-list').slideToggle();
    });
});
