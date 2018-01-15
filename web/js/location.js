$(document).ready(function () {
    $('span.grid-cell').on('click',function(){
        const x = $(this).data('x');
        const y = $(this).data('y');


        /*$.ajax({
            url:Routing.generate('settings_vendors_module_business_category_edit',{'businessCategoryId':businessCategoryId}),
            type: "POST",
            success: function (data) {
                $('.modalEdit').html(data);
                $('#business-category-edit').modal('show');
                // On utilise cette fonction une nouvelle fois pour afficher correctement les switchs de la popup, puisqu'ils sont ajoutés dynamiquement.
                activeSwitch()
                $(document).plainOverlay('hide');
            }
        });*/
    });

});
