function funcionguardarCambios($data){

    if($data['tipo'] === "$1"){
        let url = "backend/perfil/updateODP.php";let svdata = {name: $data['namedata'], value: $data['dato'], oldvalue: $data['old']};

        $.ajax({

            type: 'POST',
            url: url,
            data: JSON.stringify(svdata),
            contentType: 'application/json',
            success: response => {
    
                if (response.error === undefined) createPopup('Nuevo Aviso',response.enviar);
    
                else createPopup('Nuevo Aviso',response.error);
            },
            error: (jqXHR, estado, outputError) => {
    
                console.log("Error al procesar la solicitud: 3" + outputError+estado+jqXHR);
            }
        });

    } else if ($data['tipo'] === "$") {
        let url = "backend/perfil/updateCLP.php";let svdata = {name: $data['namedata'], value: $data['dato'], oldvalue: $data['old']};
        
        $.ajax({

            type: 'POST',
            url: url,
            data: JSON.stringify(svdata),
            contentType: 'application/json',
            success: response => {
    
                if (response.error === undefined) createPopup('Nuevo Aviso',response.enviar);
    
                else createPopup('Nuevo Aviso',response.error);
            },
            error: (jqXHR, estado, outputError) => {
    
                alert("Error al procesar la solicitud: " + outputError);
            }
        });

    } else {
        createPopup("Nuevo Aviso","A Sucedido un Error Inseperado");
    }
}