jQuery(document).ready( function($){         
    $(".quick-list").on("submit", function(e){
        
    // We'll pass this variable to the PHP function example_ajax_request
    var fruit = 'Banana';
    var listname = $(".quick-list .listname").val();   
    
    // This does the ajax request
    $.ajax({
        url: ajaxurl, // or example_ajax_obj.ajaxurl if using on frontend
        data: {
            'action': 'example_ajax_request',
            'fruit' : fruit,
            listname : listname
        },
        success:function(data) {
            // This outputs the result of the ajax request
            console.log(data);
         


            window.location.hash = "#pag-top"
            location.reload();

        },
        error: function(errorThrown){
            console.log(errorThrown);
        }
    }); 


        // e.preventDefault();
        // var listname = $(".quick-list .listname").val();    
       
    




        // $.ajax({
        //     url : bp_group_add.ajax_url,
           
        //     data : {
        //         action : 'new_list',
        //         listname : listname
        //     },
        //     success:function(data) {
        //         // This outputs the result of the ajax request
        //         alert('data:'+data);
        //         console.log(data);
        //     },
        //     error: function(errorThrown){
        //         alert(errorThrown);
        //         console.log(errorThrown);
        //     }
        // });
       

        return false;
    });





});