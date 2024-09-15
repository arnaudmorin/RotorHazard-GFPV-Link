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
        <div class="race" id="race9"></div>
        <div class="race" id="race10"></div>
        <div class="race" id="race11"></div>
        <div class="race" id="race12"></div>
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
        <div class="race" id="race21"></div>
        <div class="race" id="race22"></div>
        <div class="race" id="race23"></div>
        <div class="race" id="race24"></div>
    </div>

    <div class="round">
        <div class="race" id="race45"></div>
        <div class="race" id="race46"></div>
        <div class="race" id="race47"></div>
        <div class="race" id="race48"></div>
    </div>

    <div class="round">
        <div class="race" id="race55"></div>
        <div class="race" id="race56"></div>
    </div>

    <div class="round">
        <div class="race" id="race60"></div>
    </div>

    <!-- Final -->
    <div class="round">
        <div class="race" id="race62"></div>
    </div>
</section>

<section id="losers-bracket">
    <h2>Lower-bracket</h2> 

    <div class="round">
        <div class="race" id="race25"></div>
        <div class="race" id="race26"></div>
        <div class="race" id="race27"></div>
        <div class="race" id="race28"></div>
        <div class="race" id="race29"></div>
        <div class="race" id="race30"></div>
        <div class="race" id="race31"></div>
        <div class="race" id="race32"></div>
    </div>

    <div class="round">
        <div class="race" id="race33"></div>
        <div class="race" id="race34"></div>
        <div class="race" id="race35"></div>
        <div class="race" id="race36"></div>
        <div class="race" id="race37"></div>
        <div class="race" id="race38"></div>
        <div class="race" id="race39"></div>
        <div class="race" id="race40"></div>
    </div>

    <div class="round">
        <div class="race" id="race41"></div>
        <div class="race" id="race42"></div>
        <div class="race" id="race43"></div>
        <div class="race" id="race44"></div>
    </div>

    <div class="round">
        <div class="race" id="race49"></div>
        <div class="race" id="race50"></div>
        <div class="race" id="race51"></div>
        <div class="race" id="race52"></div>
    </div>

    <div class="round">
        <div class="race" id="race53"></div>
        <div class="race" id="race54"></div>
    </div>

    <div class="round">
        <div class="race" id="race57"></div>
        <div class="race" id="race58"></div>
    </div>

    <div class="round">
        <div class="race" id="race59"></div>
    </div>

    <div class="round">
        <div class="race" id="race61"></div>
    </div>
</section>
<script>

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
        name: "Race 5",
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
        name: "Race 6",
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
        name: "Race 9",
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
        name: "Race 10",
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
        name: "Race 11",
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
        name: "Race 13",
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
</script>
<script src="script-bracket.js"></script>
