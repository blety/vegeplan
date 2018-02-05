$(document).ready(function () {
    
    $('#saveName').on('click', function(){
       const locationId = $('button').data('locationId');
       const name = $('#saveNameInput').val();
       
       $.ajax({
            url: Routing.generate('update_location_name', {
                'locationId': locationId,
                'name': name
            }),
            dataType: 'json',
            type: "POST",
            success: function (data) {
                if (data === true) {
                    console.log('saved');
                }else {
                    console.log('Erreur dans la sauvegarde du nom du terrain');
                }
            }
        }); 
    });
});


function drag(ev) {
    ev.dataTransfer.setData('vegetableId', $(ev.target).data('vegetableId'));
    ev.dataTransfer.setData('locationId', $(ev.target).data('locationId'));
}

function allowDrop(ev) {
    ev.preventDefault();
}


function drop(ev) {
    $('#vegetable-quantity-modal').modal('toggle');
    $('#vegetable-quantity-modal').data('vegetable', ev.dataTransfer.getData('vegetableId'));
}

function submit(locationId) {
    const vegetableId = $('#vegetable-quantity-modal').data('vegetable');
    const surface = $('#vegetable-surface').val();

    $.ajax({
        url: Routing.generate('update_location_vegetable', {
            'locationId': locationId,
            'vegetableId': vegetableId,
            'surface': surface,
        }),
        dataType: 'json',
        type: "POST",
        success: function (data) {
            if (data === true) {
                $('#vegetable-quantity-modal').modal('hide');
                location.reload();
            }
        }
    });
}