$(document).ready(function() {
    $('.table-triable').DataTable( {
        "paging": false,
        "info": false, 
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Data export'
            },
            {
                extend: 'pdfHtml5',
                title: 'Data export'
            } 
        ]
        ,
        "oLanguage": {
            "sSearch": "<i class='glyphicon glyphicon-search'></i> Rechercher : ",
            "sZeroRecords": "<i class='glyphicon glyphicon-remove'></i> Aucun résultat",
            }

    } );
 

    $(".table-triable_desc").dataTable( {
        "paging": false,
        "order": [[ 0, "desc" ]], 
        "info": false, 
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Data export'
            },
            {
                extend: 'pdfHtml5',
                title: 'Data export'
            } 
        ]
        ,
        "oLanguage": {
            "sSearch": "<i class='glyphicon glyphicon-search'></i> Rechercher : ",
            "sZeroRecords": "<i class='glyphicon glyphicon-remove'></i> Aucun résultat",
            }
            });
            

    $(".table-triable3").dataTable( {
        "paging": false,
        "info": false, 
        "oLanguage": {
            "sSearch": "<i class='glyphicon glyphicon-search'></i> Rechercher : ",
            "sZeroRecords": "<i class='glyphicon glyphicon-remove'></i> Aucun résultat",
            
            }
        });
                  
    $(".table-triable_simple").dataTable( {
        "paging": false,
        "info": false,
        "bFilter": false,
        "bInfo": false
            }); 
 
                  
    $(".table-triable_simple_desc").dataTable( {
        "paging": false,
        "order": [[ 0, "desc" ]], 
        "info": false,
        "bFilter": false,
        "bInfo": false
            }); 
            
}); 


            
            