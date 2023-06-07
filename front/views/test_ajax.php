<script>


	//* Attacher des gestionnaires d'événements pour enregistrer modifications et supprimer les symboles
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




</script>