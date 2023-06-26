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
			<input type="text" name="category" placeholder="Nom de la catégorie">
			<input type="text" name="EN" placeholder="Anglais">
			<input type="text" name="DE" placeholder="Allemand">
			<input type="text" name="ES" placeholder="Espagnol">
			<input type="text" name="FR" placeholder="Français">
			<input type="text" name="IT" placeholder="Italien">
			<input type="text" name="PT" placeholder="Portugais">
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
	<table class="table-symbols">
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
		<tbody id="category-rows">
			<!-- Les lignes du tableau seront ajoutées ici en JavaScript -->
		</tbody>
	</table>



</div>