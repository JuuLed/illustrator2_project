<link rel="stylesheet" href="./css/keywords.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<div class="header-symbols">
	<h1>KEYWORDS</h1>
	<button>
		<i class="fa-solid fa-circle-plus add-icon"></i>
		Ajouter un mot-clé
	</button>

	<div id="myModal" class="modal">
		<div class="modal-content">
		<form id="add-keyword-form">
			<label for="keyword">Mot-clé</label>
			<input type="text" name="keyword" id="keyword" placeholder="Mot-clé" required>

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

<div>
	<table class="table-keywords">
		<thead>
			<tr>
				<th>Mot-clé</th>
				<th>Allemand DE</th>
				<th>Anglais EN</th>
				<th>Espagnol ES</th>
				<th>Français FR</th>
				<th>Italien IT</th>
				<th>Portugais PT</th>
				<th><i class="fa-solid fa-trash"></i></th>
			</tr>
		</thead>
		<tbody id="keyword-rows">
			<!-- Les lignes du tableau seront ajoutées ici en JavaScript -->
		</tbody>
	</table>
</div>

<script src="./js/keywords.js"></script>