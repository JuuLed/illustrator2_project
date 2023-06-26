<style>
#dropArea {
  width: 300px;
  height: 200px;
  border: 2px dashed #ccc;
  text-align: center;
  padding: 20px;
  font-size: 18px;
}

#dropArea.dragging {
  background-color: #f9f9f9;
}

#dropArea.error {
  border-color: #ff0000;
}



</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<h1>

	UPLOAD

</h1>

<div id="dropArea">
  <p>Déposez vos fichiers SVG ici</p>
</div>


<script>


var dropArea = document.getElementById('dropArea');

// Empêcher le comportement par défaut pour le drag-and-drop
dropArea.addEventListener('dragenter', preventDefault, false);
dropArea.addEventListener('dragleave', preventDefault, false);
dropArea.addEventListener('dragover', preventDefault, false);
dropArea.addEventListener('drop', preventDefault, false);

function preventDefault(event) {
  event.preventDefault();
  event.stopPropagation();
}

// Ajouter une classe lorsque l'élément est survolé avec un fichier
dropArea.addEventListener('dragenter', function(event) {
  dropArea.classList.add('dragging');
});

// Supprimer la classe lorsque l'élément n'est plus survolé avec un fichier
dropArea.addEventListener('dragleave', function(event) {
  dropArea.classList.remove('dragging');
});


// Vérifier les fichiers lors du dépôt
dropArea.addEventListener('drop', function(event) {
  dropArea.classList.remove('dragging');
  dropArea.classList.remove('error');

  var files = event.dataTransfer.files;
  var errors = [];
  var validFiles = [];

  var checkFile = function(file) {
    var reader = new FileReader();

    reader.onload = function(event) {
      var svgContent = event.target.result;

      // Vérifier les problèmes sur le fichier SVG
      var hasMultipleColors = !isMonochrome(svgContent);
      var hasNonBlackFill = !isBlackFill(svgContent);

      if (hasMultipleColors || hasNonBlackFill) {
        errors.push({
          file: file,
          hasMultipleColors: hasMultipleColors,
          hasNonBlackFill: hasNonBlackFill
        });
      } else {
        // Ajouter le fichier à la liste des fichiers valides
        validFiles.push(file);
      }

      // Vérifier si tous les fichiers ont été vérifiés
      if (validFiles.length + errors.length === files.length) {
        // Afficher les erreurs détectées
        if (errors.length > 0) {
          dropArea.classList.add('error');
          var errorMessages = errors.map(function(error) {
            var errorMessage = 'Le fichier "' + error.file.name + '" ';
            if (error.hasMultipleColors) {
              errorMessage += 'contient plusieurs couleurs.';
            }
            if (error.hasNonBlackFill) {
              errorMessage += 'utilise une couleur fill autre que #000000.';
            }
            return errorMessage;
          });
          alert(errorMessages.join('\n'));
        } else {
          // Traiter les fichiers SVG valides ici
          validFiles.forEach(function(validFile) {
            alert('Le fichier SVG est valide : ' + validFile.name);
            // Effectuer d'autres actions sur les fichiers SVG valides si nécessaire
          });
        }
      }
    };

    reader.readAsText(file);
  };

  // Vérifier chaque fichier individuellement
  for (var i = 0; i < files.length; i++) {
    checkFile(files[i]);
  }
});




// Vérifier si le fichier SVG est monochrome (une seule couleur fill)
function isMonochrome(svgContent) {
  var parser = new DOMParser();
  var doc = parser.parseFromString(svgContent, 'image/svg+xml');
  var fillNodes = doc.getElementsByTagName('path');
  
  var uniqueFillColors = new Set();
  
  for (var i = 0; i < fillNodes.length; i++) {
    var fillAttribute = fillNodes[i].getAttribute('fill');
    if (fillAttribute && fillAttribute !== '#000000') {
      uniqueFillColors.add(fillAttribute);
    }
  }
  
  return uniqueFillColors.size <= 1;
}

// Vérifier si le fichier SVG utilise uniquement la couleur fill #000000
function isBlackFill(svgContent) {
  var parser = new DOMParser();
  var doc = parser.parseFromString(svgContent, 'image/svg+xml');
  var fillNodes = doc.getElementsByTagName('path');

  for (var i = 0; i < fillNodes.length; i++) {
    var fillAttribute = fillNodes[i].getAttribute('fill');
    if (fillAttribute && fillAttribute !== '#000000') {
      return false;
    }
  }

  return true;
}




</script>