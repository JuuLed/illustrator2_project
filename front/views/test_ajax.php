<script>

function openModal() {
		var symbolRow = this.closest("tr");
		var categoryList = symbolRow.querySelector("#categoryList");

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

		var keywordList = symbolRow.querySelector("#keywordList");

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

		var modal = symbolRow.querySelector(".modal");
		modal.style.display = "block";

		// Fermer la modal lorsque l'utilisateur clique en dehors de celle-ci
		window.onclick = function (event) {
			if (event.target == modal) {
				closeModal();
			}
		};
	}





//! fonction a tester !!!!!!!!
	function addItem(itemElement, itemType) {
  var symbolRow = itemElement.closest("tr");
  var symbolId = symbolRow.querySelector(".save-btn").dataset.id;
  var itemId = itemElement.dataset[itemType + "Id"];

  // Effectuer une requête AJAX POST pour associer l'élément au symbole en base de données
  $.ajax({
    url: apiBaseURL + "/symbols/" + symbolId + "/" + itemType + "s/" + itemId,
    type: "POST",
    success: function (data) {
      console.log(itemType.charAt(0).toUpperCase() + itemType.slice(1) + " associé au symbole avec succès:", data);

      var itemContainer = symbolRow.querySelector("." + itemType + "-list");

      var newItemElement = document.createElement("li");
      newItemElement.dataset[itemType + "Id"] = itemId;

      var itemSpan = document.createElement("span");
      itemSpan.textContent = itemElement.textContent;
      newItemElement.appendChild(itemSpan);

      var deleteButton = document.createElement("button");
      deleteButton.classList.add("delete-" + itemType + "-btn");
      deleteButton.innerHTML = "&times;";
      newItemElement.appendChild(deleteButton);

      var addButton = itemContainer.querySelector(".add-btn");

      // Insérer le nouvel élément avant le bouton "+"
      itemContainer.insertBefore(newItemElement, addButton);

      // Effacer la sélection
      itemElement.classList.remove(itemType + "-hover");

      // Retirer l'élément de la liste
      itemElement.remove();

      // closeModal();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("Erreur lors de l'association de l'" + itemType + " au symbole:", textStatus, errorThrown);
    }
  });
}

</script>