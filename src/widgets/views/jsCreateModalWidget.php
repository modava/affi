<?php if (Yii::$app->request->isAjax): ?>
<script>
    formModal = $('#<?=$formClassName?>');

    formModal.off('beforeSubmit');
    formModal.on('beforeSubmit', function(e){
        e.preventDefault();

        $.ajax({
            type: 'post',
            url: '/backend/affiliate/handle-ajax/save-ajax',
            dataType: 'json',
            data: formModal.serialize() + '&model=<?=$modelName?>'
        }).done(res => {
            $('.ModalContainer').modal('hide');
            $.toast({
                heading: 'Thông báo',
                text: 'Tạo mới thành công',
                position: 'top-right',
                class: 'jq-toast-success',
                hideAfter: 3500,
                stack: 6,
                showHideTransition: 'fade'
            });
        }).fail(f => {
            $('.ModalContainer').modal('hide');
            $.toast({
                heading: 'Thông báo',
                text: 'Tạo mới thất bại',
                position: 'top-right',
                class: 'jq-toast-danger',
                hideAfter: 3500,
                stack: 6,
                showHideTransition: 'fade'
            });
        });

        return false;
    });
</script>
<?php endif ?>