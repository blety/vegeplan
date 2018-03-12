$(document).ready(function () {

    $('#top-menu li').removeClass('active');
    $('#locations-menu').addClass('active');

    // Sauvegarde du nom du terrain
    $('#saveName').on('click', function(){
       const locationId = $('button#saveName').data('locationId');
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
                    validationAnimation($('#saveNameInput'));
                }else {
                    console.log('Erreur dans la sauvegarde du nom du terrain');
                }
            }
        }); 
    });

    // Sauvegarde de la surface du terrain
    $('#saveSurface').on('click', function() {
        const locationId = $('button#saveName').data('locationId');
        const surface = $('#saveSurfaceInput').val();

        $.ajax({
            url: Routing.generate('update_location_surface', {
                'locationId': locationId,
                'surface': surface
            }),
            dataType: 'json',
            type: "POST",
            success: function (data) {
                if (data === true) {
                    validationAnimation($('#saveSurfaceInput'));
                    location.reload();
                }else {
                    console.log('Erreur dans la sauvegarde de la surface du terrain');
                }
            }
        });
    });

    $('#saveSheltered').on('click', function () {
       const locationId = $('button#saveName').data('locationId');

        $.ajax({
            url: Routing.generate('switch_sheltered', {
                'locationId': locationId,
            }),
            dataType: 'json',
            type: "POST",
            success: function (data) {
                console.log(data);
            }
        });
    });

    // Calcul de la location à la prochaine période
    $('#calculateNextPeriod').on('click', function() {
        var locationId = $(this).data('locationId');
        $.ajax({
            url: Routing.generate('calculate_next_period', {
                'locationId': locationId,
            }),
            //dataType: 'json',
            //type: "POST",
            success: function (data) {
                if (data) {
                    $('.location').html(data);
                    $('#calculateNextPeriod').html();
                    $('#nextPeriodButtonWrapper').html('<button class="btn btn-primary" onclick="location.reload();">Emplacement actuel</button>');
                }
            },
            error: function (e) {
                console.log(e);
            }
        });
    });

    $('#vegetable-quantity-modal').on('shown.bs.modal', function () {
        console.log('aaaa');
        // Validation de la popup d'ajout de légume
        $('#vegetable-surface').on('change', function() {
            const vegetableSurface = $('#vegetable-surface').val();
            const locationSurface = $('#saveSurfaceInput').val() - $('#saveSurface').data('totalSurface');
            console.log('test');
            if (vegetableSurface > locationSurface) {
                $('#vegetable-surface').css('border', '2px solid red');
            }else {
                $('#vegetable-surface').css('border', '2px solid green');
            }
        });
    });
});

function validationAnimation(selector) {
    selector.toggleClass('validated-input');

    interval = setTimeout(function() {
        selector.removeClass('validated-input');
    }, 600);
}

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
    const surface = parseFloat($('#vegetable-surface').val().replace(',', '.')).toFixed(2);
    const locationSurface = $('#saveSurfaceInput').val() - $('#saveSurface').data('totalSurface');

    if (surface <= locationSurface) {
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
}