<?php

namespace modava\affiliate;

use yii\base\BootstrapInterface;
use Yii;
use yii\base\Event;
use \yii\base\Module;
use yii\web\Application;
use yii\web\Controller;

/**
 * affiliate module definition class
 */
class AffiliateModule extends Module implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'modava\affiliate\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        // custom initialization code goes here
//        $this->registerTranslations();
        parent::init();
        Yii::configure($this, require(__DIR__ . '/config/affiliate.php'));
//        $handler = $this->get('errorHandler');
//        Yii::$app->set('errorHandler', $handler);
//        $handler->register();
        $this->layout = 'affiliate';
    }



    public function bootstrap($app)
    {
        $app->on(Application::EVENT_BEFORE_ACTION, function () {

        });
        Event::on(Controller::class, Controller::EVENT_BEFORE_ACTION, function (Event $event) {
            $controller = $event->sender;
        });
    }

    /*public function registerTranslations()
    {
        Yii::$app->i18n->translations['affiliate/messages/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@modava/affiliate/messages',
            'fileMap' => [
                'affiliate/messages/affiliate' => 'affiliate.php',
            ],
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('backend/messages/' . $category, $message, $params, $language);
    }*/
}
