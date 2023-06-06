<script>
		function openModal() {
		var symbolRow = this.closest("tr");
		var buttonType = this.dataset.type;
		console.log(buttonType);

		if (buttonType === "category") {
			var categoryModal = symbolRow.querySelector(".category-modal");
			var categoryList = categoryModal.querySelector("#categoryList");

			fetch(apiBaseURL + "/categories")
				.then(function (response) {
					if (response.ok) {
						return response.json();
					}
					throw new Error("Erreur lors de la récupération des catégories.");
				})
				.then(function (categories) {
					var existingCategories = Array.from(symbolRow.querySelectorAll("[data-category-id]")).map(function (category) {
						return category.dataset.categoryId;
					});

					categoryList.innerHTML = "";

					categories.forEach(function (category) {
						if (!existingCategories.includes(category.category_id.toString())) {
							var categoryItem = document.createElement("li");
							categoryItem.dataset.categoryId = category.category_id;
							categoryItem.textContent = category.category;

							categoryItem.addEventListener("click", function () {
								// Code à exécuter lors du clic sur la catégorie
								// Par exemple, vous pouvez ajouter une classe ou effectuer une action spécifique
								console.log("Catégorie cliquée :", category.category);

								addCategory(categoryItem);
							});

							categoryItem.addEventListener("mouseover", handleCategoryMouseOver);
							categoryItem.addEventListener("mouseout", handleCategoryMouseOut);

							categoryList.appendChild(categoryItem);
						}
					});
				})
				.catch(function (error) {
					console.log(error);
				});
		} else if (buttonType === "keyword") {
			var keywordModal = symbolRow.querySelector(".keyword-modal");
			var keywordList = keywordModal.querySelector("#keywordList");

			fetch(apiBaseURL + "/keywords")
				.then(function (response) {
					if (response.ok) {
						return response.json();
					}
					throw new Error("Erreur lors de la récupération des mots-clés.");
				})
				.then(function (keywords) {
					var existingKeywords = Array.from(symbolRow.querySelectorAll("[data-keyword-id]")).map(function (keyword) {
						return keyword.dataset.keywordId;
					});

					keywordList.innerHTML = "";

					keywords.forEach(function (keyword) {
						if (!existingKeywords.includes(keyword.keyword_id.toString())) {
							var keywordItem = document.createElement("li");
							keywordItem.dataset.keywordId = keyword.keyword_id;
							keywordItem.textContent = keyword.keyword;

							keywordItem.addEventListener("click", function () {
								console.log("Mot-clé cliqué :", keyword.keyword);
								addKeyword(keywordItem);
							});

							keywordItem.addEventListener("mouseover", handleKeywordMouseOver);
							keywordItem.addEventListener("mouseout", handleKeywordMouseOut);

							keywordList.appendChild(keywordItem);
						}
					});
				})
				.catch(function (error) {
					console.log(error);
				});
		}
		var modal = symbolRow.querySelector(".modal");
		modal.style.display = "block";

		// Fermer la modal lorsque l'utilisateur clique en dehors de celle-ci
		window.onclick = function (event) {
			if (event.target == modal) {
				closeModal();
			}
		};
	}



function addCategory(categoryItem) {
		var symbolRow = categoryItem.closest("tr");
		var symbolId = symbolRow.querySelector(".save-btn").dataset.id;
		var categoryId = categoryItem.dataset.categoryId;

		// Effectuer une requête AJAX POST pour associer la catégorie au symbole en base de données
		$.ajax({
			url: apiBaseURL + "/symbols/" + symbolId + "/categories/" + categoryId,
			type: "POST",
			success: function (data) {
				console.log("Catégorie associée au symbole avec succès:", data);

				var categoryContainer = symbolRow.querySelector(".category-list");

				var newCategoryItem = document.createElement("li");
				newCategoryItem.dataset.categoryId = categoryId;

				var categorySpan = document.createElement("span");
				categorySpan.textContent = categoryItem.textContent;
				newCategoryItem.appendChild(categorySpan);

				var deleteButton = document.createElement("button");
				deleteButton.classList.add("delete-category-btn");
				deleteButton.innerHTML = "&times;";
				newCategoryItem.appendChild(deleteButton);

				var addButton = categoryContainer.querySelector(".add-btn");

				// Insérer la nouvelle catégorie avant le bouton "+"
				categoryContainer.insertBefore(newCategoryItem, addButton);

				// Effacer la sélection
				categoryItem.classList.remove("category-hover");

				// Retirer la catégorie de la liste
				categoryItem.remove();

				// closeModal();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log("Erreur lors de l'association de la catégorie au symbole:", textStatus, errorThrown);
			}
		});
	}

</script>