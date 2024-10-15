<section id="bracket">
    <h2>Upper-bracket</h2> 
    <div class="round">
        <div class="race" id="race1"></div>
        <div class="race" id="race2"></div>
        <div class="race" id="race3"></div>
        <div class="race" id="race4"></div>
        <div class="race" id="race5"></div>
        <div class="race" id="race6"></div>
        <div class="race" id="race7"></div>
        <div class="race" id="race8"></div>
    </div>

    <div class="round">
        <div class="race" id="race9"></div>
        <div class="race" id="race10"></div>
        <div class="race" id="race11"></div>
        <div class="race" id="race12"></div>
    </div>

    <div class="round">
        <div class="race" id="race23"></div>
        <div class="race" id="race24"></div>
    </div>

    <div class="round">
        <div class="race" id="race28"></div>
    </div>

    <!-- Final -->
    <div class="round">
        <div class="race" id="race30"></div>
    </div>
</section>

<section id="losers-bracket">
    <h2>Lower-bracket</h2> 

    <div class="round">
        <div class="race" id="race13"></div>
        <div class="race" id="race14"></div>
        <div class="race" id="race15"></div>
        <div class="race" id="race16"></div>
    </div>

    <div class="round">
        <div class="race" id="race17"></div>
        <div class="race" id="race18"></div>
        <div class="race" id="race19"></div>
        <div class="race" id="race20"></div>
    </div>

    <div class="round">
        <div class="race" id="race21"></div>
        <div class="race" id="race22"></div>
    </div>

    <div class="round">
        <div class="race" id="race25"></div>
        <div class="race" id="race26"></div>
    </div>

    <div class="round">
        <div class="race" id="race27"></div>
    </div>

    <div class="round">
        <div class="race" id="race29"></div>
    </div>
</section>
<script>

// FAI 32 DE
const races = {
    race1: {
        name: "Race 1",
        pilot1: "Qualification - 32",
        pilot2: "Qualification - 1",
        pilot3: "Qualification - 16",
        pilot4: "Qualification - 24",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race2: {
        name: "Race 2",
        pilot1: "Qualification - 25",
        pilot2: "Qualification - 8",
        pilot3: "Qualification - 9",
        pilot4: "Qualification - 17",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race3: {
        name: "Race 3",
        pilot1: "Qualification - 27",
        pilot2: "Qualification - 6",
        pilot3: "Qualification - 11",
        pilot4: "Qualification - 19",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race4: {
        name: "Race 4",
        pilot1: "Qualification - 29",
        pilot2: "Qualification - 4",
        pilot3: "Qualification - 13",
        pilot4: "Qualification - 21",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race5: {
        name: "Race 5",
        pilot1: "Qualification - 30",
        pilot2: "Qualification - 3",
        pilot3: "Qualification - 14",
        pilot4: "Qualification - 22",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race6: {
        name: "Race 6",
        pilot1: "Qualification - 28",
        pilot2: "Qualification - 5",
        pilot3: "Qualification - 12",
        pilot4: "Qualification - 20",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race7: {
        name: "Race 7",
        pilot1: "Qualification - 26",
        pilot2: "Qualification - 7",
        pilot3: "Qualification - 10",
        pilot4: "Qualification - 18",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race8: {
        name: "Race 8",
        pilot1: "Qualification - 31",
        pilot2: "Qualification - 2",
        pilot3: "Qualification - 15",
        pilot4: "Qualification - 23",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race9: {
        name: "Race 9",
        pilot1: "Race 1 - 2",
        pilot2: "Race 1 - 1",
        pilot3: "Race 2 - 1",
        pilot4: "Race 2 - 2",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race10: {
        name: "Race 10",
        pilot1: "Race 3 - 2",
        pilot2: "Race 3 - 1",
        pilot3: "Race 4 - 1",
        pilot4: "Race 4 - 2",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race11: {
        name: "Race 11",
        pilot1: "Race 5 - 2",
        pilot2: "Race 5 - 1",
        pilot3: "Race 6 - 1",
        pilot4: "Race 6 - 2",
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
        name: "Race 13",
        pilot1: "Race 1 - 4",
        pilot2: "Race 2 - 3",
        pilot3: "Race 3 - 3",
        pilot4: "Race 4 - 4",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race14: {
        name: "Race 14",
        pilot1: "Race 5 - 4",
        pilot2: "Race 6 - 3",
        pilot3: "Race 7 - 3",
        pilot4: "Race 8 - 4",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race15: {
        name: "Race 15",
        pilot1: "Race 2 - 4",
        pilot2: "Race 1 - 3",
        pilot3: "Race 4 - 3",
        pilot4: "Race 3 - 4",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race16: {
        name: "Race 16",
        pilot1: "Race 6 - 4",
        pilot2: "Race 5 - 3",
        pilot3: "Race 8 - 3",
        pilot4: "Race 7 - 4",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race17: {
        name: "Race 17",
        pilot1: "Race 9 - 3",
        pilot2: "Race 15 - 2",
        pilot3: "Race 16 - 1",
        pilot4: "Race 10 - 4",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race18: {
        name: "Race 18",
        pilot1: "Race 11 - 3",
        pilot2: "Race 13 - 2",
        pilot3: "Race 14 - 1",
        pilot4: "Race 12 - 4",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race19: {
        name: "Race 19",
        pilot1: "Race 10 - 3",
        pilot2: "Race 16 - 2",
        pilot3: "Race 15 - 1",
        pilot4: "Race 9 - 4",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race20: {
        name: "Race 20",
        pilot1: "Race 12 - 3",
        pilot2: "Race 14 - 2",
        pilot3: "Race 13 - 1",
        pilot4: "Race 11 - 4",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race21: {
        name: "Race 21",
        pilot1: "Race 19 - 2",
        pilot2: "Race 17 - 1",
        pilot3: "Race 18 - 1",
        pilot4: "Race 20 - 2",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race22: {
        name: "Race 22",
        pilot1: "Race 17 - 2",
        pilot2: "Race 19 - 1",
        pilot3: "Race 20 - 1",
        pilot4: "Race 18 - 2",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race23: {
        name: "Race 23",
        pilot1: "Race 9 - 2",
        pilot2: "Race 9 - 1",
        pilot3: "Race 10 - 1",
        pilot4: "Race 10 - 2",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race24: {
        name: "Race 24",
        pilot1: "Race 11 - 2",
        pilot2: "Race 11 - 1",
        pilot3: "Race 12 - 1",
        pilot4: "Race 12 - 2",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race25: {
        name: "Race 25",
        pilot1: "Race 23 - 3",
        pilot2: "Race 21 - 1",
        pilot3: "Race 22 - 2",
        pilot4: "Race 24 - 4",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race26: {
        name: "Race 26",
        pilot1: "Race 24 - 3",
        pilot2: "Race 22 - 1",
        pilot3: "Race 21 - 2",
        pilot4: "Race 23 - 4",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race27: {
        name: "Race 27",
        pilot1: "Race 25 - 2",
        pilot2: "Race 25 - 1",
        pilot3: "Race 26 - 1",
        pilot4: "Race 26 - 2",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race28: {
        name: "Race 28",
        pilot1: "Race 23 - 2",
        pilot2: "Race 23 - 1",
        pilot3: "Race 24 - 1",
        pilot4: "Race 24 - 2",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    race29: {
        name: "Race 29",
        pilot1: "Race 28 - 3",
        pilot2: "Race 27 - 1",
        pilot3: "Race 27 - 2",
        pilot4: "Race 28 - 4",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
    final: {
        name: "Final",
        pilot1: "Race 29 - 2",
        pilot2: "Race 28 - 2",
        pilot3: "Race 28 - 1",
        pilot4: "Race 29 - 1",
        freq1: "",
        freq2: "",
        freq3: "",
        freq4: "",
    },
};
</script>
<script src="script-bracket.js"></script>
