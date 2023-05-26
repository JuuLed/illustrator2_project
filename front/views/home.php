<style>
    /* Styles pour la modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
      background-color: #fefefe;
      margin: 15% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
    }

    /* Styles pour le bouton "+" */
    .open-modal {
      display: inline-block;
      padding: 5px;
      background-color: #337ab7;
      color: #fff;
      cursor: pointer;
    }

    /* Styles pour le tableau */
    table {
      border-collapse: collapse;
      width: 100%;
    }

    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }
  </style>

<h1>

	HOME

</h1>

<table>
    <thead>
      <tr>
        <th>Nom</th>
        <th>Hobbies</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>John Doe</td>
        <td>
          Hobbies 1
          <span class="open-modal">+</span>
        </td>
      </tr>
      <tr>
        <td>Jane Smith</td>
        <td>
          Hobbies 2
          <span class="open-modal">+</span>
        </td>
      </tr>
      <!-- Ajoutez d'autres lignes de données ici -->
    </tbody>
  </table>

  <div id="myModal" class="modal">
    <div class="modal-content">
      <h2>Hobbies</h2>
      <p>Contenu de la modal...</p>
      <button id="closeModal">Fermer</button>
    </div>
  </div>

  <script>
    var modals = document.getElementsByClassName("modal");
    var modalContents = document.getElementsByClassName("modal-content");
    var btns = document.getElementsByClassName("open-modal");
    var closeBtn = document.getElementById("closeModal");

    for (var i = 0; i < btns.length; i++) {
      // Ajouter un gestionnaire d'événements à chaque bouton "+" pour ouvrir la modal correspondante
      btns[i].onclick = function(index) {
        return function() {
          modals[index].style.display = "block";
        }
      }(i);

      // Fermer la modal lorsque le bouton "Fermer" est cliqué
      closeBtn.onclick = function(index) {
        return function() {
          modals[index].style.display = "none";
        }
      }(i);
    }

    // Fermer la modal lorsque l'utilisateur clique en dehors de la modal
    window.onclick = function(event) {
      for (var i = 0; i < modals.length; i++) {
        if (event.target == modals[i]) {
          modals[i].style.display = "none";
        }
      }
    }
  </script>