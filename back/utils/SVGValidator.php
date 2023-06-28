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


?>