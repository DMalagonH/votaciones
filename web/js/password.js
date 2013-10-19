var minLength = 6, nivelPass = 0, maxNivelPass = 6;
    
function validatePass(pass)
{
    nivelPass = 0;

    if(pass.length >= minLength)
    {
        nivelPass += 1;
        if(pass.match('[a-z]') && pass.match('[A-Z]'))
        {
            nivelPass += 1;
//                alert('MAYUSCULAS');
        }
        if(pass.match('[0-9]') && (pass.match('[a-z]') || pass.match('[A-Z]')))
        {
            nivelPass += 1;
//                alert('numeros');
        }
        if(pass.match('[`,´,~,!,@,#,$,&,%, ,^,(,),+,=,{,},[,\\],|,-,_,/,*,$,=,°,¡,?,¿,\\,/,,.,;,:,",\',<,>]') && (pass.match('[a-z]') || pass.match('[A-Z]')))
        {
            nivelPass += 1;
//                alert('car especiales');
        }
        if(pass.length >= minLength+2)
        {
            nivelPass += 1;
        }
        if(pass.length >= minLength+4)
        {
            nivelPass += 1;
        }  
    }
    else if(pass.length >= 1)
    {
        nivelPass += 0.5;
    }
    else
    {
        nivelPass += 0.05;
    }

    return nivelPass;
}


$(function(){
    
    var pass1 = $('.password1'),
	pass2 = $('.password2'),
	progress = $('#progress-bar-pass'),
    span_conf = $('#span_conf');
    
        
    pass1.on('keyup', function(){
        nivelPass = validatePass(pass1.val());        
        porc = (nivelPass*100)/maxNivelPass;        
        progress.css('width', porc+'%');
        
        if(porc < 34) color = 'bar-danger';//rojo
        else if(porc >= 34 && porc < 66) color = 'bar-warning';//amarillo
        else if(porc >= 66 && porc < 90) color = 'bar-yellow';//verde claro
        else if(porc >= 90) color = 'bar-success';// verde oscuro
        
        progress.removeClass('bar-danger');
        progress.removeClass('bar-warning');
        progress.removeClass('bar-yellow');
        progress.removeClass('bar-success');
        
        progress.addClass(color);
        
        if((pass1.val() != '') && (pass1.val() == pass2.val()))
        {
            span_conf.removeClass('bar-danger');
            span_conf.addClass('bar-success');
        }
        else
        {
            span_conf.removeClass('bar-success');
            span_conf.addClass('bar-danger');
        }
        
    });
   
    pass2.on('keyup', function(){
        if((pass1.val() != '') && (pass1.val() == pass2.val()))
        {
            span_conf.removeClass('bar-danger');
            span_conf.addClass('bar-success');
        }
        else
        {
            span_conf.removeClass('bar-success');
            span_conf.addClass('bar-danger');
        }
    });
});