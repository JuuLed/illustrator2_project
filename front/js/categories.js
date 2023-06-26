//*********** PAGE CATEGORIES ***********//
//_________________ Génération du tableau via ajax api categories _________________
apiGet('/categories', function (categories) {
	var tableContent = categories.map(function (category) {
	  var translations = category.translations;
	  var row = `
		<tr>
			<td class="editable" contenteditable="false" data-id="${category.category_id}" data-field="category">${category.category}</td>
			<td class="editable" contenteditable="false" data-id="${category.category_id}" data-field="DE">${translations.DE}</td>
			<td class="editable" contenteditable="false" data-id="${category.category_id}" data-field="EN">${translations.EN}</td>
			<td class="editable" contenteditable="false" data-id="${category.category_id}" data-field="ES">${translations.ES}</td>
			<td class="editable" contenteditable="false" data-id="${category.category_id}" data-field="FR">${translations.FR}</td>
			<td class="editable" contenteditable="false" data-id="${category.category_id}" data-field="IT">${translations.IT}</td>
			<td class="editable" contenteditable="false" data-id="${category.category_id}" data-field="PT">${translations.PT}</td>
		  	<td><button class="btn-delete" data-id="${category.category_id}">Supprimer</button></td>
		</tr>
	  `;
	  return row;
	}).join('');
  
	$('.table-categories').append(tableContent);
  
	// Ajouter un gestionnaire d'événement pour le bouton de suppression
	$('.btn-delete').on('click', function () {
	  var categoryId = $(this).data('id');
	  // Appeler la fonction de suppression avec l'identifiant de la catégorie
	  deleteCategory(categoryId);
	});
  });

//_________________ MODAL D'AJOUT _________________ 
$(document).ready(function(){
	$(".header-symbols button").click(function(){
	  $("#myModal").css("display", "block");
	});
  
	$(window).click(function(event) {
	  if (event.target.id == "myModal") {
		$("#myModal").css("display", "none");
	  }
	});
  });
  

  $(document).on('submit', '#add-category-form', function(e) {
	e.preventDefault();
  
  
	var categoryData = {
	  category: $("input[name='category']").val(),
	  translations: {
		EN: $("input[name='EN']").val(),
		DE: $("input[name='DE']").val(),
		ES: $("input[name='ES']").val(),
		FR: $("input[name='FR']").val(),
		IT: $("input[name='IT']").val(),
		PT: $("input[name='PT']").val()
	  }
	};
  
	$.ajax({
	  url: apiBaseURL + '/categories', 
	  type: 'POST',
	  data: JSON.stringify(categoryData),
	  contentType: 'application/json',
	  success: function(response) {
		console.log("Success: ", response);
		$("#myModal").css("display", "none");
		apiGet('/categories', function(categories) {
			// Mise à jour de la liste des catégories affichée sur la page
			var tableContent = categories.map(function (category) {
			  var translations = category.translations;
			  var row = `
				<tr>
					<td class="editable" contenteditable="false" data-id="${category.category_id}" data-field="category">${category.category}</td>
					<td class="editable" contenteditable="false" data-id="${category.category_id}" data-field="DE">${translations.DE}</td>
					<td class="editable" contenteditable="false" data-id="${category.category_id}" data-field="EN">${translations.EN}</td>
					<td class="editable" contenteditable="false" data-id="${category.category_id}" data-field="ES">${translations.ES}</td>
					<td class="editable" contenteditable="false" data-id="${category.category_id}" data-field="FR">${translations.FR}</td>
					<td class="editable" contenteditable="false" data-id="${category.category_id}" data-field="IT">${translations.IT}</td>
					<td class="editable" contenteditable="false" data-id="${category.category_id}" data-field="PT">${translations.PT}</td>
					<td><button class="btn-delete" data-id="${category.category_id}">Supprimer</button></td>
				</tr>
			  `;
			  return row;
			}).join('');
  
			$('#category-rows').html(tableContent);
  
			// Ajouter un gestionnaire d'événement pour le bouton de suppression
			$('.btn-delete').on('click', function () {
			  var categoryId = $(this).data('id');
			  // Appeler la fonction de suppression avec l'identifiant de la catégorie
			  deleteCategory(categoryId);
			});

			// Ajouter la nouvelle catégorie au draggable
			var categoryOrderList = $('.category-order-list');
			var categoryOrderItem = $('<div>', {
			  class: 'category-order-item',
			  'data-category-id': response.category_id
			}).appendTo(categoryOrderList);
	
			$('<div>', {
			  class: 'order-number',
			  text: categories.length
			}).appendTo(categoryOrderItem);
	
			$('<div>', {
			  class: 'category-name',
			  text: response.category
			}).appendTo(categoryOrderItem);
	
			// Mettre à jour les numéros d'ordre dans le draggable
			updateOrderNumbers();
		  });
		},
  
	  error: function(error) {
		console.log("Error: ", error);
	  }
	});
  });
//_________________ Editable des cellules du tableau _________________
$(document).on('dblclick', '.editable', function () {
    $(this).attr('contenteditable', 'true').focus();
});

$(document).on('blur', '.editable', function () {
    submitChanges($(this));
}).on('keydown', '.editable', function (e) {
    if (e.which === 13) { // Touche Entrée
        e.preventDefault(); // Prévenir le saut de ligne
        $(this).blur();
    }
});

function submitChanges(element) {
    var dataId = element.data('id');
    var field = element.data('field');
    var newData = {};
    newData[field] = element.text();

    var url;
    if (field === 'category') {
        url = apiBaseURL + '/categories/' + dataId;
    } else {
        url = apiBaseURL + '/translations/categories/' + dataId;
    }

    $.ajax({
        url: url,
        type: 'PUT',
        data: JSON.stringify(newData),
        contentType: 'application/json',
        success: function(response) {
            console.log("Success: ", response);
        },
        error: function(error) {
            console.log("Error: ", error);
        }
    });

    element.attr('contenteditable', 'false');
}

  
//_________________ Ordre des catégories Draggable _________________


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

//_________________ Suppression d'une categorie _________________
function deleteCategory(categoryId) {
	$.ajax({
	  url: apiBaseURL + '/categories/' + categoryId,
	  type: 'DELETE',
	  success: function(result) {
		console.log("Cette catégorie à supprimée avec succès : ", categoryId);
		// Supprimer la ligne correspondante dans le tableau
		var rowToRemove = $('[data-id="' + categoryId + '"]').closest('tr');
		rowToRemove.remove();
  
		// Supprimer la case correspondante dans le draggable
		var itemToRemove = $('.category-order-item[data-category-id="' + categoryId + '"]');
		itemToRemove.remove();
		
		// Mettre à jour les numéros d'ordre dans le draggable
		updateOrderNumbers();
	  },
	  error: function(jqXHR, textStatus, errorThrown) {
		console.log("Erreur lors de la suppression de la catégorie", textStatus, errorThrown);
	  }
	});
  }
  