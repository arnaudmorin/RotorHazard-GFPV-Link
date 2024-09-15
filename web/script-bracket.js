
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
            race_counter = 0;
            console.log(data);
            // Loop over races
            data.forEach(race => {
                race_counter++;
                // Ok, maybe not the best idea I had but that works :)
                // Compute the raceId from race_counter
                raceId = 'race' + race_counter;

                // Update DOM for this race if we have at least one pilot
                let allEmpty = true;
                for (let key in race) {
                    if (key.startsWith('pilot')) {
                        if (race[key] !== "") {
                            allEmpty = false;
                            break;
                        }
                    }
                }
                if (! allEmpty) {
                    // At least one not empty pilot, let's add it
                    updateRaceDOM(raceId, race, '');
                }
            });

            pilotEvents();
        })
        .catch(error => {
            // Gérer les erreurs
            console.error('There has been a problem with your fetch operation:', error);
        });
}

/*
 * Function to set the correct content for a race
 */
function updateRaceDOM(raceId, values, clas){
    const raceElement = document.getElementById(raceId);
    if (raceElement){
        raceElement.innerHTML = `
            <h3>${values['name']}</h3>
            <div class="lists-container">
                <ul class="freqs">
                    <li><h4>Freq.</h4></li>
                    <li class="${values['freq1']}" >${values['freq1']}</li>
                    <li class="${values['freq2']}" >${values['freq2']}</li>
                    <li class="${values['freq3']}" >${values['freq3']}</li>
                    <li class="${values['freq4']}" >${values['freq4']}</li>
                </ul>
                <ul class='pilotlist ${clas}'>
                    <li><h4>Pilots</h4></li>
                    <li data-position="${values['position1']}">${values['pilot1']}</li>
                    <li data-position="${values['position2']}">${values['pilot2']}</li>
                    <li data-position="${values['position3']}">${values['pilot3']}</li>
                    <li data-position="${values['position4']}">${values['pilot4']}</li>
                </ul>
                <ul class='positions'>
                    <li><h4>Pos.</h4></li>
                    <li>${values['position1']}</li>
                    <li>${values['position2']}</li>
                    <li>${values['position3']}</li>
                    <li>${values['position4']}</li>
                </ul>
            </div>
        `;
    };
}

/*
 * Function to add event listeners on mouseover for pilots highlight
 * This will also read position and highlight the one in postition 1 or 2
 */
function pilotEvents(){
    let listItems = document.querySelectorAll('.pilotlist li');
    let keepHL = document.querySelectorAll('#keep-hl');

    listItems.forEach(item => {
        // Highligh winners
        const position = item.getAttribute("data-position");
        if (position === "1" || position === "2") {
            item.classList.add("winner");
        }

        if (item.hasAttribute("data-position")) {
            // On click, keep highligh and disable mouseover/mouseout
            item.addEventListener('click', () => {
                // Let's remove HL if already set
                if (keepHL.innerHTML == 'keep') {
                    keepHL.innerHTML = '';
                    listItems.forEach(li => {
                        li.classList.remove('highlight');
                    });
                } else {
                    // Set HL
                    let pilotName = item.textContent;
                    listItems.forEach(li => {
                        if (li.textContent === pilotName) {
                            li.classList.add('highlight');
                            keepHL.innerHTML = 'keep';
                        }
                    });
                }
            });

            // On mouse over --> highlight
            item.addEventListener('mouseover', () => {
                if (keepHL.innerHTML == '') {
                    let pilotName = item.textContent;
                    listItems.forEach(li => {
                        if (li.textContent === pilotName) {
                            li.classList.add('highlight');
                        }
                    });
                }
            });

            // On mouse out --> stop highlight
            item.addEventListener('mouseout', () => {
                if (keepHL.innerHTML == '') {
                    let pilotName = item.textContent;
                    listItems.forEach(li => {
                        if (li.textContent === pilotName) {
                            li.classList.remove('highlight');
                        }
                    });
                }
            });
        }
    });
}


/*
 * First function called when DOM is ready
 */
function main () {
    // Races init
    Object.entries(races).forEach(([raceId, values]) => {
        updateRaceDOM(raceId, values, 'shadow')
    });

    // Pull data from server
    refresh();

    // Set interval to pull again in a loop
    setInterval(refresh, 60000);
}

document.addEventListener("DOMContentLoaded", main());
