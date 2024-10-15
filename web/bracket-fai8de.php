<section id="bracket">
    <h2>Upper-bracket</h2> 
    <div class="round">
        <div class="race" id="race1"></div>
        <div class="race" id="race2"></div>
    </div>

    <div class="round">
        <div class="race" id="race4"></div>
    </div>

    <!-- final -->
    <div class="round">
        <div class="race" id="race6"></div>
    </div>
</section>

<section id="losers-bracket">
    <h2>Lower-bracket</h2> 

    <div class="round">
        <div class="race" id="race3"></div>
    </div>

    <div class="round">
        <div class="race" id="race5"></div>
    </div>
</section>

<script>
// FAI 8 DE
const races = {
    race1: {
        name: "Race 1",
        pilot1: "Qualification - 1",
        pilot2: "Qualification - 4",
        pilot3: "Qualification - 5",
        pilot4: "Qualification - 8",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
        position1: "",
        position2: "",
        position3: "",
        position4: "",
    },
    race2: {
        name: "Race 2",
        pilot1: "Qualification - 2",
        pilot2: "Qualification - 3",
        pilot3: "Qualification - 6",
        pilot4: "Qualification - 7",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
        position1: "",
        position2: "",
        position3: "",
        position4: "",
    },
    race3: {
        name: "Race 3",
        pilot1: "Race 1 - 4",
        pilot2: "Race 1 - 3",
        pilot3: "Race 2 - 3",
        pilot4: "Race 2 - 4",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
        position1: "",
        position2: "",
        position3: "",
        position4: "",
    },
    race4: {
        name: "Race 4",
        pilot1: "Race 1 - 2",
        pilot2: "Race 1 - 1",
        pilot3: "Race 2 - 1",
        pilot4: "Race 2 - 2",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
        position1: "",
        position2: "",
        position3: "",
        position4: "",
    },
    race5: {
        name: "Race 5",
        pilot1: "Race 3 - 2",
        pilot2: "Race 3 - 1",
        pilot3: "Race 4 - 3",
        pilot4: "Race 4 - 4",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
        position1: "",
        position2: "",
        position3: "",
        position4: "",
    },
    race6: {
        name: "Final",
        pilot1: "Race 4 - 2",
        pilot2: "Race 4 - 1",
        pilot3: "Race 5 - 1",
        pilot4: "Race 5 - 2",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
        position1: "",
        position2: "",
        position3: "",
        position4: "",
    },
};
</script>
<script src="script-bracket.js"></script>
