<?php
use yii\helpers\Url;

$controllerURL = Url::toRoute(["/affiliate/handle-ajax"]);

?>
<script>
    function changeButtonSave(modalConatiner) {
        let buttonSubmit = modalConatiner.find('button[type="submit"]').clone();
        modalConatiner.find('button[type="submit"]').remove();
        buttonSubmit.on('click', function () {
            modalConatiner.find('form').submit();
        });
        modalConatiner.find('.modal-footer').prepend(buttonSubmit);
    }

    function openCreateModal(params) {
        let modalHTML = `<div class="modal ModalContainer" tabindex="-1" role="dialog" aria-labelledby="ModalContainer" aria-hidden="true"></div>`;

        if ($('.ModalContainer').length) $('.ModalContainer').remove();

        $('body').append(modalHTML);

        $.get('<?=$controllerURL?>/get-create-modal', params, function(data, status, xhr) {
            if (status === 'success') {
                if (typeof tinymce != "undefined") tinymce.remove();
                $('.ModalContainer').html(data);
                $('.ModalContainer').modal();

                changeButtonSave($('.ModalContainer'));
            }
        });
    }

    function getListRelatedRecords(elementDOM) {
        let modalHTML = `<div class="modal ModalContainer" tabindex="-1" role="dialog" aria-labelledby="ModalContainer" aria-hidden="true"></div>`;

        if ($('.ModalContainer').length) $('.ModalContainer').remove();

        $('body').append(modalHTML);

        let model = $(elementDOM).data('model');

        let params = {
            model: model,
            related_field: $(elementDOM).data('related-field'),
            related_id:  $(elementDOM).data('related-id'),
        };
        params[model + 'Search[' + $(elementDOM).data('related-field') + ']'] = $(elementDOM).data('related-id');

        $.get('<?=$controllerURL?>/get-list-related-records-modal', params, function(data, status, xhr) {
            if (status === 'success') {
                $('.ModalContainer').html(data);
                $('.ModalContainer').modal();
            }
        });
    }

    function saveStateSearchPanel(searchPanel, button, key) {
        if (!window.localStorage.getItem(key)) {
            window.localStorage.setItem(key, 'show');
        }

        if (window.localStorage.getItem(key) === 'show') $(searchPanel).addClass('show');
        else $(searchPanel).removeClass('show');

        $(button).on('click', function() {
            if (window.localStorage.getItem(key) === 'show') {
                window.localStorage.setItem(key, 'hide');
            }
            else {
                window.localStorage.setItem(key, 'show');
            }
        });
    }
</script>