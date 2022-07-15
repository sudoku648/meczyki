function checkInputs(inputSelector, buttonsSelectors) {
    var inputs = $(inputSelector).length;

    if (inputs > 0) {
        setButtonsEnabled(true, buttonsSelectors);
        return;
    }

    setButtonsEnabled(false, buttonsSelectors);
}

function setButtonsEnabled(buttonsEnabled, buttonsSelectors) {
    if (buttonsEnabled) {
        $(buttonsSelectors).each(function (index, element) {
            $(element).removeAttr('disabled').parent('form').removeClass('cursor-not-allowed');
        });
        return;
    }

    $(buttonsSelectors).each(function (index, element) {
        $(element).attr('disabled', 'disabled').parent('form').addClass('cursor-not-allowed');
    });
}

function setMainCheckbox(checkSingleSelector, checkAllSelector) {
    var inputs = $(checkSingleSelector).filter(':checked').length;
    var total = $(checkSingleSelector).length;

    if (inputs > 0 && inputs < total) {
        $(checkAllSelector).prop({'checked': false, 'indeterminate': true});
        return;
    }
    if (inputs > 0) {
        $(checkAllSelector).prop({'checked': true, 'indeterminate': false});
        return;
    }

    $(checkAllSelector).prop({'checked': false, 'indeterminate': false});
}

function addOrRemoveInputFromForms(checkbox, dataAttribute, inputSelector, inputName, formSelectors) {
    var objectId = checkbox.attr(dataAttribute);
    var isChecked = checkbox.is(':checked');

    if (isChecked && !inputExist(inputSelector, objectId)) {
        var input = getInput(inputName, objectId);
        $(formSelectors).each(function () {
            $(this).append(input).trigger('change');
        });
        return;
    }
    if (isChecked) {
        return;
    }

    removeInput(inputSelector, formSelectors, objectId);
}

function getInput(inputName, objectId) {
    return '<input name="'+inputName+'" type="hidden" value="'+objectId+'">';
}

function inputExist(inputSelector, objectId) {
    return $(inputSelector+'[value=\''+objectId+'\']').length > 0;
}

function removeInput(inputSelector, formSelectors, objectId) {
    $(inputSelector+'[value=\''+objectId+'\']').remove();
    $(formSelectors).each(function () {
        $(this).trigger('change');
    });
}

export {checkInputs, setMainCheckbox, addOrRemoveInputFromForms, removeInput};
