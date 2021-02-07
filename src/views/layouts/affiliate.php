<?php
\modava\affiliate\assets\AffiliateAsset::register($this);
\modava\affiliate\assets\AffiliateCustomAsset::register($this);
?>
<?php $this->beginContent('@backend/views/layouts/main.php'); ?>
<?php echo $content ?>
<?php $this->endContent(); ?>
