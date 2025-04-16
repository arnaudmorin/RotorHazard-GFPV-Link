<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://unpkg.com/onsenui/css/onsenui.css">
  <link rel="stylesheet" href="https://unpkg.com/onsenui/css/onsen-css-components.min.css">
  <script src="https://unpkg.com/onsenui/js/onsenui.min.js"></script>
  <link rel="stylesheet" href="qualifier.css">
  <script>

/*
 * Fetch data from server
 * This is called at regular interval
 */
function refresh(){
    const params = new URLSearchParams(window.location.search);
    let eventid = params.get("eventid");
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

            pilots = data["ranks"];
            heats = data["heats"];

            // Sort pilots
            pilots.sort((a, b) => parseInt(a.position) - parseInt(b.position));

            // Update heats
            updateHeats(heats);

            // Update leaderboard
            updateLeaderboard(pilots);
        })
        .catch(error => {
            console.error('There has been a problem with your fetch operation:', error);
        });
}


/*
 * Function to update heats
 */
function updateHeats(heats){
    // Empty the heats div
    const heatsEl = document.getElementById("heats");
    heatsEl.innerHTML = '';

    // Now append new heats
    heats.forEach(heat => {
        addHeatDOM(heat, heatsEl);
    });
}

/*
 * Function to create a heat content
 */
function addHeatDOM(heat, heatsEl){
    const heatEl = document.createElement('div');
    heatEl.classList.add('heat');
    heatEl.innerHTML = `
        <ons-list-title>${heat['name']}</ons-list-title>
        <ons-list modifier="inset">
            <ons-list-item>
                <div class="center">${heat['pilot1']}</div>
                <div class="right ${heat['freq1']}">${heat['freq1']}</div>
            </ons-list-item>
            <ons-list-item>
                <div class="center">${heat['pilot2']}</div>
                <div class="right ${heat['freq2']}">${heat['freq2']}</div>
            </ons-list-item>
            <ons-list-item>
                <div class="center">${heat['pilot3']}</div>
                <div class="right ${heat['freq3']}">${heat['freq3']}</div>
            </ons-list-item>
            <ons-list-item>
                <div class="center">${heat['pilot4']}</div>
                <div class="right ${heat['freq4']}">${heat['freq4']}</div>
            </ons-list-item>
        </ons-list>
    `;
    heatsEl.appendChild(heatEl);
}

/*
 * Function to update leaderboard
 */
function updateLeaderboard(pilots){
    // Empty the div
    const leaderboardEl = document.getElementById("leaderboard");
    leaderboardEl.innerHTML = '';

    // Now append new results
    pilots.forEach(pilot => {
        addPositionDOM(pilot, leaderboardEl);
    });
}

/*
 * Function to create a leaderboard line content
 */
function addPositionDOM(pilot, leaderboardEl){
    const pilotEl = document.createElement('ons-list-item');
    pilotEl.onclick = () => document.querySelector('#navigator').bringPageTop("results.html", { data: pilot});
    pilotEl.innerHTML = `
        <div class="left">${pilot.position}</div>
        <div class="center">${pilot.pilot}</div>
        <div class="right">${pilot.extra}</div>
    `;

    leaderboardEl.appendChild(pilotEl);
}

/*
 * Function to update the pilot laps page based on pilot selection
 */
document.addEventListener('show', ({ target }) => {
    if (target.matches('#results')) {
        const pilot = document.querySelector('#navigator').topPage.data;
        const lapsEl = document.querySelector('#laps');

        // Empty the laps
        lapsEl.innerHTML = ""

        // Add new laps
        pilot.laps.forEach(lap => {
            const lapItem = document.createElement('ons-list-item');
            lapItem.textContent = lap;
            lapsEl.appendChild(lapItem);
        });
    }
});

/*
 * First function called when DOM is ready
 */
function main () {
    // Pull data from server
    refresh();

    // Set interval to pull again in a loop
    //setInterval(refresh, 60000);
}

ons.disableAutoStyling()
document.addEventListener("DOMContentLoaded", main());

  </script>
</head>
<body>

<ons-navigator id="navigator">
<ons-page>
    <ons-tabbar swipeable position="auto">
        <ons-tab page="heats.html" label="Heats" icon="fa-cubes" active></ons-tab>
        <ons-tab page="leaderboard.html" label="Results" icon="fa-list-ol"></ons-tab>
    </ons-tabbar>
</ons-page>
</ons-navigator>

<template id="heats.html">
<ons-page>
    <div id="heats"></div>
</ons-page>
</template>

<template id="leaderboard.html">
<ons-page>
    <div id="leaderboard"></div>
</ons-page>
</template>

<template id="results.html">
<ons-page id="results">
    <ons-toolbar>
        <div class="center">Tours</div>
    </ons-toolbar>
    <ons-list id="laps"></ons-list>
    <ons-bottom-toolbar>
        <div class="center">
            <ons-button modifier="large" onclick="document.querySelector('ons-navigator').popPage()">back</ons-button>
        </div>
    </ons-bottom-toolbar>
</ons-page>
</template>

</body>
</html>
