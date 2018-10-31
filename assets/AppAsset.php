<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/style.css',
        'https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900',
        'css/bootstrap.min.css',
        'css/icons/icomoon/styles.css',
        'css/icons/fontawesome/styles.min.css',
        'css/site.css',
        'css/core.min.css',
        'css/components.min.css',
        'css/colors.css',
        'css/jquery-confirm.min.css',
        // 'css/pnotify.custom.min.css'
        'css/simplePagination.css',
        'css/formValidation.min.css'
    ];
    public $js = [
        'assets/45a72819/js/bootstrap.min.js',
        'js/plugins/loaders/pace.min.js',
        'js/plugins/loaders/blockui.min.js',
        'js/plugins/visualization/d3/d3.min.js',
        'js/plugins/visualization/d3/d3_tooltip.js',
        'js/plugins/forms/styling/switchery.min.js',
        'js/plugins/forms/styling/uniform.min.js',
        'js/plugins/forms/selects/bootstrap_multiselect.js',
        'js/plugins/ui/moment/moment.min.js',
         // 'js/plugins/pickers/daterangepicker.js',
        'js/core/app.js',
        // 'js/pages/dashboard.js',
        'js/jquery-confirm.min.js',
        // 'js/pnotify.custom.min.js',
        'js/formValidation.min.js',
        'js/bootstrap.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
