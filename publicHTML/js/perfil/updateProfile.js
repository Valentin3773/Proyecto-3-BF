function funcionguardarCambios($data){
    alert("Funca: "+$data['dato']+" / "+$data['tipo']+" / "+$data['namedata']);

    if($data['tipo'] === "$1"){
        let url = "backend/perfil/updateODP.php";let svdata = {name: $data['namedata'], value: $data['dato'], oldvalue: $data['old']};
        alert(svdata['name']+"/"+svdata['value']+"/"+svdata['oldvalue']);

        $.ajax({

            type: 'POST',
            url: url,
            data: JSON.stringify(svdata),
            contentType: 'application/json',
            success: response => {
    
                if (response.error === undefined) alert('Nuevo Aviso 1'+response.enviar);
    
                else alert('Nuevo Aviso 2'+response.error);
            },
            error: (jqXHR, estado, outputError) => {
    
                console.log("Error al procesar la solicitud: 3" + outputError+estado+jqXHR);
            }
        });

    } else if ($data['tipo'] === "$") {
        let url = "backend/perfil/updateCLP.php";let svdata = {name: $data['namedata'], value: $data['dato'], oldvalue: $data['old']};
        alert(svdata['name']+"/"+svdata['value']+"/"+svdata['oldvalue']);
        
        $.ajax({

            type: 'POST',
            url: url,
            data: JSON.stringify(svdata),
            contentType: 'application/json',
            success: response => {
    
                if (response.error === undefined) alert('Nuevo Aviso '+response.enviar);
    
                else alert('Nuevo Aviso '+response.error);
            },
            error: (jqXHR, estado, outputError) => {
    
                alert("Error al procesar la solicitud: " + outputError);
            }
        });

    } else {
        alert("A Sucedido un Error Inseperado");
    }
}

function funcioncancelarCambios($data){
    alert("AAA: "+$data['old']);
}