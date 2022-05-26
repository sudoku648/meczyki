$(function() {
    showOrHideFirstAssistantCheckbox();
    showOrHideSecondAssistantCheckbox();

    $('select[id=\'match_game_firstAssistantReferee\']').on('change', function() {
        showOrHideFirstAssistantCheckbox();
    });

    $('select[id=\'match_game_secondAssistantReferee\']').on('change', function() {
        showOrHideSecondAssistantCheckbox();
    });
});

function showOrHideFirstAssistantCheckbox() {
    if ($('select[id=\'match_game_firstAssistantReferee\']').find(":selected").val() == '') {
        $('input[id=\'match_game_isFirstAssistantNonProfitable\']').closest('.mb-3').hide();
    } else {
        $('input[id=\'match_game_isFirstAssistantNonProfitable\']').closest('.mb-3').show();
    }
}
function showOrHideSecondAssistantCheckbox() {
    if ($('select[id=\'match_game_secondAssistantReferee\']').find(":selected").val() == '') {
        $('input[id=\'match_game_isSecondAssistantNonProfitable\']').closest('.mb-3').hide();
    } else {
        $('input[id=\'match_game_isSecondAssistantNonProfitable\']').closest('.mb-3').show();
    }
}
