<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Mon application</title>
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

    /* Styles pour la liste d'éléments */
    ul {
      list-style-type: none;
      padding: 0;
    }

    li {
      cursor: pointer;
      margin-bottom: 5px;
    }

    li:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
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
          <div class="hobbies-container">
            <div class="hobby">Activité 1</div>
            <div class="hobby">Activité 2</div>
            <div class="hobby">Activité 3</div>
          </div>
          <span class="open-modal">+</span>
        </td>
      </tr>
      <tr>
        <td>Jane Smith</td>
        <td>
          <div class="hobbies-container">
            <div class="hobby">Activité 1</div>
            <div class="hobby">Activité 4</div>
            <div class="hobby">Activité 5</div>
          </div>
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
      <h3>Liste de choses à ajouter :</h3>
      <ul id="list">
        <!-- Ajoutez vos activités ici -->
        <li class="list-item">Activité A</li>
        <li class="list-item">Activité B</li>
        <li class="list-item">Activité C</li>
      </ul>
      <button id="closeModal">Fermer</button>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var openModalButtons = document.getElementsByClassName("open-modal");
      var modal = document.getElementById("myModal");
      var closeModalButton = document.getElementById("closeModal");
      var listItems = document.getElementsByClassName("list-item");

      function openModal() {
        var hobbiesContainer = this.parentNode.getElementsByClassName("hobbies-container")[0];
        var hobbies = Array.from(hobbiesContainer.getElementsByClassName("hobby")).map(function(hobby) {
          return hobby.textContent;
        });

        var list = modal.querySelector("#list");
        list.innerHTML = "";

        Array.from(listItems).forEach(function(item) {
          if (hobbies.indexOf(item.textContent) === -1) {
            list.appendChild(item.cloneNode(true));
          }
        });

        modal.style.display = "block";

        Array.from(list.getElementsByTagName("li")).forEach(function(item) {
          item.addEventListener("click", function() {
            var hobby = this.textContent;
            var newHobby = document.createElement("div");
            newHobby.textContent = hobby;
            newHobby.className = "hobby";
            hobbiesContainer.appendChild(newHobby);
            this.remove();
          });
        });
      }

      Array.from(openModalButtons).forEach(function(button) {
        button.addEventListener("click", openModal);
      });

      closeModalButton.addEventListener("click", function() {
        modal.style.display = "none";
      });

      modal.addEventListener("click", function(event) {
        if (event.target === this) {
          modal.style.display = "none";
        }
      });
    });
  </script>
</body>
</html>
