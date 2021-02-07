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

    function copyToClipboard(text) {
        let dummy = document.createElement("input");
        document.body.appendChild(dummy);
        dummy.setAttribute("id", "dummy_id");
        document.getElementById("dummy_id").value = text;
        dummy.select();
        document.execCommand("copy");
        document.body.removeChild(dummy);
        $.toast({
            heading: 'Thông báo',
            text: 'Copy thành công',
            position: 'top-right',
            class: 'jq-toast-success',
            hideAfter: 2000,
            stack: 6,
            showHideTransition: 'fade'
        });
    }

    function getCallLog(elementDOM) {
        let modalHTML = `<div class="modal ModalContainer" tabindex="-1" role="dialog" aria-labelledby="ModalContainer" aria-hidden="true"></div>`;

        if ($('.ModalContainer').length) $('.ModalContainer').remove();

        $('body').append(modalHTML);

        let params = {
            phone: $(elementDOM).data('phone'),
            model: $(elementDOM).data('model'),
        };

        $.get('<?=$controllerURL?>/get-call-log-modal', params, function(data, status, xhr) {
            if (status === 'success') {
                $('.ModalContainer').html(data);
                $('.ModalContainer').modal();

                // Pause sound when hide modal
                $('.ModalContainer').off('hide.bs.modal');
                $('.ModalContainer').on('hide.bs.modal', function () {
                    $('.call-log-audio').each(function (index, item) {
                        item.pause();
                    });
                });
            }
        });
    }

    window.onload = function () {
        $('body').on('click', '.copy', function () {
            copyToClipboard($(this).data('copy'));
        });
        $('body').on('click', '.show-call-log', function () {
            getCallLog($(this));
        });
    }

</script>