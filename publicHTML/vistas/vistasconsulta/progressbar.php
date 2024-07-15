<style>
    #progressbar {
        height: 80%;
        width: 70%;
        background-color: #4294FF;
        border-radius: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 5px;
        overflow: hidden;
        transition-duration: .3s;
        box-shadow: 1px 1px 2px rgba(0, 0, 0, .4);
    }
    #progressbarbb {
        position: relative;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        overflow: hidden;
        border-radius: 40px;
        --barwidth: 25%;
    }
    #progressbarbb::after {
        position: absolute;
        width: var(--barwidth);
        height: 100%;
        left: 0;
        content: '';
        background-color: #0AF0AB;
        border-radius: 40px;
        transition-duration: .5s;
        box-shadow: 0 0 4px rgba(0, 0, 0, .25);
        clip-path: polygon(0% 0%, 90% 0, 100% 50%, 90% 100%, 0% 100%);
    }
    #progressbar .paso {
        color: white;
        font-family: RobotoSerif, 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        font-weight: 600;
        font-size: 25px;
        z-index: 5;
        width: 25%;
        text-align: center;
        text-shadow: 1px 1px 3px rgba(255, 255, 255, 0.5);
        cursor: pointer;
    }
</style>

<script>
    function moveBar(paso) {

        switch (paso) {

            case 1: $('#progressbarbb').css({ '--barwidth': '25%' });
                break;

            case 2: $('#progressbarbb').css({ '--barwidth': '50%' });
                break;

            case 3: $('#progressbarbb').css({ '--barwidth': '75%' });
                break;

            case 4: $('#progressbarbb').css({ '--barwidth': '110%' });
                break;
        }
    }
</script>

<div id="progressbar">

    <div id="progressbarbb">

        <div class="paso odontologo">Odontólogo</div>
        <div class="paso fecha">Fecha</div>
        <div class="paso hora">Hora</div>
        <div class="paso confirmar">Confirmar</div>

    </div>

</div>