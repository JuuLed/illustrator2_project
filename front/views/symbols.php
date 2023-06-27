<link rel="stylesheet" href="./css/symbols.css">

<div class="header-symbols">
	<h1>SYMBOLS</h1>

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
		<th>Fichier</th>
		<th>Nom du symbole</th>
		<th class="cln-size">Taille</th>
		<th class="cln-size">Actif</th>
		<th>Catégories</th>
		<th>Mots-clés</th>
		
		<th>
			<input type="checkbox" id="selectAllCheckbox">
			<button id="deleteSelectedBtn" disabled="true">Supp. sélect.</button>
		</th>
	</tr>
	<!-- Les lignes du tableau seront ajoutées ici en JavaScript -->
</table>




<script src="./js/symbols.js"></script>