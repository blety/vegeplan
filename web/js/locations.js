$(document).ready(function () {
    $('.location').on('click', function () {

    });
});

function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData('vegetableId', $(ev.target).data('vegetableId'));
    ev.dataTransfer.setData('locationId', $(ev.target).data('locationId'));
}

function drop(ev) {
    $('#vegetable-quantity-modal').modal('toggle');
    $('#vegetable-quantity-modal').data('vegetable', ev.dataTransfer.getData('vegetableId'));
}

function submit(locationId) {

    const vegetableId = $('#vegetable-quantity-modal').data('vegetable');
    const surface = $('#vegetable-surface').val();
    console.log(vegetableId);
    console.log(locationId);
    console.log(surface);

    $.ajax({
        url: Routing.generate('update_location_vegetable', {
            'locationId': locationId,
            'vegetableId': vegetableId,
            'surface': surface,
        }),
        dataType: 'json',
        type: "POST",
        success: function (data) {
            if (data == true) {
                $('#vegetable-quantity-modal').modal('hide');
                location.reload();
            }
        }
    });

}