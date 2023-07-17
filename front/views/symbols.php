<link rel="stylesheet" href="./css/symbols.css">

<div class="header-symbols">
	<div>
		<h1>SYMBOLS</h1>


		<button type="button" id="openUploadModal">AJOUTER SYMBOLES</button>






	</div>


	<div class="header-symbols search-bar">
		<div>
			<label for="searchInput">Recherche :</label>
			<input type="text" id="searchInput" placeholder="Nom de symbole ou mots-clés">
		</div>
		<div>
			<label for="categoryFilter">Filtrer par catégorie :</label>
			<select id="categoryFilter">
				<option value="">Toutes les catégories</option>
				<!-- Les options de catégorie seront ajoutées ici en JavaScript -->
			</select>
		</div>
		<button id="resetFiltersBtn">Réinitialiser</button>
	</div>



</div>

<table class="table-symbols">
	<tr>
		<th>Nom du symbole</th>
		<th class="cln-size">Taille</th>
		<th class="cln-size">Actif</th>
		<th>Aperçu</th>
		<th>Catégories</th>
		<th>Mots-clés</th>
		<th>Modifier</th>
		<th>Télécharger</th>

		<th>
			<input type="checkbox" id="selectAllCheckbox">
			<button id="deleteSelectedBtn" disabled="true">Supp. sélect.</button>
		</th>
	</tr>
	<!-- Les lignes du tableau seront ajoutées ici en JavaScript -->
</table>



<div class="uploadModal">
	<div class="uploadModal-content">
		<!-- Le contenu du formulaire -->
	</div>
</div>



<script src="./js/symbols.js"></script>

