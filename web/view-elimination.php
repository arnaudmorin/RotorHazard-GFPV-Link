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
            current_heat_id = data["current_heat_id"];

            // Sort pilots
            pilots.sort((a, b) => parseInt(a.position) - parseInt(b.position));

            // Update current heat
            updateCurrentHeat(current_heat_id, heats);

            // Update next heat
            updateNextHeat(current_heat_id, heats);

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
function updateCurrentHeat(current_heat_id, heats){
    // Empty the heats div
    const currentEl = document.getElementById("current");
    currentEl.innerHTML = '';

    heats.forEach(heat => {
        if (heat['id'] == current_heat_id) {
            addHeatDOM(heat, currentEl);
        }
    });
}

function updateNextHeat(current_heat_id, heats){
    // Empty the heats div
    const nextEl = document.getElementById("next");
    nextEl.innerHTML = '';

    heats.forEach(heat => {
        if (heat['id'] == (current_heat_id+1)) {
            addHeatDOM(heat, nextEl);
        }
    });
}

function updateHeats(heats){
    // Empty the heats div
    const heatsEl = document.getElementById("heats");
    heatsEl.innerHTML = '';

    // Now append new heats
    heats.forEach(heat => {
    if (heat['id'] < current_heat_id) {
            addHeatDOM(heat, heatsEl);
        }
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
                <div class="left">${heat['position1']}</div>
                <div class="center">${heat['pilot1']}</div>
                <div class="right ${heat['freq1']}">${heat['freq1']}</div>
            </ons-list-item>
            <ons-list-item>
                <div class="left">${heat['position2']}</div>
                <div class="center">${heat['pilot2']}</div>
                <div class="right ${heat['freq2']}">${heat['freq2']}</div>
            </ons-list-item>
            <ons-list-item>
                <div class="left">${heat['position3']}</div>
                <div class="center">${heat['pilot3']}</div>
                <div class="right ${heat['freq3']}">${heat['freq3']}</div>
            </ons-list-item>
            <ons-list-item>
                <div class="left">${heat['position4']}</div>
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
    pilotEl.innerHTML = `
        <div class="left">${pilot.position}</div>
        <div class="center">${pilot.pilot}</div>
    `;

    leaderboardEl.appendChild(pilotEl);
}

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
        <ons-tab page="current.html" label="Current" icon="fa-cube" active></ons-tab>
        <ons-tab page="next.html" label="Next" icon="fa-angle-double-right"></ons-tab>
        <ons-tab page="heats.html" label="Past" icon="fa-cubes"></ons-tab>
        <ons-tab page="leaderboard.html" label="Results" icon="fa-list-ol"></ons-tab>
    </ons-tabbar>
</ons-page>
</ons-navigator>

<template id="current.html">
<ons-page>
    <div id="current"></div>
</ons-page>
</template>

<template id="next.html">
<ons-page>
    <div id="next"></div>
</ons-page>
</template>

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

</body>
</html>
