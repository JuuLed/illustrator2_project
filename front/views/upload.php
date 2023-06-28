
<h2>Choisissez un fichier SVG à télécharger :</h2>
<input type="file" id="uploadedFile">



<script>
    // Attachez un gestionnaire d'événements 'change' au champ de fichier
    document.getElementById('uploadedFile').addEventListener('change', function(e) {
        // Récupérez le fichier sélectionné par l'utilisateur
        var file = e.target.files[0];
        // Si aucun fichier n'a été sélectionné, terminez la fonction
        if (!file) {
            return;
        }

        // Créez un nouvel objet FileReader pour lire le contenu du fichier
        var reader = new FileReader();
        reader.onload = function(e) {
            // Récupérez le contenu du fichier (code source SVG)
            var contents = e.target.result;
            console.log(contents); // Affiche le code source du SVG

            // Créez un nouvel objet DOMParser pour analyser le code source SVG
            var parser = new DOMParser();
            var doc = parser.parseFromString(contents, "image/svg+xml");

            // Sélectionnez tous les éléments avec les attributs 'fill' et 'stroke', et les balises de style
            var fills = doc.querySelectorAll('[fill]');
            var strokes = doc.querySelectorAll('[stroke]');
            var styles = doc.querySelectorAll('style');

            // Créez un Set pour stocker les couleurs trouvées (élimine les doublons)
            var colors = new Set();

            // Vérifiez les attributs 'fill'
            for(var i = 0; i < fills.length; i++) {
                var color = fills[i].getAttribute('fill');
                // Si une couleur autre que #000000 est trouvée, refusez le fichier
                if (color && color !== "#000000") {
                    alert("Le fichier '" + file.name + "' contient une couleur 'fill' autre que #000000. Le fichier n'a pas été accepté.");
                    return;
                }
                colors.add(color);
            }

            // Vérifiez les attributs 'stroke'
            for(var i = 0; i < strokes.length; i++) {
                var color = strokes[i].getAttribute('stroke');
                // Si une couleur autre que #000000 est trouvée, refusez le fichier
                if (color && color !== "#000000") {
                    alert("Le fichier '" + file.name + "' contient une couleur 'stroke' autre que #000000. Le fichier n'a pas été accepté.");
                    return;
                }
                colors.add(color);
            }

            // Vérifiez les déclarations de couleur dans les balises de style
            for(var i = 0; i < styles.length; i++) {
                var styleContent = styles[i].textContent;
                // Utilisez une expression régulière pour trouver toutes les déclarations de couleur
                var colorMatches = styleContent.match(/(fill|stroke):\s*(#[0-9a-fA-F]{6}|#[0-9a-fA-F]{3})/g) || [];
                for(var j = 0; j < colorMatches.length; j++) {
                    var color = colorMatches[j].split(':')[1].trim();
                    // Si une couleur autre que #000000 est trouvée, refusez le fichier
                    if (color !== "#000000") {
                        alert("Le fichier '" + file.name + "' contient une couleur '" + colorMatches[j].split(':')[0] + "' autre que #000000 dans une balise de style. Le fichier n'a pas été accepté.");
                        return;
                    }
                    colors.add(color);
                }
            }

            // Si plus d'une couleur est trouvée, refusez le fichier
            if (colors.size > 1) {
                alert("Le fichier '" + file.name + "' contient plusieurs couleurs. Le fichier n'a pas été accepté.");
                return;
            }

            console.log(colors); // Affiche les couleurs 'fill' et 'stroke' trouvées

            alert("Fichier '" + file.name + "' est monochrome et a été accepté.");
        };
        // Commencez à lire le contenu du fichier
        reader.readAsText(file);
    });
</script>
