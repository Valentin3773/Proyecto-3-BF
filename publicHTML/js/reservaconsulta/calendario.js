function generarCalendario(fechaAc, fechasDispon, mes, year, fechaselec) {
    
    const monthyear = $('#monthyear h2');
    const calendarbody = $('#calendarbody tbody');  

    calendarbody.empty();

    const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio','Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    
    fechaActual = new Date(fechaAc);

    let fechasDisponibles = [];

    fechasDispon.forEach(element => fechasDisponibles.push(new Date(element)));
    fechasDisponibles.forEach(element => element.setHours(24, 0, 0, 0));

    monthyear.text(meses[mes] + ', ' + year);

    const primerDia = !(new Date(year, mes, 1).getDay() === 0) ? new Date(year, mes, 1).getDay() - 1 : 6;
    
    const diasDelMes = new Date(year, mes + 1, 0).getDate();

    let dateCount = 1;

    let fechaDia;

    for (let i = 0; i < 6; i++) {

        let fila = $('<tr></tr>');

        for (let j = 0; j < 7; j++) {

            if ((i === 0 && j < primerDia) || (dateCount > diasDelMes)) fila.append('<td></td>');
            
            else {
                
                fechaDia = new Date(year, mes, dateCount);

                if(fechaDia < fechaActual) fila.append('<td><div id="dia" class="pasado" data-dia="' + dateCount + '" data-mes="' + mes + '" data-year="' + year + '">' + dateCount + '</div></td>');

                else if(!fechasDisponibles.some(f => f.getTime() === fechaDia.getTime())) fila.append('<td><div id="dia" class="ocupado" data-dia="' + dateCount + '" data-mes="' + mes + '" data-year="' + year + '">' + dateCount + '</div></td>');

                else {

                    if(fechaselec !== null && fechaselec.getTime() === fechaDia.getTime()) {

                        fila.append('<td><div id="dia" class="disponible seleccionado" data-dia="' + dateCount + '" data-mes="' + mes + '" data-year="' + year + '">' + dateCount + '</div></td>');
                        $('#calendariocontainer h3.fechaselec').fadeIn(200).html(fechaselec.getDate() + '/' + Number(fechaselec.getMonth() + 1) + '/' + fechaselec.getFullYear());
                    }
                    else fila.append('<td><div id="dia" class="disponible" data-dia="' + dateCount + '" data-mes="' + mes + '" data-year="' + year + '">' + dateCount + '</div></td>');
                } 
        
                dateCount++;
            }
        }

        calendarbody.append(fila);
    }
}

function cargarHorasDisponibles(horasDispo, horarios) {

    const conthoras = $('#elegirhora #horasdisponibles');

    let horasDisponibles = [];
    horasDispo.forEach(element => {

        let timeParts = element.split(':');
        horasDisponibles.push({

            hora: Number(timeParts[0]),
            minuto: Number(timeParts[1]),
            segundo: 0
        });
    });

    horasDisponibles.forEach(element => {
        
        if(element.minuto === 0) conthoras.append('<li class="hora" data-hora="' + element.hora + '" data-minuto="' + element.minuto + '"><input type="radio" name="hora" value="' + element.hora + ':' + element.minuto + '0"><span>' + element.hora + ':' + element.minuto + '0</span></li>');
        else conthoras.append('<li class="hora" data-hora="' + element.hora + '" data-minuto="' + element.minuto + '"><input type="radio" name="hora" value="' + element.hora + ':' + element.minuto + '"><span>' + element.hora + ':' + element.minuto + '</span></li>');
    });

    $('#elegirhora .hora').on('click', function() {

        $(this).children('input').prop('checked', true);

        if ($('.hora input:checked').val()) {

            $('#elegirhora #btnsmov #siguienteform').prop('disabled', false).css({ 'background-color': 'rgb(0, 178, 255, 1)' });

            let horasplit = $('.hora input:checked').val().split(':');

            if (infoconsulta.paso > 3 && (horasplit[0] != infoconsulta.hora.hora || horasplit[1] != infoconsulta.hora.minuto)) {

                infoconsulta.paso = 3;
                moveProgressBar();
                infoconsulta.hora = null;
                infoconsulta.asunto = null;
            }
        }
    });
}