
(function($){

    const uploaderButton=$("#uploaderButton");
    const uploadForm=$("#uploadForm");
    const uploadFile=uploadForm.children('input');
    const allowedFileExt=['txt'];

    const openMessage = (text,className="",time=3000) => {
        const message=$("#message");
        message.removeClass("error");
        message.addClass(className);
        message.text(text);
        setTimeout(()=>message.text(''),time);
        
    }

    uploaderButton.click(function(){
        uploadFile.click();
    });
    uploadForm.submit(e => e.preventDefault() );

    uploadFile.change(e=>{
        $("#loading").addClass('open');
        const result = $("#result");
        const content = $("#content");
        result.removeClass('open');
        content.removeClass('open');
        const file=e.target.value;
        if(!file){
            $("#loading").removeClass('open');
            return;
        }
        const fileExt=file.split('.').pop();
        if(!allowedFileExt.includes(fileExt)){
            e.preventDefault();
            $("#loading").removeClass('open');
            openMessage('Extensión no válida','error');
            return;
        }
        const formData=new FormData(document.getElementById(uploadForm.attr('id')));
        $.ajax({
            type:'post',
            url: uploadForm.attr('action'),
            data:formData,
            contentType: false,
            processData: false,
            success: response=>{
                $("#loading").removeClass('open');
                console.log('Response',response);
                uploadFile.val('');
                if(response.result){
                    result.addClass('open');
                    content.addClass('open');
                    result.html('<p class="title">Resultado</p>');
                    content.html('<p class="title">Contenido del archivo</p>');
                    response.content.forEach(item=>{
                        content.append(`<p class="text-line">${item}</p>`);
                    });
                    if(Array.isArray(response.result)){
                        response.result.forEach(item=>{
                            result.append(`<p class="text-line">${item}</p>`);
                        });
                    } else {
                        result.append(`<p class="text-line">Ganador: <strong>Jugador ${response.result.winner}</strong></p>`);
                        result.append(`<p class="text-line">Diferencia mayor de puntos:<strong> ${response.result.points}</strong></p>`);
                    }
                }
            },
            error: error=>{
                uploadFile.val('');
                console.log('error',error.responseText);
                $("#loading").removeClass('open');
                const msgJson=JSON.parse(error.responseText);
                openMessage(msgJson.message,'error',5000)
            }
        });
    });

})(jQuery)