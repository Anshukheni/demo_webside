<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Star Wars Characters</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1 class="title">Star Wars Characters</h1>

        <div id="loader" class="loader hidden"></div>

        <div id="characters-grid" class="characters-grid"></div>

        <div id="pagination" class="pagination">
            <button id="prev" class="pagination-btn">Previous</button>
            <button id="next" class="pagination-btn">Next</button>
        </div>
    </div>

    <div id="character-modal" class="modal hidden">
        <div class="modal-content">
            <h2 id="character-name"></h2>
            <p><strong>Height:</strong> <span id="character-height"></span> meters</p>
            <p><strong>Mass:</strong> <span id="character-mass"></span> kg</p>
            <p><strong>Birth Year:</strong> <span id="character-birthyear"></span></p>
            <p><strong>Films:</strong> <span id="character-films"></span></p>
            <p><strong>Added on:</strong> <span id="character-added"></span></p>
            <button id="close-modal">Close</button>
        </div>
    </div>

    <script>
        const apiUrl = "https://swapi.dev/api/people/";
        let currentPage = 1;
        let totalPages = 1;

        async function fetchData(page) {
            try {
                document.getElementById("loader").classList.remove("hidden");
                const response = await fetch(`${apiUrl}?page=${page}`);
                const data = await response.json();

                if (response.ok) {
                    totalPages = Math.ceil(data.count / 10);
                    renderCharacters(data.results);
                } else {
                    showError("Error fetching data.");
                }
            } catch {
                showError("Unable to reach API.");
            } finally {
                document.getElementById("loader").classList.add("hidden");
            }
        }

        function renderCharacters(characters) {
            const grid = document.getElementById("characters-grid");
            grid.innerHTML = "";
            characters.forEach((character, index) => {
                const card = document.createElement("div");
                card.classList.add("card");
                card.innerHTML = `<h3>${character.name}</h3>`;
                card.addEventListener("click", () => openModal(character));
                grid.appendChild(card);
            });
        }

        function openModal(character) {
            document.getElementById("character-name").textContent = character.name;
            document.getElementById("character-height").textContent = (character.height / 100).toFixed(2);
            document.getElementById("character-mass").textContent = character.mass;
            document.getElementById("character-birthyear").textContent = character.birth_year;
            document.getElementById("character-films").textContent = character.films.length;
            document.getElementById("character-added").textContent = new Date(character.created).toLocaleDateString("en-GB");
            document.getElementById("character-modal").classList.remove("hidden");
        }

        function showError(message) {
            const grid = document.getElementById("characters-grid");
            grid.innerHTML = `<p class="error-message">${message}</p>`;
        }

        document.getElementById("close-modal").addEventListener("click", () => {
            document.getElementById("character-modal").classList.add("hidden");
        });

        document.getElementById("prev").addEventListener("click", () => {
            if (currentPage > 1) {
                currentPage--;
                fetchData(currentPage);
            }
        });

        document.getElementById("next").addEventListener("click", () => {
            if (currentPage < totalPages) {
                currentPage++;
                fetchData(currentPage);
            }
        });

        fetchData(currentPage);
    </script>
</body>
</html>
