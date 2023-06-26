<link rel="stylesheet" href="./css/categories.css">
<script src="./js/categories.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

<div class="header-symbols">
	<h1>CATEGORIES</h1>
	<button>
		<i class="fa-solid fa-circle-plus add-icon"></i>
		Ajouter une catégorie
	</button>

	<div id="myModal" class="modal">
		<div class="modal-content">
		<form id="add-category-form">
			<label for="category">Nom de la catégorie</label>
			<input type="text" name="category" id="category" placeholder="Nom de la catégorie" required>

			<label for="en">Anglais</label>
			<input type="text" name="EN" id="en" placeholder="Anglais" required>

			<label for="de">Allemand</label>
			<input type="text" name="DE" id="de" placeholder="Allemand" required>

			<label for="es">Espagnol</label>
			<input type="text" name="ES" id="es" placeholder="Espagnol" required>

			<label for="fr">Français</label>
			<input type="text" name="FR" id="fr" placeholder="Français" required>

			<label for="it">Italien</label>
			<input type="text" name="IT" id="it" placeholder="Italien" required>

			<label for="pt">Portugais</label>
			<input type="text" name="PT" id="pt" placeholder="Portugais" required>

			<button type="submit">Ajouter</button>
		</form>

		</div>
	</div>

</div>

<div class="category-order-bar">
  <div class="category-order-title">Ordre d'affichage des catégories</div>
  <div class="category-order-arrow"></div>
  <div class="category-order-list grid">
    <!-- Les catégories seront ajoutées ici via JavaScript -->
  </div>
</div>

<div>
	<table class="table-categories">
		<thead>
			<tr>
				<th>Nom de la catégorie</th>
				<th>Allemand DE</th>
				<th>Anglais EN</th>
				<th>Espagnol ES</th>
				<th>Francais FR</th>
				<th>Italien IT</th>
				<th>Portugais PT</th>
				<th><i class="fa-solid fa-trash"></i></th>
			</tr>
		</thead>
		<tbody id="category-rows">
			<!-- Les lignes du tableau seront ajoutées ici en JavaScript -->
		</tbody>
	</table>



</div>