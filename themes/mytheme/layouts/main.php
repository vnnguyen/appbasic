<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\notifications\widgets\MyNotifications;

AppAsset::register($this);
$session = Yii::$app->session;
if ($session->has('language')) {
    Yii::$app->language = $session->get('language');
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900">
    <?php
    //.modal-body{ height: 135px; padding:0}
        $this->registerCss('
             .modal-dialog{ margin: 5% auto; }
             .modal-content{ border-radius: 10px; }
             .modal-content .div-title{ display: inline-block; width: 100%; margin-bottom: 5px; background: #37474F;  padding: 6px; padding-right: 15px; border-radius: 7px 7px 0 0; color: #fff}
             .modal-content .div-title span{ font-size: 16px; font-weight:500}

             .p-content{ padding-top: 5%; font-size: 15px; }
             .modal-footer .confirm-ok{ padding: 7px 20px;}
             .modal-body .div-content .glyphicon-floppy-remove{ font-size: 32px; position: absolute; left: 15%; top: 44%; }
             .btn-default:hover{background: #ccc;}

        ');
    ?>
    <?php $this->registerJsFile(Yii::$app->request->baseUrl.'/js/modal_confirm.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

        //$this->registerCssFile("http://example.com/css/themes/black-and-white.css", [
    //'depends' => [BootstrapAsset::className()],
    //'media' => 'print',
    //], 'css-print-theme');
    ?>
    <?php
    $this->registerCss('
        .nav-notifications .dropdown-menu { min-width: 350px}
    ');
    ?>
</head>

<body>
<?php $this->beginBody() ?>
    <!-- Main navbar -->
    <div class="navbar navbar-inverse">
        <div class="navbar-header">
            <a class="navbar-brand" href="<?= Yii::$app->homeUrl; ?>"><img src="<?= Yii::$app->request->baseUrl; ?>/images/logo_light.png" alt=""></a>

            <ul class="nav navbar-nav visible-xs-block">
                <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
                <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
            </ul>
        </div>

        <div class="navbar-collapse collapse" id="navbar-mobile">
            <ul class="nav navbar-nav">
                <li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-git-compare"></i>
                        <span class="visible-xs-inline-block position-right">Git updates</span>
                        <span class="badge bg-warning-400">9</span>
                    </a>

                    <div class="dropdown-menu dropdown-content">
                        <div class="dropdown-content-heading">
                            Git updates
                            <ul class="icons-list">
                                <li><a href="#"><i class="icon-sync"></i></a></li>
                            </ul>
                        </div>

                        <ul class="media-list dropdown-content-body width-350">
                            <li class="media">
                                <div class="media-left">
                                    <a href="#" class="btn border-primary text-primary btn-flat btn-rounded btn-icon btn-sm"><i class="icon-git-pull-request"></i></a>
                                </div>

                                <div class="media-body">
                                    Drop the IE <a href="#">specific hacks</a> for temporal inputs
                                    <div class="media-annotation">4 minutes ago</div>
                                </div>
                            </li>

                            <li class="media">
                                <div class="media-left">
                                    <a href="#" class="btn border-warning text-warning btn-flat btn-rounded btn-icon btn-sm"><i class="icon-git-commit"></i></a>
                                </div>

                                <div class="media-body">
                                    Add full font overrides for popovers and tooltips
                                    <div class="media-annotation">36 minutes ago</div>
                                </div>
                            </li>

                            <li class="media">
                                <div class="media-left">
                                    <a href="#" class="btn border-info text-info btn-flat btn-rounded btn-icon btn-sm"><i class="icon-git-branch"></i></a>
                                </div>

                                <div class="media-body">
                                    <a href="#">Chris Arney</a> created a new <span class="text-semibold">Design</span> branch
                                    <div class="media-annotation">2 hours ago</div>
                                </div>
                            </li>

                            <li class="media">
                                <div class="media-left">
                                    <a href="#" class="btn border-success text-success btn-flat btn-rounded btn-icon btn-sm"><i class="icon-git-merge"></i></a>
                                </div>

                                <div class="media-body">
                                    <a href="#">Eugene Kopyov</a> merged <span class="text-semibold">Master</span> and <span class="text-semibold">Dev</span> branches
                                    <div class="media-annotation">Dec 18, 18:36</div>
                                </div>
                            </li>

                            <li class="media">
                                <div class="media-left">
                                    <a href="#" class="btn border-primary text-primary btn-flat btn-rounded btn-icon btn-sm"><i class="icon-git-pull-request"></i></a>
                                </div>

                                <div class="media-body">
                                    Have Carousel ignore keyboard events
                                    <div class="media-annotation">Dec 12, 05:46</div>
                                </div>
                            </li>
                        </ul>

                        <div class="dropdown-content-footer">
                            <a href="#" data-popup="tooltip" title="All activity"><i class="icon-menu display-block"></i></a>
                        </div>
                    </div>
                </li>
            </ul>

            <p class="navbar-text"><span class="label bg-success">Online</span></p>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown language-switch">
                    <a class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= Yii::$app->request->baseUrl; ?>/images/flags/gb.png" class="position-left" alt="">
                        English
                        <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu">
                        <li><?= Html::a('<img src="'.Yii::$app->request->baseUrl.'/images/flags/de.png" alt=""> VietNam',['post/langvi', 'language'=>'vi'],['class' => 'deutsch']);?></li>
                        <li><a class="ukrainian" ><img src="<?= Yii::$app->request->baseUrl; ?>/images/flags/ua.png" alt=""> Українська</a></li>
                        <li><?= Html::a('<img src="'.Yii::$app->request->baseUrl.'/images/flags/gb.png" alt=""> English',['post/langen', 'language'=>'en'],['class' => 'english']);?></li>
                    </ul>
                </li>
                <li class="dropdown"><?= MyNotifications::widget([
                    'options' => ['class' => 'dropdown nav-notifications'],
                    'countOptions' => ['class' => 'badge bg-warning-400']
                ]);?>

                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-bubbles4"></i>
                        <span class="visible-xs-inline-block position-right">Messages</span>
                        <span class="badge bg-warning-400">2</span>
                    </a>

                    <div class="dropdown-menu dropdown-content width-350">
                        <div class="dropdown-content-heading">
                            Messages
                            <ul class="icons-list">
                                <li><a href="#"><i class="icon-compose"></i></a></li>
                            </ul>
                        </div>

                        <ul class="media-list dropdown-content-body">
                            <li class="media">
                                <div class="media-left">
                                    <img src="<?= Yii::$app->request->baseUrl; ?>/images/placeholder.jpg" class="img-circle img-sm" alt="">
                                    <span class="badge bg-danger-400 media-badge">5</span>
                                </div>

                                <div class="media-body">
                                    <a href="#" class="media-heading">
                                        <span class="text-semibold">James Alexander</span>
                                        <span class="media-annotation pull-right">04:58</span>
                                    </a>

                                    <span class="text-muted">who knows, maybe that would be the best thing for me...</span>
                                </div>
                            </li>

                            <li class="media">
                                <div class="media-left">
                                    <img src="<?= Yii::$app->request->baseUrl; ?>/images/placeholder.jpg" class="img-circle img-sm" alt="">
                                    <span class="badge bg-danger-400 media-badge">4</span>
                                </div>

                                <div class="media-body">
                                    <a href="#" class="media-heading">
                                        <span class="text-semibold">Margo Baker</span>
                                        <span class="media-annotation pull-right">12:16</span>
                                    </a>

                                    <span class="text-muted"></span>
                                </div>
                            </li>
                        </ul>

                        <div class="dropdown-content-footer">
                            <a href="#" data-popup="tooltip" title="All messages"><i class="icon-menu display-block"></i></a>
                        </div>
                    </div>
                </li>

                <!-- <li class="dropdown dropdown-user">
                    <a class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= Yii::$app->request->baseUrl; ?>/images/placeholder.jpg" alt="">
                        <span>Victoria</span>
                        <i class="caret"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="#"><i class="icon-user-plus"></i> My profile</a></li>
                        <li><a href="#"><i class="icon-coins"></i> My balance</a></li>
                        <li><a href="#"><span class="badge bg-teal-400 pull-right">58</span> <i class="icon-comment-discussion"></i> Messages</a></li>
                        <li class="divider"></li>
                        <li><a href="#"><i class="icon-cog5"></i> Account settings</a></li>
                        <li><a href="#"><i class="icon-switch2"></i> Logout</a></li>
                    </ul>
                </li> -->
            </ul>
        </div>
    </div>
    <!-- /main navbar -->


    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">

            <!-- Main sidebar -->
            <div class="sidebar sidebar-main">
                <div class="sidebar-content">

                    <!-- User menu -->
                    <div class="sidebar-user">
                        <div class="category-content">
                            <div class="media">
                                <a href="#" class="media-left"><img src="<?= Yii::$app->request->baseUrl; ?>/images/placeholder.jpg" class="img-circle img-sm" alt=""></a>
                                <div class="media-body">
                                    <span class="media-heading text-semibold">Victoria Baker</span>
                                    <div class="text-size-mini text-muted">
                                        <i class="icon-pin text-size-small"></i> &nbsp;Santa Ana, CA
                                    </div>
                                </div>

                                <div class="media-right media-middle">
                                    <ul class="icons-list">
                                        <li>
                                            <a href="#"><i class="icon-cog3"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /user menu -->


                    <!-- Main navigation -->
                    <div class="sidebar-category sidebar-category-visible">
                        <div class="category-content no-padding">
                            <ul class="navigation navigation-main navigation-accordion">

                                <!-- Main -->
                                <li>
                                    <a href="#"><i class="icon-stack2"></i> <span>POST</span></a>
                                    <ul>
                                        <li><a href="<?= Yii::$app->request->baseUrl; ?>/post/index"><?= Yii::t('app', 'All Posts');?></a></li>
                                        <li><a href="<?= Yii::$app->request->baseUrl; ?>/post/create"><?= Yii::t('app', 'Add New Post');?></a></li>
                                        <li><a href="<?= Yii::$app->request->baseUrl; ?>/post/draft"><?= Yii::t('app', 'Draft Post');?></a></li>
                                        <li><a href="<?= Yii::$app->request->baseUrl; ?>/post/off"><?= Yii::t('app', 'Off Post');?></a></li>
                                        <li><a href="<?= Yii::$app->request->baseUrl; ?>/post/recycle"><?= Yii::t('app', 'Recycle bin');?></a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#"><i class="icon-coin-dollar"></i> <span><?= Yii::t('app', 'CASH');?></span></a>
                                    <ul>
                                        <li><a href="<?= Yii::$app->request->baseUrl; ?>/post1/index/0"><?= Yii::t('app', 'Advance Payment');?></a></li>
                                        <li><a href="<?= Yii::$app->request->baseUrl; ?>/post1/index/1"><?= Yii::t('app', 'Pay');?></a></li>
                                        <li><a href="<?= Yii::$app->request->baseUrl; ?>/post1/draft"><?= Yii::t('app', 'Draft Cash');?></a></li>
                                        <li><a href="<?= Yii::$app->request->baseUrl; ?>/post1/recycle"><?= Yii::t('app', 'Recycle bin');?></a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="<?= Yii::$app->request->baseUrl; ?>/priority/index"><i class="icon-exit3"></i> <span><?= Yii::t('app', 'Priority');?></span></a>
                                </li>
                                <li>
                                    <a href="<?= Yii::$app->request->baseUrl; ?>/tour/index"><i class="icon-address-book"></i> <span><?= Yii::t('app', 'Tours');?></span></a>
                                </li>
                                <li>
                                    <a href="<?= Yii::$app->request->baseUrl; ?>/cptour/view/1"><i class="icon-address-book"></i> <span><?= Yii::t('app', 'CP tour (Timeline)');?></span></a>
                                </li>
                                <li>
                                    <a href="<?= Yii::$app->request->baseUrl; ?>/condition"><i class="icon-address-book"></i> <span><?= Yii::t('app', 'Conditions');?></span></a>
                                </li>
                                <li>
                                    <a href="<?= Yii::$app->request->baseUrl; ?>/mailqueue"><i class="icon-address-book"></i> <span><?= Yii::t('app', 'Mail queue');?></span></a>
                                </li>
                                <li>
                                    <a href="<?= Yii::$app->request->baseUrl; ?>/task/list"><i class="icon-stack"></i> <span><?= Yii::t('app', 'Tasks');?></span></a>
                                </li>
                                <li>
                                    <a href="<?= Yii::$app->request->baseUrl; ?>/calen"><i class="icon-stack"></i> <span><?= Yii::t('app', 'Calendar');?></span></a>
                                </li>
                                <li>
                                    <a href="<?= Yii::$app->request->baseUrl; ?>/case/request/28623"><i class="icon-stack"></i> <span><?= Yii::t('app', 'Hồ sơ`');?></span></a>
                                </li>
                                <li>
                                    <a href="<?= Yii::$app->request->baseUrl; ?>/hotel/create"><i class="icon-stack"></i> <span><?= Yii::t('app', 'Book hotel');?></span></a>
                                </li>
                                <li>
                                    <a href="<?= Yii::$app->request->baseUrl; ?>/reserv"><i class="icon-stack"></i> <span><?= Yii::t('app', 'Book position');?></span></a>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <!-- /main navigation -->

                </div>
            </div>
            <!-- /main sidebar -->


            <!-- Main content -->
            <div class="content-wrapper">

                <!-- Page header -->
                <div class="page-header page-header-default">
                    <div class="page-header-content">
                        <div class="page-title">
                            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"><?= Html::encode($this->title); ?></h4>
                        </div>

                        <!-- <div class="heading-elements">
                            <div class="heading-btn-group">
                                <a href="#" class="btn btn-link btn-float has-text"><i class="icon-bars-alt text-primary"></i><span>Statistics</span></a>
                                <a href="#" class="btn btn-link btn-float has-text"><i class="icon-calculator text-primary"></i> <span>Invoices</span></a>
                                <a href="#" class="btn btn-link btn-float has-text"><i class="icon-calendar5 text-primary"></i> <span>Schedule</span></a>
                            </div>
                        </div> -->
                    </div>

                    <div class="breadcrumb-line">
                        <?= Breadcrumbs::widget([
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        ]) ?>
                    </div>
                </div>
                <!-- /page header -->


                <!-- Content area -->
                <div class="col-md-12">
                    <div class="content">

                        <?= $content ?>

                    </div>
                </div>
                <div id="my-confirm" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="div-title  bg-grey-800">
                                    <span><?= Yii::t('app', 'Confirm')?></span>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                </div>

                                <div class="div-content">
                                    <span class="glyphicon glyphicon-floppy-remove"></span>
                                    <p class="text-center p-content"></p>
                                </div>
                            </div>
                            <div class="modal-footer text-center">
                                <button type="button" class="btn btn-primary confirm-ok">OK</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /content area -->

            </div>
            <!-- /main content -->

        </div>
        <!-- /page content -->

    </div>
    <!-- /page container -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
