
$(document).ready(function () {
    $('#appbundle_vegetable_primaryColor').on('click', function () {
        $('#appbundle_vegetable_primaryColor').ColorPicker({
            onChange: function(hsb, hex, rgb) {
                $('#appbundle_vegetable_primaryColor').css('backgroundColor', '#' + hex);
                $('#appbundle_vegetable_primaryColor').val('#' + hex);
            }
        });
    });

    $('#appbundle_vegetable_secondaryColor').on('focus', function () {
        $('#appbundle_vegetable_secondaryColor').css('backgroundColor', $('#appbundle_vegetable_primaryColor').css('backgroundColor'));
        $('#appbundle_vegetable_secondaryColor').ColorPicker({
            onChange: function (hsb, hex, rgb) {
                $('#appbundle_vegetable_secondaryColor').css('border', 'solid #' + hex + ' 4px');
                $('#appbundle_vegetable_secondaryColor').val('#' + hex);
            }
        })
    });
});


