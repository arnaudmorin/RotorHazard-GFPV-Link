
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
 * Get Pinch distance
 */
function getPinchDistance(touch1, touch2) {
    const dx = touch2.clientX - touch1.clientX;
    const dy = touch2.clientY - touch1.clientY;
    return Math.sqrt(dx * dx + dy * dy);
}

/*
 * Function that read the current translate
 */
function getTranslate(str){
    const translateRegex = /translate\(([-\d.]+px),\s*([-\d.]+px)\)/;

    const result = translateRegex.exec(str);

    if (result) {
        return [parseInt(result[1]), parseInt(result[2])];
    } else {
        return [0, 0];
    }
}

/*
 * Move the bracket on the page
 */
function enableDrag(){
    const mainElement = document.querySelector('main');
    const bodyElement = document.querySelector('body');
    const zoomSpeed = 0.03;     // Zoom speed
    let scale = 1;              // zoom initial scale
    let startX, startY, offsetX = 0, offsetY = 0, isDragging = false;
    let initialDistance = 0;

    function zoom(e){
	if (e.deltaY < 0) {
	    scale += zoomSpeed;

            // Avoid too much zoom
	    if (scale > 1.5) scale = 1.5;
	} else {
	    scale -= zoomSpeed;

	    // Avoid too small zoom
	    if (scale < 0.5) scale = 0.5;
	}
        let currentTransform = mainElement.style.transform;
        currentTransform = currentTransform.replace(/scale\([^)]+\)/, '');
	mainElement.style.transform = `scale(${scale})` + currentTransform;
    }

    function startDrag(e) {
        if (e.type === 'touchstart' && e.touches.length === 2) {
            // On mobile, pinch with two fingers
            initialDistance = getPinchDistance(e.touches[0], e.touches[1]);
            bodyElement.style.cursor = 'zoom-in';
        } else {
            isDragging = true;
            startX = e.type === 'touchstart' ? e.touches[0].clientX : e.clientX;
            startY = e.type === 'touchstart' ? e.touches[0].clientY : e.clientY;
            [offsetX, offsetY] = getTranslate(mainElement.style.transform);
            bodyElement.style.cursor = 'grabbing';
        }
    }

    function drag(e) {
        if (e.type === 'touchmove' && e.touches.length === 2) {
            const newDistance = getPinchDistance(e.touches[0], e.touches[1]);
            const scaleChange = newDistance / initialDistance;
            scale *= scaleChange;
            // Avoid too much zoom
	    if (scale > 1.5) scale = 1.5;
	    // Avoid too small zoom
	    if (scale < 0.1) scale = 0.1;
            let currentTransform = mainElement.style.transform;
            currentTransform = currentTransform.replace(/scale\([^)]+\)/, '');
            mainElement.style.transform = `scale(${scale})` + currentTransform;
            initialDistance = newDistance;
        } else {
            if (!isDragging) return;

            const x = e.type === 'touchmove' ? e.touches[0].clientX : e.clientX;
            const y = e.type === 'touchmove' ? e.touches[0].clientY : e.clientY;

            const dx = (x - startX)/scale;
            const dy = (y - startY)/scale;

            let currentTransform = mainElement.style.transform;
            currentTransform = currentTransform.replace(/translate\([^)]+\)/, '');
            mainElement.style.transform = currentTransform + `translate(${(offsetX + dx)}px, ${(offsetY + dy)}px)`;
        }
    }

    function endDrag(e) {
        isDragging = false;
        bodyElement.style.cursor = '';
    }

    // Computer
    window.addEventListener('mousedown', startDrag);
    window.addEventListener('mousemove', drag);
    window.addEventListener('mouseup', endDrag);
    window.addEventListener('wheel', zoom);

    // Mobile
    window.addEventListener('touchstart', startDrag);
    window.addEventListener('touchmove', drag);
    window.addEventListener('touchend', endDrag);
}

/*
 * Prevent scrolling
 */
function preventScrolling(event) {
    event.preventDefault();
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

    // Prevent scrolling
    document.addEventListener('touchmove', preventScrolling, { passive: false });
    document.addEventListener('wheel', preventScrolling, { passive: false });
    enableDrag();
}

document.addEventListener("DOMContentLoaded", main());
