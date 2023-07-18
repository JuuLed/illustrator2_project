<?
	include_once $_SERVER['DOCUMENT_ROOT']."../devt.loguy.fr/class/include.php";
	// include_once "/home/www/devt.loguy.fr/class/include.php";
	ini_set( 'default_charset', 'UTF-8');

	/**
	 * Récupération de tous les templates de gobelets à thèmes activés
	 * @return array 
	 */
	function getTemplatesList() {
		$sql = "SELECT
					configuratorTemplates.templateID AS `ID`,
					configuratorTranslations.translationValue AS `name`
				FROM 
					loguy.configuratorTranslations
				LEFT JOIN
					loguy.configuratorTemplates ON configuratorTranslations.translationTypeID = configuratorTemplates.templateID
				WHERE
					configuratorTranslations.translationType = 'template'
				AND
					configuratorTemplates.templateEnabled = 1
				AND
					configuratorTranslations.translationLangID = 4
				ORDER BY
					configuratorTranslations.translationValue ASC";

		$templates = DB::gets($sql);

		// suppression des templates qui ne sont dans aucun thème
		foreach($templates as $key=>$template) {
			$sql = "SELECT
						COUNT(themeID)
					FROM
						loguy.configuratorTemplatesThemes
					WHERE
						templateID = ".(int)$template->ID;
			
			if(DB::get($sql) == 0 ) {
				unset($templates[$key]);
			}
		}

		return $templates;
	}

	/**
	 * Renvoie les templates équivalents dans le cas de plusieurs versions d'un visuel (ex : après modification de couleur ou de police)
	 * @param mixed $templateID 
	 * @return mixed 
	 */
	function getTemplatesEquivalent($templateID) {
		// on recherche le referenceID du template passé en paramètre
		$sql = "SELECT
					configuratorTemplates.templateReferenceID AS refID
				FROM 
					loguy.configuratorTemplates
				WHERE
					templateID = ".(int)$templateID;
		$referenceID = DB::get($sql);

		// s'il n'y en a pas, on assigne la variable referenceID au templateID passé en paramètre
		if(!$referenceID) {
			$referenceID = $templateID;
		}

		// on recherche des templates qui auraient en référence le templateID passé en paramètre ou celui trouvé au début de la fonction
		$sql = "SELECT
					configuratorTemplates.templateID AS ID
				FROM 
					loguy.configuratorTemplates
				WHERE
					templateReferenceID = ".DB::escape($referenceID)."";

		$results = DB::gets($sql);

		// si le templateID passé en paramètre ne figure pas dans les résultats, on l'ajoute
		if(!in_array($templateID, $results)) {
			$results[] = $templateID;
		}
		// sinon on ajoute le template en refenceID dans la liste des résultats
		else {
			$results[] = $referenceID;
		}
		return $results;
	}

	// fonction recherche sur les personnalisation template dans le json (n° template et champs personnalisé) et date
	/**
	 * Renvoie les personnalisations effectuées à partir de paramètres mis dans la variable $params
	 * @param object $params :
	 * 					- (int) templateID : ID du template recherché (la recherche sera effectuée sur les templates équivalents)
	 * 					- (string) searchedText[1-3] : texte(s) recherché(s)
	 * 					- (string) dateStart : date de début du bornage
	 * 					- (string) dateEnd : date de fin du bornage
	 * @return array
	 */
	function searchCustomization($params) {
		$sql = "SELECT
					configurateur_personalisations.perso_key,
					configurateur_personalisations.perso_obj,
					configurateur_personalisations.perso_date,
					configurateur_personalisations.perso_previewFileNameFace1
				FROM 
					toolzik_imprimetcom_v2.configurateur_personalisations
				WHERE
					configurateur_personalisations.perso_configuratorVersion = 'vlite'";

		if(!empty($params->templateID)) {

			// numéro du template recherché, enrichi de ses équivalents (= autres versions)
			$templatesID = getTemplatesEquivalent($params->templateID);

			$sql .= "AND (";
			$i = 0;

			foreach($templatesID as $templateID) {
				$i++;

				// recherche du n° template dans le json de personnalisation
				$sql .= "configurateur_personalisations.perso_obj LIKE '%\"currentTemplate\":\"".(int)$templateID."\"%'";

				if($i < count($templatesID)) {
					$sql .= " OR ";
				}
			}
			$sql .= ")";
		}

		// recherche des mots clés dans le json de personnalisation
		if(!empty($params->searchedText1)) {
			$sql .= " AND configurateur_personalisations.perso_obj LIKE '%".DB::escape($params->searchedText1)."%'";
		}

		if(!empty($params->searchedText2)) {
			$sql .= " AND configurateur_personalisations.perso_obj LIKE '%".DB::escape($params->searchedText2)."%'";
		}

		if(!empty($params->searchedText3)) {
			$sql .= " AND configurateur_personalisations.perso_obj LIKE '%".DB::escape($params->searchedText3)."%'";
		}

		// recherche par date
		if(!empty($params->dateStart)) {
			$sql .= " AND configurateur_personalisations.perso_date BETWEEN '".strtotime($params->dateStart)."' 
					AND '".strtotime($params->dateEnd . "23:59:59")."' ";
		}

		$sql .= " ORDER BY
					configurateur_personalisations.perso_date DESC 
				  LIMIT 1000";
		
		// DEBUG::printr($sql);
		// exit;

		return DB::gets($sql);
	}


	/**
	 * Renvoie le numéro de panier et de commande à partir d'un identifiant de personnalisation (persoKey)
	 * @param mixed $persoID 
	 * @return mixed 
	 */
	function getDetailOrder($persoKey) {
		$sql = "SELECT
					".DBNAME_PS."orders.id_order,
					".DBNAME_PS."orders.id_cart,
					configurateur_personalisations.perso_key,
					configurateur_personalisations.perso_previewFileNameFace1
				FROM 
					toolzik_imprimetcom_v2.configurateur_personalisations
				LEFT JOIN
					".DBNAME_PS."customized_data ON ".DBNAME_PS."customized_data.value = configurateur_personalisations.perso_key
				LEFT JOIN
					".DBNAME_PS."customization ON ".DBNAME_PS."customization.id_customization = ".DBNAME_PS."customized_data.id_customization
				LEFT JOIN
					".DBNAME_PS."orders ON ".DBNAME_PS."orders.id_cart = ".DBNAME_PS."customization.id_cart
				WHERE
					configurateur_personalisations.perso_key = \"".DB::escape($persoKey)."\"";

		return DB::get($sql);
	}

	/**
	 * Convertit une chaîne de caractère en version JSON pour recherche directe en DB sans décodage (ex : convertit "était" en "\u00e9tait")
	 * @param string $str 
	 * @return string 
	 */
	function convertStringToJSONEncodedString($str) {

		$convertedString = json_encode($str);
		$convertedString = substr($convertedString,1,-1);
		$convertedString = DB::escape($convertedString);

		// cas particulier du double-quote
		// $convertedString = str_replace("\\\\\\\"","\\\"",$convertedString);
		// $convertedString = str_replace("\\\\u","\\u",$convertedString);

		return $convertedString;

	}



	// initialisation de la table d'erreurs
	$errors = array();

	

	// si une recherche a été lancée
	if(isset($_POST['searchCusto'])) {
		
		// Gestion des erreurs du formulaire et personnalisation des messages d'erreurs
		$atLeastOneFieldFilled = false;

		foreach($_POST as $field) {
			if(!empty($field)) {
				$atLeastOneFieldFilled = true;
				break;
			}
		}
		
		// Si le formulaire envoyé est vide
		if(!$atLeastOneFieldFilled) {
			$errors[] = "Au moins un champ doit être rempli.";
		}
		
		// Si uniquement la date de fin est choisie (et pas de date de début)
		if(empty($_POST['dateStart']) && !empty($_POST['dateEnd']) ) {
			$errors[] = 'Veuillez remplir une date de début';
		}
		
		// Si uniquement la date de début est choisie (et pas de date de début)
		if (empty($_POST['dateEnd']) && !empty($_POST['dateStart']) ) {
			$errors[] = 'Veuillez remplir une date de fin';
		}
		
		// Si la date de début choisi est postérieure à la date de fin
		if ((!empty($_POST['dateStart']) && !empty($_POST['dateEnd'])) && $_POST['dateEnd'] < $_POST['dateStart']){
			$errors[]  = 'Veuillez mettre une date de fin postérieure à la date de début';
		}

		// s'il n'y a pas d'erreurs, on prépare les paramètres et lance la recherche
		if(empty($errors)) {

			$params = (object) array();
			$params->templateID = $_POST['templateID'];
			$params->searchedText1 = convertStringToJSONEncodedString($_POST['searchedText1']);
			$params->searchedText2 = convertStringToJSONEncodedString($_POST['searchedText2']);
			$params->searchedText3 = convertStringToJSONEncodedString($_POST['searchedText3']);
			$params->dateStart = $_POST['dateStart'];
			$params->dateEnd = $_POST['dateEnd'];

			// DEBUG::printr($params);
			// exit;
			
			$foundCustomizations = searchCustomization($params);

		}
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Recherche de personnalisations de gobelets à thèmes</title>

	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.1/css/all.css" crossorigin="anonymous">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;700&display=swap" rel="stylesheet"> 

	<link href="https://cdn.imprimetcom.fr/css/loguy/v1/main.css" rel="stylesheet">
	<link href="https://cdn.imprimetcom.fr/css/loguy/v1/form.css" rel="stylesheet">
	<link href="https://cdn.imprimetcom.fr/css/loguy/v1/buttons.css" rel="stylesheet">
	<link href="https://cdn.imprimetcom.fr/css/loguy/v1/table.css" rel="stylesheet">
	
	<style>
		body {
			width: 1440px;
			margin: 0 auto;
			padding: 1em;
		}
		@media (max-width: 1200px) { 
			body {
				width: 100%;
			}
		}
		.previewImg {
			width:35em;
		}
		.errors {
			background: var(--color-alert-danger);
			color: white;
			padding: .5em 1em;
			border-radius: 5px;
			margin-bottom: 1em;
		}
		.info {
			padding-top:1em;
			font-size:0.85em;
		}
		.navTop {
			display: flex;
			justify-content: space-between;
			padding-bottom: 0.5em;
			margin-bottom: 1em;
		}
		td {
			text-align: center;
		}
	</style>
</head>
<body>
	<h1>Recherche de personnalisations de gobelets à thèmes</h1>
<?
	//* ------------------  Etape 2 : Résultats de la recherche
	if(isset($foundCustomizations)) {
		
		// s'il y a des résultats
		if(!empty($foundCustomizations)) {
?>
			<div class="navTop">
				<div><strong><?= count($foundCustomizations); ?></strong> <?= count($foundCustomizations) > 1 ? "résultats" : "résultat"; ?></div>
				<div><a href="<?= $_SERVER['REQUEST_URI']; ?>">Retour au formulaire de recherche</a></div>
			</div>
			<table class="table">
				<thead>
					<tr>
						<th>Date</th>
						<th>Aperçu</th>
						<th>Numéro de commande</th>
						<th>Numéro de panier</th>
					</tr>
				</thead>
				<tbody>
			<? foreach($foundCustomizations as $customization) { 
				
				$additionalInfos = getDetailOrder($customization->perso_key);?>
					<tr>
						<td><?= TOOLS::displayDateTime((int)$customization->perso_date,true); ?></td>
						<td>
							<a href="https://www.imprimetcom.fr/modules/configurateur//views/templates/admin/preDownload.php?persoID=<?= $customization->perso_key ?>" target="_blank">
								<img class="previewImg" src="https://www.imprimetcom.fr/modules/configurateur//views/preview/<?= $customization->perso_previewFileNameFace1 ?>"/>
							</a>
						</td>
						<td><?= $additionalInfos->id_order ? $additionalInfos->id_order : "-" ?></td>
						<td><?= $additionalInfos->id_cart  ? $additionalInfos->id_cart  : "-" ?></td>
					</tr>
			<? } ?> 
				</tbody>
			</table>
	<? } else { ?>
		<div>
			Pas de résultats
		</div>
	<?
		}
	}
	//* -------------- Etape 1 : Affichage du formulaire
	else { 
		
		// Gestion des erreurs du formulaires
		if(!empty($errors)) {?>
			<div class="errors">
				<ul>
					<? foreach($errors as $error) { ?>
						<li><?= $error; ?></li>
					<? } ?>
				</ul>
			</div>
		<? } ?>
	
		<form method="POST">
			<div class="field-inlineGroup">
				<div class="straightFieldsetStyle">
					<fieldset>
						<legend>Textes recherchés</legend>
						<div class="field">
							<input type="text" name="searchedText1" placeholder="Texte1" value="<?= (isset($_POST['searchedText1'])) ? $_POST['searchedText1'] : ""; ?>"/>
							<label>Texte 1</label>
						</div>
						<div class="field">
							<input type="text" name="searchedText2" placeholder="Texte1" value="<?= (isset($_POST['searchedText2'])) ? $_POST['searchedText2'] : ""; ?>"/>
							<label>Texte 2</label>
						</div>
						<div class="field">
							<input type="text" name="searchedText3" placeholder="Texte1" value="<?= (isset($_POST['searchedText3'])) ? $_POST['searchedText3'] : ""; ?>"/>
							<label>Texte 3</label>
						</div>
					</fieldset>
				</div>

				<div class="straightFieldsetStyle">
					<fieldset>
						<legend>Autres critères</legend>
							<div class="field">
								<select name="templateID">
									<option value=""></option>
										<?
											$templates = getTemplatesList();
											foreach($templates as $template) { 
												$selected = isset($_POST['templateID']) && $_POST['templateID'] == $template->ID ? "selected" : "";
										?>
											<option value="<?= $template->ID ?>" <?= $selected; ?>><?= $template->name ?> 
											<!-- Numéro de template à décommenter si nécessaire -->
												<!-- (<?= $template->ID ?>) -->
											</option>
										<? } ?>
								</select>
								<label>Visuel :</label>
							</div>
							<div class="field">
								<label>Entre le</label>
								<input type="date" name="dateStart" value="<?= (isset($_POST['dateStart'])) ? $_POST['dateStart'] : ""; ?>"/>
							</div>
							<div class="field">
								<label>et le</label>
								<input type="date" name="dateEnd" value="<?= (isset($_POST['dateEnd'])) ? $_POST['dateEnd'] : ""; ?>"/>
							</div>
					</fieldset>
				</div>
			</div>
			<button name="searchCusto" type="submit">Rechercher</button>
			<div class="info"><i class="fas fa-info-circle"></i> Recherche limitée à 1000 résultats.</div>
			
		</form>
	<? } ?>
</body>
</html>