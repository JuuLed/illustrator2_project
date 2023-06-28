<?php

function checkSvgFile($file_path)
{
	$svg_content = file_get_contents($file_path);

	// Cherchez toutes les occurrences de fill et stroke avec leur couleur respective
	preg_match_all('/(fill|stroke):\s*(#[0-9a-fA-F]{6}|#[0-9a-fA-F]{3})/i', $svg_content, $matches);

	// Initialise un tableau pour stocker les couleurs uniques trouvées
	$colors = array();

	foreach ($matches[2] as $color) {
		// Si une couleur autre que #000000 est trouvée, refusez le fichier
		if (strtolower($color) !== "#000000") {
			return false;
		}
		if (!in_array($color, $colors)) {
			$colors[] = $color;
		}
	}

	// Si plus d'une couleur est trouvée, refusez le fichier
	if (count($colors) > 1) {
		return false;
	}

	echo "Colors: ";
	print_r($colors); // Imprimez le tableau des couleurs

	return true;
}

// Vérifiez si le fichier a été téléchargé
if (!empty($_FILES) && $_FILES['uploadedFile']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['uploadedFile']['tmp_name'])) {
	$tmp_name = $_FILES['uploadedFile']['tmp_name'];
	$name = $_FILES['uploadedFile']['name'];

	// Vérifiez si le fichier téléchargé est un SVG
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	if (finfo_file($finfo, $tmp_name) === 'image/svg+xml') {
		if (checkSvgFile($tmp_name)) {
			echo "Fichier '$name' est monochrome et a été accepté.";
		} else {
			echo "Le fichier '$name' contient une couleur 'fill' ou 'stroke' autre que #000000, ou plusieurs couleurs. Le fichier n'a pas été accepté.";
		}
	} else {
		echo "Le fichier '$name' n'est pas un SVG valide.";
	}
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	echo "Erreur d'upload du fichier.";
}

?>

<form action="" method="POST" enctype="multipart/form-data">
	Select SVG file to upload:
	<input type="file" name="uploadedFile" id="uploadedFile">
	<input type="submit" value="Upload" name="submit">
</form>