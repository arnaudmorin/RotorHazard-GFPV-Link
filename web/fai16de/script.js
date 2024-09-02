// FAI 16 DE
const races = {
    race1: {
        name: "Race 1",
        pilot1: "Qualification - 16",
        pilot2: "Qualification - 1",
        pilot3: "Qualification - 8",
        pilot4: "Qualification - 9",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race2: {
        name: "Race 2",
        pilot1: "Qualification - 13",
        pilot2: "Qualification - 4",
        pilot3: "Qualification - 5",
        pilot4: "Qualification - 12",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race3: {
        name: "Race 3",
        pilot1: "Qualification - 14",
        pilot2: "Qualification - 3",
        pilot3: "Qualification - 6",
        pilot4: "Qualification - 10",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race4: {
        name: "Race 4",
        pilot1: "Qualification - 15",
        pilot2: "Qualification - 2",
        pilot3: "Qualification - 7",
        pilot4: "Qualification - 11",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race5: {
        name: "Race 5 (lower)",
        pilot1: "Race 1 - 4",
        pilot2: "Race 2 - 3",
        pilot3: "Race 3 - 3",
        pilot4: "Race 4 - 4",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race6: {
        name: "Race 6 (lower)",
        pilot1: "Race 2 - 4",
        pilot2: "Race 1 - 3",
        pilot3: "Race 4 - 3",
        pilot4: "Race 3 - 4",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race7: {
        name: "Race 7",
        pilot1: "Race 1 - 2",
        pilot2: "Race 1 - 1",
        pilot3: "Race 2 - 1",
        pilot4: "Race 2 - 2",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race8: {
        name: "Race 8",
        pilot1: "Race 3 - 2",
        pilot2: "Race 3 - 1",
        pilot3: "Race 4 - 1",
        pilot4: "Race 4 - 2",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race9: {
        name: "Race 9 (lower)",
        pilot1: "Race 8 - 4",
        pilot2: "Race 6 - 2",
        pilot3: "Race 5 - 1",
        pilot4: "Race 7 - 3",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race10: {
        name: "Race 10 (lower)",
        pilot1: "Race 7 - 4",
        pilot2: "Race 5 - 2",
        pilot3: "Race 6 - 1",
        pilot4: "Race 8 - 3",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race11: {
        name: "Race 11 (lower)",
        pilot1: "Race 9 - 2",
        pilot2: "Race 9 - 1",
        pilot3: "Race 10 - 1",
        pilot4: "Race 10 - 2",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race12: {
        name: "Race 12",
        pilot1: "Race 7 - 2",
        pilot2: "Race 7 - 1",
        pilot3: "Race 8 - 1",
        pilot4: "Race 8 - 2",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race13: {
        name: "Race 13 (lower)",
        pilot1: "Race 12 - 4",
        pilot2: "Race 11 - 2",
        pilot3: "Race 11 - 1",
        pilot4: "Race 12 - 3",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    final: {
        name: "Final",
        pilot1: "Race 13 - 2",
        pilot2: "Race 12 - 2",
        pilot3: "Race 12 - 1",
        pilot4: "Race 13 - 1",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
};

/*
 * Fetch data from server
 * This is called at regular interval
 */
function refresh(){
    const params = new URLSearchParams(window.location.search);
    let eventid = params.get("eventid");

    // TODO aussi
    let url = `https://www.arnaudmorin.fr/rh/pull?eventid=${eventid}`;

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
            // Loop over races
            data.forEach(race => {
                // Ok, maybe not the best idea I had but that works :)
                // Compute the raceId from race.name
                raceId = race.name.toLowerCase().replace(/\s+/g, '');
                // Update DOM for this race
                updateRaceDOM(raceId, race, '');
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
    raceElement.innerHTML = `
        <h3>${values['name']}</h3>
        <div class="lists-container">
            <ul class='pilotlist ${clas}'>
                <li data-position="${values['position1']}">${values['pilot1']}</li>
                <li data-position="${values['position2']}">${values['pilot2']}</li>
                <li data-position="${values['position3']}">${values['pilot3']}</li>
                <li data-position="${values['position4']}">${values['pilot4']}</li>
            </ul>
            <ul class="freqs">
                <li class="${values['freq1']}" >${values['freq1']}</li>
                <li class="${values['freq2']}" >${values['freq2']}</li>
                <li class="${values['freq3']}" >${values['freq3']}</li>
                <li class="${values['freq4']}" >${values['freq4']}</li>
            </ul>
        </div>
    `;
}

/*
 * Function to add event listeners on mouseover for pilots highlight
 * This will also read position and highlight the one in postition 1 or 2
 */
function pilotEvents(){
    let listItems = document.querySelectorAll('.pilotlist li');

    listItems.forEach(item => {
        // Highligh winners
        const position = item.getAttribute("data-position");
        if (position === "1" || position === "2") {
            item.classList.add("winner");
        }

        // On mouse over --> highlight
        item.addEventListener('mouseover', () => {
            let pilotName = item.textContent;
            listItems.forEach(li => {
                if (li.textContent === pilotName) {
                    li.classList.add('highlight');
                }
            });
        });

        // On mouse out --> stop highlight
        item.addEventListener('mouseout', () => {
            let pilotName = item.textContent;
            listItems.forEach(li => {
                if (li.textContent === pilotName) {
                    li.classList.remove('highlight');
                }
            });
        });
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
    setInterval(refresh, 10000);
}

document.addEventListener("DOMContentLoaded", main());
