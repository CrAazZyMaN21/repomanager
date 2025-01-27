<section class="section-main reloadable-container" container="hosts/list">
    <?php
    if ($totalHosts >= 1) : ?>
        <div id="hostsDiv">
            <div>
                <div class="flex justify-space-between margin-top-50 margin-bottom-40">
                    <h3 class="margin-0">HOSTS</h3>

                    <div>
                        <div id="compact-view-btn" class="slide-btn" title="Compact/Full view">
                            <img src="/assets/icons/view.svg" />
                            <span>Compact/Full view</span>
                        </div>

                        <?php
                        if (IS_ADMIN) : ?>
                            <div class="slide-btn get-panel-btn" panel="hosts/groups/list" title="Manage hosts groups">
                                <img src="/assets/icons/folder.svg" />
                                <span>Manage groups</span>
                            </div>

                            <div class="slide-btn get-panel-btn" panel="hosts/settings" title="Edit display settings">
                                <img src="/assets/icons/cog.svg" />
                                <span>Settings</span>
                            </div>
                            <?php
                        endif ?>
                    </div>
                </div>

                <?php
                if (!empty($hostGroupsList)) :
                    /**
                     *  If there is at least 1 active host then we display the search fields
                     */
                    if ($totalHosts != 0) : ?>
                        <div class="grid grid-2 justify-space-between column-gap-20">
                            <div>
                                <h6>SEARCH HOST</h6>
                                <input type="text" id="search-host-input" onkeyup="searchHost()" autocomplete="off" placeholder="Hostname" title="Search a host by its name.&#13;&#13;You can specify a filter before your search entry:&#13;os:<os name> <search>&#13;os_version:<os version> <search>&#13;os_family:<os family> <search>&#13;type:<virtualization type> <search>&#13;kernel:<kernel> <search>&#13;arch:<architecture> <search>&#13;profile:<profile> <search>&#13;env:<env> <search>&#13;agent_version:<version> <search>&#13;reboot_required:<true/false> <search>" />
                            </div>

                            <div>
                                <h6>SEARCH PACKAGE</h6>
                                <input type="text" id="getHostsWithPackageInput" onkeyup="getHostsWithPackage()" autocomplete="off" placeholder="Package name" />
                            </div>
                        </div>
                        <br><br>
                        <?php
                    endif ?>
                    
                    <div class="groups-container">
                        <?php
                        foreach ($hostGroupsList as $group) :
                            /**
                             *  Retrieve the list of hosts in the group
                             */
                            $hostsList = $myhost->listByGroup($group['Name']);

                            /**
                             *  If it's the default group 'Default' and it has no host then we ignore its display
                             */
                            if ($group['Name'] == "Default" and empty($hostsList)) {
                                continue;
                            } ?>
                            <input type='hidden' name='groupname' value='<?=$group['Name']?>'>
            
                            <div class="hosts-group-container div-generic-blue veil-on-reload">
                                <?php
                                /**
                                 *  Print the group name except if it's the Default group
                                 */
                                if ($group['Name'] == 'Default') {
                                    $groupName = 'Ungrouped';
                                } else {
                                    $groupName = $group['Name'];
                                }

                                /**
                                 *  Count number of hosts in the group
                                 */
                                $hostsCount = count($hostsList);

                                /**
                                 *  Generate count message
                                 */
                                if ($hostsCount < 2) {
                                    $countMessage = $hostsCount . ' host';
                                } else {
                                    $countMessage = $hostsCount . ' hosts';
                                } ?>

                                <div class="flex justify-space-between">
                                    <div>
                                        <p class="font-size-16"><?= $groupName ?></p>
                                        <p class="lowopacity-cst"><?= $countMessage ?></p>
                                    </div>
                                </div>

                                <?php
                                /**
                                 *  Print the hosts of the group
                                 */
                                if (!empty($hostsList)) : ?>
                                    <div class="hosts-table">
                                        <div class="flex justify-end margin-bottom-10">
                                            <?php
                                            if (IS_ADMIN) : ?>
                                                <span class="margin-right-15">
                                                    <input class="js-select-all-button lowopacity pointer" type="checkbox" group="<?= $group['Name'] ?>" title="Select all" >
                                                </span>
                                                <?php
                                            endif ?>
                                        </div>
                                    
                                        <?php
                                        /**
                                         *  Process the hosts list
                                         *  Here we will display the details of each host and we take the opportunity to retrieve some additional information from the database
                                         */
                                        foreach ($hostsList as $host) :
                                            $id = $host['Id'];
                                            $hostname = 'unknown';
                                            $ip = 'unknown';
                                            $os = 'unknown';
                                            $osVersion = 'unknown';
                                            $osFamily = 'unknown';
                                            $type = 'unknown';
                                            $kernel = 'unknown';
                                            $arch = 'unknown';
                                            $profile = 'unknown';
                                            $env = 'unknown';
                                            $agentVersion = 'unknown';
                                            $rebootRequired = 'unknown';
                                            $agentStatus = 'unknown';
                                            $responseDetails = null;

                                            if (!empty($host['Hostname'])) {
                                                $hostname = $host['Hostname'];
                                            }
                                            if (!empty($host['Ip'])) {
                                                $ip = $host['Ip'];
                                            }
                                            if (!empty($host['Os'])) {
                                                $os = $host['Os'];
                                            }
                                            if (!empty($host['Os_version'])) {
                                                $osVersion = $host['Os_version'];
                                            }
                                            if (!empty($host['Os_family'])) {
                                                $osFamily = $host['Os_family'];
                                            }
                                            if (!empty($host['Type'])) {
                                                $type = $host['Type'];
                                            }
                                            if (!empty($host['Kernel'])) {
                                                $kernel = $host['Kernel'];
                                            }
                                            if (!empty($host['Arch'])) {
                                                $arch = $host['Arch'];
                                            }
                                            if (!empty($host['Profile'])) {
                                                $profile = $host['Profile'];
                                            }
                                            if (!empty($host['Env'])) {
                                                $env = $host['Env'];
                                            }
                                            if (!empty($host['Linupdate_version'])) {
                                                $agentVersion = $host['Linupdate_version'];
                                            }
                                            if (!empty($host['Reboot_required'])) {
                                                $rebootRequired = $host['Reboot_required'];
                                            }
                                            if (!empty($host['Online_status'])) {
                                                $agentStatus = $host['Online_status'];
                                            }

                                            /**
                                             *  Check if the last time the agent reported its status is less than 1h (and 10min of "margin")
                                             */
                                            if ($host['Online_status_date'] != DATE_YMD or $host['Online_status_time'] <= date('H:i:s', strtotime(date('H:i:s') . ' - 70 minutes'))) {
                                                $agentStatus = 'seems-stopped';
                                            }

                                            /**
                                             *  Last known status message
                                             */
                                            $agentLastSendStatusMsg = 'state on ' . DateTime::createFromFormat('Y-m-d', $host['Online_status_date'])->format('d-m-Y') . ' ' . $host['Online_status_time'];

                                            /**
                                             *  Open the dedicated database of the host from its ID to be able to retrieve additional information
                                             */
                                            $hostDb->openHostDb($id);

                                            /**
                                             *  Retrieve the total number of available packages
                                             */
                                            $packagesAvailableTotal = count($hostDb->getPackagesAvailable());

                                            /**
                                             *  Retrieve the total number of installed packages
                                             */
                                            $packagesInstalledTotal = count($hostDb->getPackagesInstalled());

                                            /**
                                             *  Retrieve the last pending request (if there is one)
                                             */
                                            $lastPendingRequest = $myhost->getLastPendingRequest($id);

                                            /**
                                             *  Close the dedicated database of the host
                                             */
                                            $hostDb->closeHostDb();

                                            /**
                                             *  Print the host informations
                                             *  Here the <div> will contain all the host informations in order to be able to search on it (input 'search a host')
                                             */ ?>
                                            <div class="host-line flex flex-direction-column div-generic-blue bck-blue-alt margin-bottom-10" hostid="<?= $id ?>" hostname="<?= $hostname ?>" os="<?= $os ?>" os_version="<?= $osVersion ?>" os_family="<?= $osFamily ?>" type="<?= $type ?>" kernel="<?= $kernel ?>" arch="<?= $arch ?>" profile="<?= $profile ?>" env="<?= $env ?>" agent_version="<?= $agentVersion ?>" reboot_required="<?= $rebootRequired ?>">
                                                <div class="flex column-gap-20">
                                                    <div class="align-self-center">
                                                        <?php
                                                        if ($agentStatus == 'running') : ?>
                                                            <img src="/assets/icons/check.svg" class="icon-np" title="Agent is running" />
                                                            <?php
                                                        endif;

                                                        if ($agentStatus != 'running') : ?>
                                                            <img src="/assets/icons/warning-red.svg" class="icon-np" title="Agent state on the host: <?= $agentStatus ?> (<?= $agentLastSendStatusMsg ?>)" />
                                                            <?php
                                                        endif ?>
                                                    </div>

                                                    <div class="width-100">
                                                        <div class="grid grid-4 row-gap-20 column-gap-20">
                                                            <div class="flex flex-direction-column">
                                                                <div class="">
                                                                    <h6 class="margin-top-0">
                                                                        <a href="/host/<?= $id ?>" class="wordbreakall" target="_blank" rel="noopener noreferrer">
                                                                            <?= $hostname ?>
                                                                        </a>
                                                                        <span><?= \Controllers\Common::printOsIcon($os); ?></span>
                                                                    </h6>
                                                                </div>
                                                                <p class="mediumopacity-cst copy"><?= $ip ?></p>
                                                            </div>

                                                            <div>
                                                                <h6 class="margin-top-0">TYPE</h6>
                                                                <p class="mediumopacity-cst copy"><?= $type ?></p>
                                                            </div>
                                                            <?php
                                                            if (!$compactView) : ?>
                                                                <div>
                                                                    <h6 class="margin-top-0">AGENT VERSION</h6>
                                                                    <p class="mediumopacity-cst copy"><?= $agentVersion ?></p>
                                                                </div>

                                                                <div>
                                                                    <h6 class="margin-top-0">REBOOT</h6>
                                                                    <p class="flex align-item-center column-gap-5">
                                                                        <?php
                                                                        if ($rebootRequired == 'true') {
                                                                            echo '<img src="/assets/icons/warning.svg" class="icon-np" />';
                                                                            echo '<span>Required</span>';
                                                                        } else {
                                                                            echo '<span class="mediumopacity-cst">Not required</span>';
                                                                        } ?>
                                                                    </p>
                                                                </div>

                                                                <div>
                                                                    <h6 class="margin-top-0">OS</h6>
                                                                    <p class="mediumopacity-cst copy"><?= $os ?></p>
                                                                </div>

                                                                <div>
                                                                    <h6 class="margin-top-0">OS VERSION</h6>
                                                                    <p class="mediumopacity-cst copy"><?= $osVersion ?></p>
                                                                </div>

                                                                <div>
                                                                    <h6 class="margin-top-0">KERNEL</h6>
                                                                    <p class="mediumopacity-cst copy"><?= $kernel ?></p>
                                                                </div>

                                                                <div>
                                                                    <h6 class="margin-top-0">ARCH</h6>
                                                                    <p class="mediumopacity-cst copy"><?= $arch ?></p>
                                                                </div>

                                                                <div>
                                                                    <h6 class="margin-top-0">PROFILE</h6>
                                                                    <p class="mediumopacity-cst copy"><?= $profile ?></p>
                                                                </div>

                                                                <div>
                                                                    <h6 class="margin-top-0">ENVIRONMENT</h6>
                                                                    <p class="copy">
                                                                        <?= \Controllers\Common::envtag($env) ?>
                                                                    </p>
                                                                </div>
                                                                <?php
                                                            endif ?>

                                                            <div>
                                                                <h6 class="margin-top-0"><?= $layoutPackagesTitle ?> INSTALLED</h6>
                                                                <p title="<?= $packagesInstalledTotal . ' package(s) installed on this host' ?>">
                                                                    <span class="label-white"><?= $packagesInstalledTotal ?></span>
                                                                </p>
                                                            </div>

                                                            <div>
                                                                <h6 class="margin-top-0"><?= $layoutPackagesTitle ?> AVAILABLE</h6>
                                                                <p title="<?= $packagesAvailableTotal . ' update(s) available on this host' ?>">
                                                                    <?php
                                                                    if ($packagesAvailableTotal >= $packagesCountConsideredCritical) {
                                                                        echo '<span class="label-white bkg-red">' . $packagesAvailableTotal . '</span>';
                                                                    } elseif ($packagesAvailableTotal >= $packagesCountConsideredOutdated) {
                                                                        echo '<span class="label-white bkg-yellow">' . $packagesAvailableTotal . '</span>';
                                                                    } else {
                                                                        echo '<span class="label-white">' . $packagesAvailableTotal . '</span>';
                                                                    } ?>    
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <?php
                                                        if (!$compactView) : ?>
                                                            <div>
                                                                <?php
                                                                /**
                                                                 *  Last request status
                                                                 *  Ignore it if the request was a 'disconnect' request
                                                                 */
                                                                if (!empty($lastPendingRequest)) :
                                                                    /**
                                                                     *  Retrieve and decode JSON data
                                                                     */
                                                                    $requestJson = json_decode($lastPendingRequest['Request'], true);

                                                                    /**
                                                                     *  Request name
                                                                     */
                                                                    $request = $requestJson['request'];

                                                                    /**
                                                                     *  Request data
                                                                     */
                                                                    if (isset($requestJson['data'])) {
                                                                        $requestData = $requestJson['data'];
                                                                    }

                                                                    if ($request != 'disconnect') :
                                                                        /**
                                                                         *  Response data
                                                                         */
                                                                        if (!empty($lastPendingRequest['Response_json'])) {
                                                                            $responseJson = json_decode($lastPendingRequest['Response_json'], true);
                                                                        }

                                                                        /**
                                                                         *  Request status
                                                                         */
                                                                        if ($lastPendingRequest['Status'] == 'new') {
                                                                            $requestStatus = 'Pending';
                                                                            $requestStatusIcon = 'pending.svg';
                                                                        }
                                                                        if ($lastPendingRequest['Status'] == 'sent') {
                                                                            $requestStatus = 'Sent';
                                                                            $requestStatusIcon = 'pending.svg';
                                                                        }
                                                                        if ($lastPendingRequest['Status'] == 'running') {
                                                                            $requestStatus = 'Running';
                                                                            $requestStatusIcon = 'loading.svg';
                                                                        }
                                                                        if ($lastPendingRequest['Status'] == 'canceled') {
                                                                            $requestStatus = 'Canceled';
                                                                            $requestStatusIcon = 'warning-red.svg';
                                                                        }
                                                                        if ($lastPendingRequest['Status'] == 'failed') {
                                                                            $requestStatus = 'Failed';
                                                                            $requestStatusIcon = 'error.svg';
                                                                        }
                                                                        if ($lastPendingRequest['Status'] == 'completed') {
                                                                            $requestStatus = 'Completed';
                                                                            $requestStatusIcon = 'check.svg';
                                                                        }

                                                                        /**
                                                                         *  Request title
                                                                         */
                                                                        if ($request == 'request-general-infos') {
                                                                            $requestTitle = 'Requested the host to send its general informations';
                                                                            $requestTitleShort = 'Request general informations';
                                                                        }
                                                                        if ($request == 'request-packages-infos') {
                                                                            $requestTitle = 'Requested the host to send its packages informations';
                                                                            $requestTitleShort = 'Request packages informations';
                                                                        }
                                                                        if ($request == 'request-packages-update') {
                                                                            $requestTitle = 'Request to install a list of package(s)';
                                                                            $requestTitleShort = 'Request to update a list of package(s)';

                                                                            if (!empty($requestJson['packages'])) {
                                                                                $requestDetails = count($requestJson['packages']) . ' package(s) to install';
                                                                            }
                                                                        }
                                                                        if ($request == 'request-all-packages-update') {
                                                                            $requestTitle = 'Requested the host to update all of its packages';
                                                                            $requestTitleShort = 'Request to update all packages';

                                                                            if (!empty($responseJson)) {
                                                                                /**
                                                                                 *  If there was no packages to update
                                                                                 */
                                                                                if ($responseJson['update']['status'] == 'nothing-to-do') {
                                                                                    $responseDetails = 'No packages to update';
                                                                                }

                                                                                /**
                                                                                 *  If there was packages to update, retrieve the number of packages updated
                                                                                 */
                                                                                if ($responseJson['update']['status'] == 'done' or $responseJson['update']['status'] == 'failed') {
                                                                                    $successCount = $responseJson['update']['success']['count'];
                                                                                    $failedCount  = $responseJson['update']['failed']['count'];

                                                                                    // If the update was successful
                                                                                    if ($responseJson['update']['status'] == 'done') {
                                                                                        $requestStatus = 'Successful';
                                                                                        $requestStatusIcon = 'check.svg';
                                                                                    }

                                                                                    // If the update failed
                                                                                    if ($responseJson['update']['status'] == 'failed') {
                                                                                        $requestStatus = 'Failed with errors';
                                                                                        $requestStatusIcon = 'error.svg';
                                                                                    }

                                                                                    // If there was packages updated AND packages failed
                                                                                    if ($successCount >= 1 and $failedCount >= 1) {
                                                                                        $requestStatus = 'Partial success';
                                                                                        $requestStatusIcon = 'warning.svg';
                                                                                    }

                                                                                    // If there was no packages updated AND packages failed
                                                                                    if ($successCount == 0 and $failedCount >= 1) {
                                                                                        $requestStatus = 'Failed';
                                                                                        $requestStatusIcon = 'error.svg';
                                                                                    }

                                                                                    // Build a short info message
                                                                                    $responseDetails = $successCount . ' package(s) updated, ' . $failedCount . ' failed';

                                                                                    // Retrieve the list of packages updated
                                                                                    // $successPackages = $responseJson['update']['success']['packages'];

                                                                                    // Retrieve the list of packages failed
                                                                                    // $failedPackages = $responseJson['update']['failed']['packages'];
                                                                                }
                                                                            }
                                                                        }

                                                                        /**
                                                                         *  Only print the request title if it was executed less than 1h ago
                                                                         */
                                                                        if (strtotime($lastPendingRequest['Date'] . ' ' . $lastPendingRequest['Time']) >= strtotime(date('Y-m-d H:i:s') . ' - 1 hour')) : ?>
                                                                            <h6>LAST REQUEST</h6>
                                                                            <div class="flex align-item-center column-gap-5">
                                                                                <?php
                                                                                if (!empty($requestStatusIcon)) {
                                                                                    if (str_ends_with($requestStatusIcon, '.svg')) {
                                                                                        echo '<img src="/assets/icons/' . $requestStatusIcon . '" class="icon-np" title="' . $requestStatus . '" />';
                                                                                    } else {
                                                                                        echo '<span class="' . $requestStatusIcon . '" title="' . $requestStatus . '"></span> ';
                                                                                    }
                                                                                } ?>
                                                                                <p class="mediumopacity-cst" title="<?= $requestTitle ?>">
                                                                                    <?php
                                                                                    echo $requestTitleShort;

                                                                                    if (!empty($responseDetails)) {
                                                                                        echo ' - ' . $responseDetails;
                                                                                    } ?>
                                                                                </p>                                                                            
                                                                            </div>
                                                                            <?php
                                                                        endif;
                                                                    endif;
                                                                endif ?>
                                                            </div>
                                                            <?php
                                                        endif ?>

                                                        <div class="host-additionnal-info"></div>
                                                    </div>

                                                    <div class="align-self-center">
                                                        <?php
                                                        if (IS_ADMIN) : ?>
                                                            <input type="checkbox" class="js-host-checkbox lowopacity pointer" name="checkbox-host[]" group="<?= $group['Name'] ?>" value="<?= $id ?>" title="Select <?= $hostname ?>">
                                                            <?php
                                                        endif ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        endforeach; ?>
                                    </div>
                                    <?php
                                endif ?>
                            </div>
                            <?php
                        endforeach ?>
                    </div>
                    <?php
                endif ?>
            </div>
        </div>
        <?php
    endif; ?>
</section>
