$(document).ready(function () {
    /**Add options **/
    const MaxInputs = 5;
    const content = $('div#fields');
    const AddButton = $('#add-field');
    let x = $('div#fields div').length + 1,
        FieldCount = x - 1;
    $('input#submit').hide();
    AddButton.on('click', function(e) {
        e.preventDefault();
        if (x > 2) {
            $('input#submit').show();
        }
        if (x <= MaxInputs) {
            FieldCount++;
            $('<fieldset><input type="text" name="option[]" id="field_' + FieldCount + '" placeholder="Opción N°' + FieldCount + '" required /><img src="/svg/delete.svg" class="delete" title="Eliminar" /></fieldset>').appendTo(content).hide().fadeIn(400, 'linear');
            x++;
        }
        return false;
    });
    $('body').on('click','.delete', function(e) {
        e.preventDefault();
        if (x > 1) {
            $(this).parent('fieldset').fadeOut(400, 'linear', function() {
                $(this).remove();
            });
            x--;
            FieldCount--;
        }
        if (x === 1) {
            $('input#submit').hide();
        }
        return false;
    });
});
