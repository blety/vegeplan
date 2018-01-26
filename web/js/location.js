$(document).ready(function () {

});

function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    console.log(ev.target);
    ev.dataTransfer.setData('vegetableId', $(ev.target).data('vegetableId'));
    ev.dataTransfer.setData('locationId', $(ev.target).data('locationId'));
}

function drop(ev) {
    $('#vegetable-quantity-modal').modal('toggle');
}

function submit(locationId, vegetableId) {
    ev.preventDefault();
    /*const vegetableId = ev.dataTransfer.getData('vegetableId');
    const locationId = ev.dataTransfer.getData('locationId');
*/
    $.ajax({
        url: Routing.generate('update_location_vegetable', {
            'locationId': locationId,
            'vegetableId': vegetableId
        }),
        dataType: 'json',
        type: "POST",
        success: function (data) {

        }
    });

}