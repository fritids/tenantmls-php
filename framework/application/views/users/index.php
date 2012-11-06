<?php
        $page_title = ($logged_in) ? $tmls_user['User']['first_name'].' '.$tmls_user['User']['middle_name'].' '.$tmls_user['User']['last_name'] : 'Sign up and find your local agents and rentals';
        $page_sidebar_link = array(
            'Dashboard'=>array(BASE_PATH.'/dashboard','dashboard',false),
            'Profile'=>array(BASE_PATH.'/profile','profile',false),
            'Search'=>array(BASE_PATH.'/search','search',false),
            'Manage'=>array(BASE_PATH.'/manage','manage',false),
            'Messages'=>array(BASE_PATH.'/messages','messages',false),
            'Documents'=>array(BASE_PATH.'/documents','documents',false),
            'Settings'=>array(BASE_PATH.'/settings','settings',false),
        );
        // select the appropriate sidebar_link
        $page_sidebar_link[ucfirst(strtolower($page_tab))] = array(
            $page_sidebar_link[ucfirst(strtolower($page_tab))][0],
            $page_sidebar_link[ucfirst(strtolower($page_tab))][1],
            true
        );
?>
<?php if (!isset($inframe) || $inframe==0) : ?>
<div class="tmls-ui-container">
    <div id="tmls-ui-profile-header">
        <div class="tmls-ui-body-highlight" id="highlight-profile">
            <?php
            if ($tmls_user['User']['user_type']=='Tenant' && count($tmls_user['Agents'])==0) {
                echo '<a class="highlight-button e" href="'.BASE_PATH.'/tmls/1000000000">Find Agents</a>';
            } else {
                echo '<a class="highlight-button e" href="'.BASE_PATH.'/tmls/1000000011">Find Tenants</a>';
            }
            ?>
            <span id="header-icon"></span>
            <h3><?php echo $page_title; ?></h3>
        </div>
    </div>
    <div id="tmls-ui-profile-header-spacer"></div>
    <div id="tmls-ui-body">
        <?php
        /*
        <div id="ui-breadcrumbs">
            <ul>
                <li><span id="home"></span></li>
                <?php
                    echo ($page_tab) ? '<li>'.ucfirst($page_tab).'</li>' : '';
                ?>
            </ul>
            <div class="clearfix-left"></div>
        </div>
        */
        ?>
        <div id="tmls-application-ui-sidebar">
                <ul>
                <?php
                    foreach($page_sidebar_link as $value=>$params) {
                        $href = $params[0];
                        $id = $params[1];
                        $class = ($params[2]) ? ' class="active a"' : ' class="a"';
                        echo '<li'.$class.'><span class="sidebar_icon" id="'.$id.'"></span><a href="'.$href.'">'.$value.'</a></li>';
                    }
                ?>
                </ul>
            </div>
        <div id="content">
    <?php endif;
    /*
     *  Load the content here
     */
    switch(strtolower($page_tab)) {
        default : performAction('users',strtolower($page_tab),array($pm),1,1); break;
        case 'search' : performAction('locales',strtolower($page_tab),array($pm),1,1); break;
    }
    if (!isset($inframe) || $inframe==0) :
    ?>
        </div>
    </div>
    <?php endif; ?>