<?php

namespace modava\affiliate\widgets;

class DropdownWidget extends \yii\base\Widget
{
    public $title = 'Button label';
    public $dropdowns = [];
    public $options = [];
    public $isCustomItem = false;

    public function init()
    {
        if (!array_key_exists('class', $this->options)) {
            $this->options['class'] = 'btn-secondary btn-sm';
        }

        parent::init();
    }

    public function run()
    {
        $isCustomItem = $this->isCustomItem;
        $dropdown = array_reduce($this->dropdowns, function ($carry, $item) use ($isCustomItem) {
            return $carry . ($isCustomItem ? $item : '<a class="dropdown-item" href="#">' . $item . '</a>');
        });

        return '
            <div class="btn-group my-1" role="group">
              <button type="button" class="btn ' . $this->options['class'] . ' dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . $this->title . '</button>
              <div class="dropdown-menu">' . $dropdown . '</div>
            </div>
        ';
    }
}