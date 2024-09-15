
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
        .then(data => {
            // Empty the page
            const heatsEl = document.getElementById("heats");
            heatsEl.innerHTML = '';

            pilots = data["ranks"];
            races = data["races"];

            // Loop over races
            races.forEach(race => {
                addRaceDOM(race);
            });

            // Sort pilots
            pilots.sort((a, b) => parseInt(a.position) - parseInt(b.position));

            const tableBody = document.getElementById("pilot-table-body");
            tableBody.innerHTML = '';

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
 * Function to set the correct content for a heat
 */
function addRaceDOM(race){
    const heatsEl = document.getElementById("heats");
    if (heatsEl){
        const heatEl = document.createElement('div');
        heatEl.classList.add('race');
        heatEl.innerHTML = `
            <h3>${race['name']}</h3>
            <div class="lists-container">
                <ul class="freqs">
                    <li><h4>Freq.</h4></li>
                    <li class="${race['freq1']}" >${race['freq1']}</li>
                    <li class="${race['freq2']}" >${race['freq2']}</li>
                    <li class="${race['freq3']}" >${race['freq3']}</li>
                    <li class="${race['freq4']}" >${race['freq4']}</li>
                </ul>
                <ul class='pilotlist'>
                    <li><h4>Pilots</h4></li>
                    <li>${race['pilot1']}</li>
                    <li>${race['pilot2']}</li>
                    <li>${race['pilot3']}</li>
                    <li>${race['pilot4']}</li>
                </ul>
            </div>
        `;
        heatsEl.appendChild(heatEl);
    };
}


/*
 * First function called when DOM is ready
 */
function main () {
    // Pull data from server
    refresh();

    // Set interval to pull again in a loop
    setInterval(refresh, 60000);
}

document.addEventListener("DOMContentLoaded", main());
