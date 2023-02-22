<h2>Tabs with a single group of sub tabs (under tab 1):</h2>
<div class="tab">
  <div class="tab__header">
    <div class="tab__header-1">My Dashboard</div>
    <div class="tab__header-2">My Community</div>
  </div>
  <div class="tab__content">
    <div class="tab__content-1">



    <?
$user = getUser($_COOKIE['userid'], $w);
$select = "SELECT * FROM `subscription_types` WHERE `subscription_id` = $user[subscription_id]";
$sql = mysql(brilliantDirectories::getDatabaseConfiguration('database'), $select);
$membership = mysql_fetch_assoc($sql);


if ($membership['member_dashboard'] == 2) {
    //init all the variables 
    $steps_complete = 5;
    $steps_skiped = true;
    $total_steps = 5;
} else {
    if ($membership['member_dashboard'] == 1) {
        //init all the variables 
        $steps_complete = 0;
        $steps_skiped = 0;
        $count_steps_skiped = 0;
        $step1 = 0;
        $step2 = 0;
        $step3 = 0;
        $step4 = 0;
        $step5 = 0;

        //GET ALL SETTINGS TO SHOW 
        $select = "SELECT * FROM `users_meta` WHERE `database` = 'subscription_types' AND `database_id` = $user[subscription_id] AND (`key` = 'show_contact_detail' 
        OR `key` = 'show_about_tab' OR `key` = 'show_profile_photo' OR `key` = 'show_listing_details')";
        $sql = mysql(brilliantDirectories::getDatabaseConfiguration('database'), $select);
        $plugin_settings = array();
        while ($data = mysql_fetch_assoc($sql)) {
            $plugin_settings[$data['key']] = array("value" => $data['value']);
        }
        //GET STEPS
        $select = "SELECT * FROM `profile_complete_step`";
        $sql = mysql(brilliantDirectories::getDatabaseConfiguration('database'), $select);
        while ($data = mysql_fetch_assoc($sql)) {
            $steps[] = $data;
        }

        //VERIFY IF ALL BASIC INFO ARE COMPLETED
        if (
            ($user['first_name'] != " " && $user['first_name'] != null) &&
            ($user['last_name'] != " " && $user['last_name'] != null) &&
            ($user['email'] != " " && $user['email'] != null) &&
            ($user['company'] != " " && $user['company'] != null) &&
            ($user['phone_number'] != " " && $user['phone_number'] != null) &&
            ($user['listing_type'] != " " && $user['listing_type'] != null) &&
            ($user['profession_id'] != " " && $user['profession_id'] != null) &&
            ($user['skill_level'] != " " && $user['skill_level'] != null) &&
            ($user['interests'] != " " && $user['interests'] != null) &&
            ($user['city'] != " " && $user['city'] != null) &&
            ($user['country_ln'] != " " && $user['country_ln'] != null) &&
            ($user['state_ln'] != " " && $user['state_ln'] != " ") &&
            ($user['search_description'] != " " && $user['search_description'] != null)
        ) {
            $step1 = 1;
            $steps_complete += 1;
        }

        //Verify photo info
        $select = "SELECT * FROM `users_photo` WHERE `user_id` = $user[user_id]";
        $sql = mysql(brilliantDirectories::getDatabaseConfiguration('database'), $select);
        $user_photo = mysql_fetch_assoc($sql);
        if ($user_photo['file'] != " " && $user_photo != NULL) {
            $step2 = 1;
            $steps_complete += 1;
        }
        //verify if had services
        $select = "SELECT * FROM `rel_services` WHERE `user_id` = $user[user_id]";
        $sql = mysql(brilliantDirectories::getDatabaseConfiguration('database'), $select);
        while ($data = mysql_fetch_assoc($sql)) {
            $count_services[] = $data;
        }
        if (count($count_services) != 0) {
            $step3 = 1;
            $steps_complete += 1;
        }

        //verify extra profile details
        if ($user['about_me'] != " " && $user['about_me'] != null) {
            $step4 = 1;
            $steps_complete += 1;
        }

        //verify location
        $select = "SELECT * FROM `service_areas` WHERE `user_id` = $user[user_id]";
        $sql = mysql(brilliantDirectories::getDatabaseConfiguration('database'), $select);
        while ($data = mysql_fetch_assoc($sql)) {
            $count_location[] = $data;
        }
        //echo $count_location;
        if (count($count_location) != 0) {
            $step5 = 1;
            $steps_complete += 1;
        }



        //verify if all steps are skiped
        if ($user['step_skip1'] == 1 && $user['step_skip2'] == 1 && $user['step_skip3'] == 1 && $user['step_skip4'] == 1 && $user['step_skip5'] == 1) {
            $steps_skiped = 1;
        }
        if ($user['step_skip1'] == 1) {
            $count_steps_skiped += 1;
        }
        if ($user['step_skip2'] == 1) {
            $count_steps_skiped += 1;
        }
        if ($user['step_skip3'] == 1) {
            $count_steps_skiped += 1;
        }
        if ($user['step_skip4'] == 1) {
            $count_steps_skiped += 1;
        }
        if ($user['step_skip5'] == 1) {
            $count_steps_skiped += 1;
        }

        $total_steps = $count_steps_skiped + $steps_complete;
    }
}

//END
?>

<h1 class="h2 bold dashboard_home_title">
    %%%account_dashboard_icon%%% %%%dashboard_home_title%%%
    <?php
    echo widget("Bootstrap Theme - Account - H1 Action Links", "", $w['website_id'], $w); ?>
</h1>
<hr class="hr-account-member-dashboard">
<?
//Start 
if (($steps_complete != 5) && ($steps_skiped != true) && ($total_steps != 5)) {
    //HEADER
    if ($subscription['user_dashboard_header_widget'] != "") {
        if ($subscription['user_dashboard_header_widget'] == 'dashboard_header_custom') {
            echo $subscription['membership_dashboard_header_content'];
        } else {
            echo widget($subscription['user_dashboard_header_widget'], "", $w['website_id'], $w);
        }
    }

?>
    <div class="col-md-12 nopad" style="margin: 15px 0;">
        <h2 class="nomargin sm-text-center streaming-title" style="color: <? echo $wa['custom_58'] ?>; font-size: 30px;">
            Welcome to <?php echo $w['website_name']; ?>
        </h2>
        <h2 style="font-size: 17px; margin-top:15px">
            Begin your journey by properly completing your profile
        </h2>
    </div>

    <!--Table for steps--->
    <div class="col-md-12 nopad">
        <table id="stepsDatatable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th class="table_header">Step</th>
                    <th class="table_header">Description</th>
                    <th class="table_header">Status</th>
                    <th class="table_header">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?
                $count = 1;
                foreach ($steps as $step) { ?>
                    <? //SELECT * FROM `users_meta` WHERE `database` = 'subscription_types' AND `database` = $user[subscription_id] AND `key` = 'show_contact_detail' 
                    // AND `key` = 'show_about_tab' AND `key` = 'show_profile_photo' AND `key` = 'show_listing_details'
                    if (($plugin_settings['show_contact_detail']['value'] != 0 && $step['id_step'] == 1)
                        || ($plugin_settings['show_profile_photo']['value'] != 0 && $step['id_step'] == 2)
                        || ($plugin_settings['show_listing_details']['value'] != 0 && $step['id_step'] == 3)
                        || ($plugin_settings['show_about_tab']['value'] != 0 && $step['id_step'] == 4)
                        || ($membership['location_limit'] == 1 && $step['id_step'] == 5)
                    ) { ?>
                        <tr class="STEP-content">
                            <td class="bodyTable"><? echo $count; ?></td>
                            <td class="bodyTable"><? echo $step['description']; ?></td>
                            <?
                            //Step 1 
                            if ($step['id_step'] == 1) {
                                if ($step1 == 1 || $user['step_skip1'] == 1) { ?>
                                    <!--Complete-->
                                    <td class="bodyTable">Information Complete</td>
                                    <td>
                                        <div class="col-md-12 nolpad">
                                            <div class="col-md-8 col-sm-12 mynopad">
                                                <button id="GetStartedStep1" href="<? $step['link'] ?>" type="button" class="btn  btn-block disableButtons" disabled data-dismiss="modal">Get Started</button>
                                            </div>
                                            <div class="col-md-4 col-sm-12 nopad">
                                                <button id="SkipStep1" id_users="<? echo $user['user_id'] ?>" type="button" class="btn  btn-block disableButtons" disabled data-dismiss="modal">Skip</button>
                                            </div>
                                        </div>
                                    </td>

                                <? } else { ?>
                                    <!--Pending-->
                                    <td class="bodyTableIncomplete">Information Pending</td>
                                    <td>
                                        <div class="col-md-12 nolpad">
                                            <div class="col-md-8 col-sm-12 mynopad">
                                                <a href="<? echo $step['link'] ?>" id="GetStartedStep1" type="button" class="btn btn-block ableButton" data-dismiss="modal">Get Started</a>
                                            </div>
                                            <div class="col-md-4 nopad col-sm-12">
                                                <a id="SkipStep1" id_users="<? echo $user['user_id'] ?>" type="button" class="btn btn-block ableButton" data-dismiss="modal">Skip</a>
                                            </div>
                                        </div>
                                    </td>
                                <? } ?>
                            <? } ?>

                            <?
                            //Step 2 
                            if ($step['id_step'] == 2) {
                                if ($step2 == 1 || $user['step_skip2'] == 1) { ?>
                                    <!--Complete-->
                                    <td class="bodyTable">Information Complete</td>
                                    <td>
                                        <div class="col-md-12 nolpad">
                                            <div class="col-md-8 col-sm-12 mynopad">
                                                <button id="GetStartedStep2" href="echo <? $step['link'] ?>" type="button" class="btn btn-block disableButtons" disabled data-dismiss="modal">Get Started</button>
                                            </div>
                                            <div class="col-md-4 nopad col-sm-12">
                                                <button id="SkipStep2" id_users="<? $user['user_id'] ?>" type="button" class="btn btn-block disableButtons" disabled data-dismiss="modal">Skip</button>
                                            </div>
                                        </div>
                                    </td>

                                <? } else { ?>
                                    <!--Pending-->
                                    <td class="bodyTableIncomplete">Information Pending</td>
                                    <td>
                                        <div class="col-md-12 nolpad">
                                            <div class="col-md-8 col-sm-12 mynopad">
                                                <a href="<? echo $step['link'] ?>" id="GetStartedStep2" type="button" class="btn btn-block ableButton" data-dismiss="modal">Get Started</a>
                                            </div>
                                            <div class="col-md-4 nopad col-sm-12">
                                                <a id="SkipStep2" id_users="<? echo $user['user_id'] ?>" type="button" class="btn btn-block ableButton" data-dismiss="modal">Skip</a>
                                            </div>
                                        </div>
                                    </td>
                                <? } ?>
                            <? } ?>

                            <?
                            //Step 3 
                            if ($step['id_step'] == 3) {
                                if ($step3 == 1 || $user['step_skip3'] == 1) { ?>
                                    <!--Complete-->
                                    <td class="bodyTable">Information Complete</td>
                                    <td>
                                        <div class="col-md-12 nolpad">
                                            <div class="col-md-8 col-sm-12 mynopad">
                                                <button id="GetStartedStep3" type="button" class="btn btn-block disableButtons" disabled data-dismiss="modal">Get Started</button>
                                            </div>
                                            <div class="col-md-4 nopad col-sm-12">
                                                <button id="SkipStep3" id_users="<? echo $user['user_id'] ?>" type="button" class="btn btn-block disableButtons" disabled data-dismiss="modal">Skip</button>
                                            </div>
                                        </div>
                                    </td>

                                <? } else { ?>
                                    <!--Pending-->
                                    <td class="bodyTableIncomplete">Information Pending</td>
                                    <td>
                                        <div class="col-md-12 nolpad">
                                            <div class="col-md-8 col-sm-12 mynopad">
                                                <a href="<? echo $step['link'] ?>" id="GetStartedStep3" type="button" class="btn btn-block ableButton" data-dismiss="modal">Get Started</a>
                                            </div>
                                            <div class="col-md-4 nopad col-sm-12">
                                                <a id="SkipStep3" id_users="<? echo $user['user_id'] ?>" type="button" class="btn btn-block ableButton" data-dismiss="modal">Skip</a>
                                            </div>
                                        </div>
                                    </td>
                                <? } ?>
                            <? } ?>

                            <?
                            //Step 4 
                            if ($step['id_step'] == 4) {
                                if ($step4 == 1 || $user['step_skip4'] == 1) { ?>
                                    <!--Complete-->
                                    <td class="bodyTable">Information Complete</td>
                                    <td>
                                        <div class="col-md-12 nolpad">
                                            <div class="col-md-8 col-sm-12 mynopad">
                                                <button id="GetStartedStep4" type="button" class="btn btn-block disableButtons" disabled data-dismiss="modal">Get Started</button>
                                            </div>
                                            <div class="col-md-4 nopad col-sm-12">
                                                <button id="SkipStep4" id_users="<? $user['user_id'] ?>" type="button" class="btn btn-block disableButtons" disabled data-dismiss="modal">Skip</button>
                                            </div>
                                        </div>
                                    </td>

                                <? } else { ?>
                                    <!--Pending-->
                                    <td class="bodyTableIncomplete">Information Pending</td>
                                    <td>
                                        <div class="col-md-12 nolpad">
                                            <div class="col-md-8 col-sm-12 mynopad">
                                                <a href="<? echo $step['link'] ?>" id="GetStartedStep4" type="button" class="btn btn-block ableButton" data-dismiss="modal">Get Started</a>
                                            </div>
                                            <div class="col-md-4 nopad col-sm-12">
                                                <a id="SkipStep4" id_users="<? echo $user['user_id'] ?>" type="button" class="btn btn-block ableButton" data-dismiss="modal">Skip</a>
                                            </div>
                                        </div>
                                    </td>
                                <? } ?>
                            <? } ?>
                            <?
                            //Step 5 
                            if ($step['id_step'] == 5) {
                                if ($step5 == 1 || $user['step_skip5'] == 1) { ?>
                                    <!--Complete-->
                                    <td class="bodyTable">Information Complete</td>
                                    <td>
                                        <div class="col-md-12 nolpad">
                                            <div class="col-md-8 col-sm-12 mynopad">
                                                <button id="GetStartedStep5" type="button" class="btn btn-block disableButtons" disabled data-dismiss="modal">Get Started</button>
                                            </div>
                                            <div class="col-md-4 nopad col-sm-12">
                                                <button id="SkipStep5" id_users="<? $user['user_id'] ?>" type="button" class="btn btn-block disableButtons" disabled data-dismiss="modal">Skip</button>
                                            </div>
                                        </div>
                                    </td>

                                <? } else { ?>
                                    <!--Pending-->
                                    <td class="bodyTableIncomplete">Information Pending</td>
                                    <td>
                                        <div class="col-md-12 nolpad">
                                            <div class="col-md-8 col-sm-12 mynopad">
                                                <a href="<? echo $step['link'] ?>" id="GetStartedStep5" type="button" class="btn btn-block ableButton" data-dismiss="modal">Get Started</a>
                                            </div>
                                            <div class="col-md-4 nopad col-sm-12">
                                                <a id="SkipStep5" id_users="<? echo $user['user_id'] ?>" type="button" class="btn btn-block ableButton" data-dismiss="modal">Skip</a>
                                            </div>
                                        </div>
                                    </td>
                                <? } ?>
                            <? } ?>
                        </tr>
                <? $count++;
                    }
                }
                ?>

            </tbody>
        </table>
    </div>


    <?php
    if ($user_data['filename'] != '' && $subscription['searchable'] == "1" && $subscription['show_dashboard_member_badge'] != "0") { ?>
        <div class="row">
            <div class="col-md-12">
                <div class="well">
                    <h2 class="text-center">
                        %%%dashboard_promote_titleone%%%
                        <br>
                        %%%dashboard_promote_titletwo%%%
                    </h2>
                    <p class="text-center">
                        %%%dashboard_promote_subtitleone%%%
                        <br>
                        <?php
                        if ($subscription['dashboard_member_badge'] != "" && addonController::isAddonActive('member_listing_badges')) {
                            $imageSource = brilliantDirectories::getWebsiteUrl() . $subscription['dashboard_member_badge'];
                        } else if ($w['facebook_secret'] != "") {
                            $imageSource = brilliantDirectories::getWebsiteUrl() . $w['facebook_secret'];
                        } else {
                            $imageSource = brilliantDirectories::getWebsiteUrl() . '/images/memberbadge.png';
                        } ?>

                        <img class="center-block" src="<?php echo $imageSource; ?>" />
                    </p>
                    <p class="text-center">%%%dashboard_promote_subtitletwo%%%</p>
                    <form name=myform2>
                        <div class="form-group">
                            <textarea class="form-control" name="name" tabindex=1 onClick="selectText('markupArea');" id="markupArea"><a href="<?php echo brilliantDirectories::getWebsiteUrl() . "/" . $user_data['filename'] ?>?from=badge"  title="%%%member_badge_find_me_on%%% <?php echo $w['website_name']; ?>" target="_blank"><img src="<?php echo $imageSource; ?>" border=0/></a>
                        </textarea>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php
    if ($subscription['user_dashboard_footer_widget'] != "") {
        if ($subscription['user_dashboard_footer_widget'] == 'dashboard_footer_custom') {
            echo $subscription['membership_dashboard_footer_content'];
        } else {
            echo widget($subscription['user_dashboard_footer_widget'], "", $w['website_id'], $w);
        }
    }
    ?>

<? } else { ?>
    <!--Profile Code from BD-->
    <?php
    if ($subscription['user_dashboard_header_widget'] != "") {
        if ($subscription['user_dashboard_header_widget'] == 'dashboard_header_custom') {
            echo $subscription['membership_dashboard_header_content'];
        } else {
            echo widget($subscription['user_dashboard_header_widget'], "", $w['website_id'], $w);
        }
    }

    if (!isset($subscription['subscription_id']) || $subscription['subscription_id'] <= 0) {
        $subscription = getSubscription($user['subscription_id'], $w);
    }

    $percent = 0;
    $enabledOptions = 0;
    $completedOptions = 0;
    $phresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
        *
    FROM
        `users_photo`
    WHERE
        `user_id`='$user[user_id]'
    AND
        `type`='logo'
    LIMIT 1");
    $photoLogoUsed = mysql_num_rows($phresults);
    $phresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
        *
    FROM
        `users_photo`
    WHERE
        `user_id`='$user[user_id]'
    AND
        `type`='photo'
    LIMIT 1");
    $photoProfileUsed = mysql_num_rows($phresults);
    $photoTabText = "%%%account_listing_tab_2%%%";

    if ($w['enable_new_search_engine'] == 1) {
        $phresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
            *
        FROM
            `service_areas`
        WHERE
            `user_id`='$user[user_id]'");
    } else {
        $phresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
            *
        FROM
            `users_locations`
        WHERE
            `user_id`='$user[user_id]'");
    }
    $user['pictures'] = mysql_num_rows($phresults);
    $user['styles'] = relTotals('memberstyles', $user['user_id'], $w);
    $user['services'] = relTotals('services', $user['user_id'], $w);
    $pt = 3;

    if (is_null($subscription['show_contact_detail'])) {
        $subscription['show_contact_detail'] = 1;
        $subscription['show_logo_upload'] = 1;
        $subscription['show_profile_photo'] = 1;
        $subscription['show_listing_details'] = 1;
        $subscription['show_about_tab'] = 1;
        $subscription['location_limit'] = 0;
    }

    if ($subscription['show_about_tab'] == 1) {
        $pt++;
    }
    $multiListing = getAddOnInfo('multi_location', '6c84d51c9fe026cacd4e055bb594fb69');
    if ($subscription['location_limit'] != 0 && $subscription['location_limit'] != '' && $w['account_locations_tab'] == 1 && isset($multiListing['status']) && $multiListing['status'] === 'success') {
        $pt++;
    } else {
        $subscription['location_limit'] = 0;
    }
    $increment = floor(100 / $pt);

    if ($subscription['show_logo_upload'] == 1 || $subscription['show_profile_photo'] == 1) {
        $photoTabEnabled = 1;
    } else {
        $photoTabEnabled = 0;
    }

    if ($subscription['show_logo_upload'] == 1 && $subscription['show_profile_photo'] == 1) {
        $photoName = 1;
    } else {
        $photoName = 0;
    }


    $enabledOptions++;
    $completed = 'warning';

    if (form::isFormComplete($subscription['contact_details_form'], $user['user_id'])) {
        $setting['complete_contact'] = 1;
        $percent += $increment;
        $completedOptions++;
        $completed = 'success';
    }

    $contactDetailsTab = "<tr><td><a class='btn btn-block btn-" . $completed . " bold' href='/account/contact'>%%%account_listing_tab_1%%%</a></td></tr>";



    if ($photoTabEnabled == 1) {
        $enabledOptions++;
        $completed = 'warning';

        if ($subscription['show_profile_photo'] == 0 && $subscription['show_logo_upload'] == 1) {
            if ($photoLogoUsed > 0) {
                $setting['complete_photo'] = 0;
                $percent += $increment;
                $completedOptions++;
                $completed = 'success';
            } else {
                $setting['complete_photo'] = 0;
            }
        } else if ($subscription['show_profile_photo'] == 1 && $subscription['show_logo_upload'] == 0) {

            if ($photoProfileUsed > 0) {
                $setting['complete_photo'] = 0;
                $percent += $increment;
                $completedOptions++;
                $completed = 'success';
            } else {
                $setting['complete_photo'] = 0;
            }
        } else if ($subscription['show_profile_photo'] == 1 && $subscription['show_logo_upload'] == 1) {

            if ($photoProfileUsed > 0 || $photoLogoUsed > 0) {
                $setting['complete_photo'] = 0;
                $percent += $increment;
                $completedOptions++;
                $completed = 'success';
            } else {
                $setting['complete_photo'] = 0;
            }
        }
        $profilePhotoTab = '<tr><td><a class="btn btn-block btn-' . $completed . ' bold" href="/account/profile">' . $photoTabText . '</a></td></tr>';
    } else {
        $setting['complete_photo'] = 0;
        $profilePhotoTab = '';
    }

    if ($subscription['show_listing_details'] == 1) {
        $enabledOptions++;
        $completed = 'warning';

        if (form::isFormComplete($subscription['listing_details_form'], $user['user_id'])) {
            $setting['complete_resume'] = 1;
            $percent += $increment;
            $completedOptions++;
            $completed = 'success';
        }

        $listingDetailsTab = "<tr><td><a class='btn btn-block btn-" . $completed . " bold' href='/account/resume'>%%%account_listing_tab_3%%%</a></td></tr>";
    } else {
        $setting['complete_resume'] = 1;
        $listingDetailsTab = '';
    }

    if ($subscription['show_about_tab'] == 1) {
        $enabledOptions++;
        $completed = 'warning';

        if (form::isFormComplete($subscription['about_form'], $user['user_id'])) {
            $setting['complete_about'] = 1;
            $percent += $increment;
            $completedOptions++;
            $completed = 'success';
        }
        $aboutTab = "<tr><td><a class='btn btn-block btn-" . $completed . " bold' href='/account/about'>%%%account_listing_tab_4%%%</a></td></tr>";
    } else {
        $setting['complete_about'] = 0;
        $aboutTab = '';
    }

    if ($subscription['location_limit'] != 0 && $subscription['location_limit'] != '') {
        $enabledOptions++;
        $completed = 'warning';

        if ($user['pictures'] > 0) {
            $setting['complete_portfolio'] = 1;
            $percent += $increment;
            $completedOptions++;
            $completed = 'success';
        }
        $serviceAreaTab = "<tr><td><a class='btn btn-block btn-" . $completed . " bold' href='/account/locations'>%%%account_listing_tab_5%%%</a></td></tr>";
    } else {
        $setting['complete_portfolio'] = 0;
        $serviceAreaTab = '';
        $subscription['location_limit'] = 0;
    }

    if ($percent > 95) {
        $percent = 100;
    }

    mysql(brilliantDirectories::getDatabaseConfiguration('database'), "UPDATE users_setting SET
    complete_contact = '$setting[complete_contact]',
    complete_resume = '$setting[complete_resume]',
    complete_photo = '$setting[complete_photo]',
    complete_about = '$setting[complete_about]',
    complete_portfolio = '$setting[complete_portfolio]'
    WHERE user_id = '$user[user_id]'");

    if ($percent == 100) {
        $sresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
            *
        FROM
            users_setting
        WHERE
            show_complete = '0'
        AND
            user_id = '$user[user_id]'");

        if (mysql_num_rows($sresults) > 0) {
            $w['first_name'] = $user['first_name'];
            mysql(brilliantDirectories::getDatabaseConfiguration('database'), "UPDATE
                users_setting
            SET
                show_complete = '1'
            WHERE
                user_id = '$user[user_id]'");
        }
    }

    if ($percent >= 1 && $percent <= 99) {
        $percent = round($completedOptions * (100 / $enabledOptions));
    }

    echo '<span id="tabManager"
        data-contacttab="' . $subscription['show_contact_detail'] . '"
        data-phototab="' . $photoTabEnabled . '"
        data-photologo="' . $subscription['show_logo_upload'] . '"
        data-photologoused="' . $photoLogoUsed . '"
        data-photoprofile="' . $subscription['show_profile_photo'] . '"
        data-photoprofileused="' . $photoProfileUsed . '"
        data-photoname="' . $photoName . '"
        data-listingtab="' . $subscription['show_listing_details'] . '"
        data-abouttab="' . $subscription['show_about_tab'] . '"
        data-servicetab="' . $subscription['location_limit'] . '"
        data-phototext="' . $photoTabText . '"
        style="display:none;"></span>'; ?>
    <?php if ($subscription['show_dashboard_progress_bar'] != "0" && $percent < 100) { ?>
        <div class="well hidden-xs">
            <h4>%%%your_listing_is%%% <?php echo strip_tags($percent); ?>% %%%complete_label%%%</h4>
            <div class="progress active nomargin">
                <?php
                if ($percent < 100) {
                    $warningSuccessClass = 'warning';
                } else {
                    $warningSuccessClass = 'success';
                } ?>
                <div class="progress-bar three-sec progress-bar-<?php echo $warningSuccessClass; ?>" role="progressbar" data-transitiongoal="<?php echo strip_tags($percent); ?>"><?php echo strip_tags($percent); ?>%
                    %%%complete_label%%%
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="row">
        <?php // echo widget("bettermode-sso"); ?>
        <?php
        $allowFBSync = $subscription['show_fb_login_btn'];
        $allowGoogle = $subscription['show_google_login_btn'];
        $subscription['data_settings'] = array_filter($subscription['data_settings']);
        if (is_array($subscription['data_settings']) && count($subscription['data_settings']) > 0) {
            $subresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
                `data_id`
            FROM
                data_categories
            WHERE
                data_id NOT IN ('1','2','3','4')
                AND
                data_id IN (" . join(',', $subscription['data_settings']) . ")
            ORDER BY
                profile_display_order ASC");
        }
        if ($subscription['show_add_content'] == "1" && mysql_num_rows($subresults) > 0) { ?>
            <div class="col-md-12 dashboard-publish-content">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="nomargin bold">
                            <i class="fa fa-pencil-square-o"></i>
                            %%%add_content_label%%%
                        </h4>
                    </div>
                    <div class="panel-body">
                        <?php
                        $idsToNotShow = array(28, 10, 13, 29, 21, 22, 19);
                        while ($dt = mysql_fetch_assoc($subresults)) {
                            global $ds;
                            $ds = getDataCategory($dt['data_id'], "data_id", $w);


                            if ($ds['data_type'] == 28) {
                                global $parentIDExists;
                                //check if this site is using the new sub accounts module
                                if ($parentIDExists) {
                                    $parentId['parent_id'] =  $parentIDExists;
                                } else {
                                    $parentIdQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
                                    parent_id
                                FROM
                                    `users_data`
                                WHERE
                                    user_id = '" . $user_data['user_id'] . "'");
                                    $parentId = mysql_fetch_assoc($parentIdQuery);
                                }
                                if ($parentId['parent_id'] != 0) {
                                    $hideSubAccounts = 1;
                                } else {
                                    $hideSubAccounts = 0; //show sub_accounts
                                }
                                $multiListingAddOnInfo = getAddOnInfo("sub_accounts", '9268c02160f09766325e6b39cd4b7b87');
                            }
                            if ($ds['data_type'] == 28 && $hideSubAccounts == 0 && addonController::isAddonActive('sub_accounts') === true) {

                                if ($ds['data_name'] != "") { ?>
                                    <div class="col-sm-6 col-md-4 hpad">
                                        <a href="/account/<?php echo $ds['data_filename']; ?>/add" class="alert btn-default btn-block fpad bmargin">
                                            <?php echo $ds['icon']; ?><?php echo $ds['data_name']; ?>
                                        </a>
                                    </div>
                                <? }
                            } else if ($ds['data_name'] != "" && !in_array($ds['data_type'], $idsToNotShow) && post_payment_controller::canViewDigitalFeature($ds['data_id'])) { ?>
                                <div class="col-sm-6 col-md-4 hpad">
                                    <a href="/account/<?php echo $ds['data_filename']; ?>/add" class="alert btn-default btn-block fpad bmargin">
                                        <?php echo $ds['icon']; ?><?php echo $ds['data_name']; ?>
                                    </a>
                                </div>
                        <?php
                            }
                        } ?>
                    </div>
                </div>
            </div>
        <?php } //END SHOW ADD CONTENT IF STATEMENT
        if ($subscription['show_dashboard_manage_listing'] != "0") {
        ?>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="nomargin bold">
                            <i class="fa fa-user"></i>
                            %%%manage_label%%% %Listing%
                        </h4>
                    </div>
                    <div class="panel-body" style="min-height: 290px;">
                        <table class="table nomargin">
                            <tbody>
                                <?php
                                echo $contactDetailsTab . $profilePhotoTab . $listingDetailsTab . $aboutTab . $serviceAreaTab;
                                $addonFBlogin = getAddOnInfo('fb_login', 'c7303c75e37fb6af15dd8056dc19d54a');
                                $addonGoogleLogin = getAddOnInfo("google_login", "1a6d19e7b022116626760695b465066b");
                                $allowFBLogin = $w['fb_login_status'];
                                $allowGoogleLogin = $w['google_login_status'];
                                $googleKeyValidation = (!empty($w['api_google_developer_key']) && !empty($w['api_google_developer_clientid'])) ? true : false;
                                $fbKeyValidation = $w['fb_app_id'];
                                if (isset($addonFBlogin['status']) && $addonFBlogin['status'] === 'success' && !empty($fbKeyValidation) && $allowFBSync == 1 && $allowFBLogin == 1) {
                                    if (empty($user_data['facebook_id'])) { ?>
                                        <tr>
                                            <td><a href="" id="facebookAction" data-action="sync" class="btn btn-facebook btn-block">
                                                    <i class="fa fa-facebook-official" aria-hidden="true"></i>
                                                    %%%fb_sync%%%
                                                </a></td>
                                        </tr>
                                    <?php } else { ?>
                                        <tr>
                                            <td><a href="" id="facebookAction" data-action="unsync" class="btn btn-facebook btn-block">
                                                    <i class="fa fa-facebook-official" aria-hidden="true"></i>
                                                    %%%fb_unsync%%%
                                                </a></td>
                                        </tr>
                                    <?php }
                                    echo widget($addonFBlogin['widget'], "", $w['website_id'], $w);
                                }
                                if (isset($addonGoogleLogin['status']) && $addonGoogleLogin['status'] === 'success' && $allowGoogle == 1 && $allowGoogleLogin == 1 && $googleKeyValidation === true) {
                                    if (empty($user_data['google_id'])) { ?>
                                        <tr>
                                            <td><a id="googleAction" data-action="sync" class="btn btn-google btn-block">
                                                    <i class="fa fa-google" aria-hidden="true"></i>
                                                    %%%google_sync%%%
                                                </a></td>
                                        </tr>
                                    <?php } else { ?>
                                        <tr>
                                            <td><a id="googleAction" data-action="unsync" class="btn btn-google btn-block">
                                                    <i class="fa fa-google" aria-hidden="true"></i>
                                                    %%%google_unsync%%%
                                                </a></td>
                                        </tr>
                                <?php }
                                    echo widget($addonGoogleLogin['widget'], "", $w['website_id'], $w);
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <? };
        if ($subscription['show_dashboard_account_details'] != "0") { ?>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="nomargin bold">
                            <i class="fa fa-cog"></i>
                            %%%dashboard_accountdetails_title%%%
                        </h4>
                    </div>
                    <div class="panel-body" style="min-height: 290px;">
                        <table class="table table-striped nomargin" style="margin:0!important;">
                            <tr>
                                <td>
                                    <span class="play">
                                        <i class="fa fa-play-circle font-lg rmargin text-primary"></i> %%%dashboard_joined%%%: <b><?php echo transformDate($user_data['signup_date'], "QB"); ?></b>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fa fa-play-circle font-lg rmargin text-primary"></i> %%%dashboard_level%%%:
                                    <b><?php echo $subscription['subscription_name']; ?></b>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fa fa-play-circle font-lg rmargin text-primary"></i>
                                    %%%dashboard_status%%%:
                                    <?php
                                    if ($user_data['active'] == 1) { ?>
                                        <span class="text-danger bold">%%%dashboard_notactivated%%%</span>
                                        <?php
                                    } else {
                                        if ($user_data['active'] == 2) { ?>
                                            <span class="bold">%%%dashboard_active%%%</span>
                                        <?php
                                        } else if ($user_data['active'] == 5) { ?>
                                            <span class="text-danger bold">%%%past_due_text_label%%%</span>
                                        <?php
                                        } else { ?>
                                            <span class="text-danger bold">%%%dashboard_onhold%%%</span>
                                    <?php }
                                    } ?>
                                </td>
                            </tr>
                            <?php
                            if ($user_data['verified'] == 1) { ?>
                                <tr>
                                    <td>
                                        <a class="progress-done" href="/account/promote/verify-submit">
                                            <i class="fa fa-play-circle font-lg rmargin text-primary"></i>
                                            %%%dashboard_verified_member%%%
                                        </a>
                                    </td>
                                </tr>
                            <?php
                            } else if ($w['promote_feature_verify'] == 1) { ?>
                                <tr>
                                    <td>
                                        <a class="play" href="/account/promote/verify">
                                            <i class="fa fa-play-circle font-lg rmargin text-primary"></i>
                                            %%%dashboard_verifyyour%%%
                                            %%%Listing%%%
                                        </a>
                                    </td>
                                </tr>
                            <?php
                            } ?>
                            <?php if ($subscription['hide_billing_links'] != 1) { ?>
                                <tr>
                                    <td>
                                        <a href="/account/billing/change" class="play">
                                            <i class="fa fa-play-circle font-lg rmargin text-primary"></i>
                                            %%%dashboard_billingdetails%%%
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td>
                                    <a class="play" href="/account/password">
                                        <i class="fa fa-play-circle font-lg rmargin text-primary"></i> %%%profile_edit_pw%%%
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a class="play" href="/logout">
                                        <i class="fa fa-play-circle font-lg rmargin text-primary"></i>
                                        %%%dashboard_logout%%%
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        <?php }
        if ($user_data['filename'] != '' && $subscription['searchable'] == "1" && $subscription['show_dashboard_qr_code'] != "0") { ?>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="nomargin bold">
                            <i class="fa fa-bullhorn"></i>
                            %%%dashboard_qrtitle%%%
                        </h4>
                    </div>
                    <div class="panel-body text-center" style="min-height: 290px;">
                        <h4 class="nomargin">%%%dashboard_qrsubtitle%%%</h4>
                        <img src="https://api.qrserver.com/v1/create-qr-code/?data=<?php echo brilliantDirectories::getWebsiteUrl() . '/' . $user_data['filename']; ?>&amp;size=161x161" class="vmargin">
                        <p class="nomargin">
                            %%%dashboard_qrtext%%%
                        </p>
                    </div>
                </div>
            </div>
        <?php
        } ?>
    </div>
    <div class="clearfix"></div>

    <?php
    if ($user_data['filename'] != '' && $subscription['searchable'] == "1" && $subscription['show_dashboard_member_badge'] != "0") { ?>
        <div class="row">
            <div class="col-md-12">
                <div class="well">
                    <h2 class="text-center">
                        %%%dashboard_promote_titleone%%%
                        <br>
                        %%%dashboard_promote_titletwo%%%
                    </h2>
                    <p class="text-center">
                        %%%dashboard_promote_subtitleone%%%
                        <br>
                        <?php
                        if ($subscription['dashboard_member_badge'] != "" && addonController::isAddonActive('member_listing_badges')) {
                            $imageSource = brilliantDirectories::getWebsiteUrl() . $subscription['dashboard_member_badge'];
                        } else if ($w['facebook_secret'] != "") {
                            $imageSource = brilliantDirectories::getWebsiteUrl() . $w['facebook_secret'];
                        } else {
                            $imageSource = brilliantDirectories::getWebsiteUrl() . '/images/memberbadge.png';
                        } ?>

                        <img class="center-block" src="<?php echo $imageSource; ?>" />
                    </p>
                    <p class="text-center">%%%dashboard_promote_subtitletwo%%%</p>
                    <form name=myform2>
                        <div class="form-group">
                            <textarea class="form-control" name="name" tabindex=1 onClick="selectText('markupArea');" id="markupArea"><a href="<?php echo brilliantDirectories::getWebsiteUrl() . "/" . $user_data['filename'] ?>?from=badge"  title="%%%member_badge_find_me_on%%% <?php echo $w['website_name']; ?>" target="_blank"><img src="<?php echo $imageSource; ?>" border=0/></a>
                        </textarea>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php }
    if ($subscription['user_dashboard_footer_widget'] != "") {
        if ($subscription['user_dashboard_footer_widget'] == 'dashboard_footer_custom') {
            echo $subscription['membership_dashboard_footer_content'];
        } else {
            echo widget($subscription['user_dashboard_footer_widget'], "", $w['website_id'], $w);
        }
    }
    ?>
<? } ?>



    </div>
    <div class="tab__content-2">

    [widget=bettermode-sso]

    </div>
    <div class="tab__content-3">Tab Content 3</div>
  </div>
</div>