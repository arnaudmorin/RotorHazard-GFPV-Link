
/*
 * Fetch data from server
 * This is called at regular interval
 */
function refresh(){
    const params = new URLSearchParams(window.location.search);
    let eventid = params.get("eventid");

    // TODO aussi
    let url = `/pull?eventid=${eventid}`;

    fetch(url)
        .then(response => {
            // On error
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            // On success, parse JSON
            return response.json();
        })
        .then(pilots => {
            // Sort pilots
            pilots.sort((a, b) => parseInt(a.position) - parseInt(b.position));

            const tableBody = document.getElementById("pilot-table-body");

            pilots.forEach(pilot => {
                const row = document.createElement("tr");

                row.innerHTML = `
                    <td>${pilot.position}</td>
                    <td>${pilot.pilot}</td>
                    <td>${pilot.extra}</td>
                `;

            const lapsDropdown = document.createElement('div');
            lapsDropdown.classList.add('laps-dropdown');
            
            // Add laps in drop down
            const lapsList = document.createElement('ul');
            pilot.laps.forEach(lap => {
                const lapItem = document.createElement('li');
                lapItem.textContent = lap;
                lapsList.appendChild(lapItem);
            });
            lapsDropdown.appendChild(lapsList);

            // Ajouter un event listener pour le clic
            row.addEventListener('click', function() {
                // Toggle l'affichage du dropdown
                lapsDropdown.style.display = lapsDropdown.style.display === 'none' || lapsDropdown.style.display === '' 
                    ? 'block' : 'none';
            });

            // Ajouter le row et le dropdown au tableau
            tableBody.appendChild(row);
            tableBody.appendChild(lapsDropdown);


            });
        })
        .catch(error => {
            // Gérer les erreurs
            console.error('There has been a problem with your fetch operation:', error);
        });
}


/*
 * First function called when DOM is ready
 */
function main () {
    // Pull data from server
    refresh();

    // Set interval to pull again in a loop
    setInterval(refresh, 100000);
}

document.addEventListener("DOMContentLoaded", main());
