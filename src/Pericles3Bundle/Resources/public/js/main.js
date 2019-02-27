/**
 * Created by Julien on 26/03/2016.
 */

function getBootstrapAlert(titre, corps, cssclass){
    return "<div class='alert "+cssclass+" alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Fermer'><span aria-hidden='true'>&times;</span></button><strong>"+titre+"</strong> "+corps+"</div>";
}

function OpenInNewTab(url) {
    var win = window.location=url;
}



        function ajax_reload_zone_id(id)
        { 
            console.log($(id));
            
            
            ajax_id_url(id,$(id).data('reload_with'));
        }

        function ajax_id_url(id,url){
            
            
            $.ajax({
                type: "POST",
                url: url ,
                dataType: "html",
                success: function(response) {
                    $(id).html(response);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown)
                {
                    alert('Error +++ : ' +  errorThrown);
                }
            });
        }

	$('#body').on('click', '.DeleteBoxBefore', function() {
                
                if ($(this).data('btn_lib'))
                {
                    $('#DeleteValid').html($(this).data('btn_lib'));
                }
                if ($(this).data('fenetre_titre'))
                {
                    $('#myModalLabel').html($(this).data('fenetre_titre'));
                }
                
                
                $('#modal_text_before_delete').html($(this).data('invite'));
                $('#DeleteValid').data("url_delete_ajax",$(this).data('url_delete_ajax'));
                $('#DeleteValid').data("url_delete",$(this).data('url_delete'));
                $('#DeleteValid').data("zone_to_reload",$(this).data('zone_to_reload'));
                $('#DeleteValid').data("zone_to_reload_whithid",$(this).data('zone_to_reload_whithid'));
                $('#ModalDelete').modal('show');        
	});
        
        
        
	$('#body').on('click', '.CantDeleteBoxBefore', function() {
                $('#modal_text_delete_cant').html($(this).data('invite'));
                $('#ModalDeleteCant').modal('show');        
	});
        
        
        
        
        $("#DeleteValid").click(function() {
             
              
            if ($(this).data('url_delete_ajax'))
            {
                var zone_to_reload=$(this).data('zone_to_reload');
                var zone_to_reload_whithid=$(this).data('zone_to_reload_whithid');
                
                
                if (zone_to_reload_whithid)
                {
                    $.ajax({
                    type: "POST",
                    url: $(this).data('url_delete_ajax'),
                    dataType: "json",
                    success: function (response) {
                        $('#ModalDelete').modal('hide');
                            ajax_reload_zone_id(zone_to_reload_whithid);
                    }
                    ,
                    error: function (XMLHttpRequest, textStatus, errorThrown)
                    {
                        $('#ModalDelete').modal('hide');
                        alert('Error : ' + errorThrown);
                    }
                    }); 
                }
                else
                {

                    $.ajax({
                    type: "POST",
                    url: $(this).data('url_delete_ajax'),
                    dataType: "html",
                    success: function (response) {
                        $('#ModalDelete').modal('hide');
                        if (response)
                        {
                            $(zone_to_reload).html(response);
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        $('#ModalDelete').modal('hide');
                        alert('Error : ' + errorThrown);
                        }
                    }); 
                    }
            } 
            else
            {
                window.location.href = $(this).data('url_delete');
            }
            
        });




