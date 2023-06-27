//*********** PAGE KEYWORDS ***********//
//_________________ Génération du tableau via ajax api keywords _________________
apiGet('/keywords', function (keywords) {
	var tableContent = keywords.map(function (keyword) {
	  var translations = keyword.translations;
	  var row = `
		<tr>
			<td class="editable" contenteditable="false" data-id="${keyword.keyword_id}" data-field="keyword">${keyword.keyword}</td>
			<td class="editable" contenteditable="false" data-id="${keyword.keyword_id}" data-field="DE">${translations.DE}</td>
			<td class="editable" contenteditable="false" data-id="${keyword.keyword_id}" data-field="EN">${translations.EN}</td>
			<td class="editable" contenteditable="false" data-id="${keyword.keyword_id}" data-field="ES">${translations.ES}</td>
			<td class="editable" contenteditable="false" data-id="${keyword.keyword_id}" data-field="FR">${translations.FR}</td>
			<td class="editable" contenteditable="false" data-id="${keyword.keyword_id}" data-field="IT">${translations.IT}</td>
			<td class="editable" contenteditable="false" data-id="${keyword.keyword_id}" data-field="PT">${translations.PT}</td>
		  	<td><button class="btn-delete" data-id="${keyword.keyword_id}">Supprimer</button></td>
		</tr>
	  `;
	  return row;
	}).join('');
  
	$('#keyword-rows').append(tableContent);
  
	// Ajouter un gestionnaire d'événement pour le bouton de suppression
	$('.btn-delete').on('click', function () {
	  var keywordId = $(this).data('id');
	  // Appeler la fonction de suppression avec l'identifiant du mot-clé
	  deleteKeyword(keywordId);
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

$(document).on('submit', '#add-keyword-form', function(e) {
    e.preventDefault();

    var keywordData = {
        keyword: $("input[name='keyword']").val(),
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
        url: apiBaseURL + '/keywords', 
        type: 'POST',
        data: JSON.stringify(keywordData),
        contentType: 'application/json',
        success: function(response) {
            console.log("Success: ", response);
            $("#myModal").css("display", "none");
            var newKeyword = response;
            var newRow = `
                <tr>
                    <td class="editable" contenteditable="false" data-id="${newKeyword.keyword_id}" data-field="keyword">${newKeyword.keyword}</td>
                    <td class="editable" contenteditable="false" data-id="${newKeyword.keyword_id}" data-field="DE">${newKeyword.translations.DE}</td>
                    <td class="editable" contenteditable="false" data-id="${newKeyword.keyword_id}" data-field="EN">${newKeyword.translations.EN}</td>
                    <td class="editable" contenteditable="false" data-id="${newKeyword.keyword_id}" data-field="ES">${newKeyword.translations.ES}</td>
                    <td class="editable" contenteditable="false" data-id="${newKeyword.keyword_id}" data-field="FR">${newKeyword.translations.FR}</td>
                    <td class="editable" contenteditable="false" data-id="${newKeyword.keyword_id}" data-field="IT">${newKeyword.translations.IT}</td>
                    <td class="editable" contenteditable="false" data-id="${newKeyword.keyword_id}" data-field="PT">${newKeyword.translations.PT}</td>
                    <td><button class="btn-delete" data-id="${newKeyword.keyword_id}">Supprimer</button></td>
                </tr>
            `;
            $('#keyword-rows').append(newRow);


            // Ajouter un gestionnaire d'événement pour le bouton de suppression
			$(newRow).find('.btn-delete').on('click', function () {
				var keywordId = $(this).data('id');
				deleteKeyword(keywordId);
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
    if (field === 'keyword') {
        url = apiBaseURL + '/keywords/' + dataId;
    } else {
        url = apiBaseURL + '/translations/keywords/' + dataId;
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

//_________________ Suppression d'un mot-clé _________________
function deleteKeyword(keywordId) {
	$.ajax({
	  url: apiBaseURL + '/keywords/' + keywordId,
	  type: 'DELETE',
	  success: function(result) {
		console.log("Ce mot-clé a été supprimé avec succès : ", keywordId);
		// Supprimer la ligne correspondante dans le tableau
		var rowToRemove = $('[data-id="' + keywordId + '"]').closest('tr');
		rowToRemove.remove();
	  },
	  error: function(jqXHR, textStatus, errorThrown) {
		console.log("Erreur lors de la suppression du mot-clé", textStatus, errorThrown);
	  }
	});
}
