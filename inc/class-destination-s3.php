<?php
class S3Testing_Destination_S3
{
    public function option_defaults()
    {
        return [
            's3base_url' => '',
            's3base_multipart' => true,
            's3base_pathstylebucket' => false,
            's3base_version' => 'latest',
            's3base_signature' => 'v4',
            's3accesskey' => '',
            's3secretkey' => '',
            's3bucket' => '',
            's3region' => 'us-east-1',
            's3ssencrypt' => '',
            's3storageclass' => '',
            's3dir' => trailingslashit(sanitize_file_name(get_bloginfo('name'))),
            's3maxbackups' => 15,
            's3syncnodelete' => true,
        ];
    }

    public function edit_tab($jobid)
    {
        ?>
        <h3 class="title">
            <?php esc_html_e('S3 Service', 'backwpup'); ?>
        </h3>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="s3region">
                        <?php esc_html_e('Select a S3 service', 'backwpup'); ?>
                    </label>
                </th>
                <td>

                    <select name="s3region"
                            id="s3region"
                            title="<?php esc_attr_e('S3 Region', 'backwpup'); ?>">
                        <?php foreach (S3Testing_S3_Destination::options() as $id => $option) { ?>
                            <option value="<?php echo esc_attr($id); ?>"
                                <?php selected($id, S3Testing_Option::get($jobid, 's3region')); ?>
                            >
                                <?php echo esc_html($option['label']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
<!--            <tr>-->
<!--                <th scope="row">-->
<!--                    <label for="s3base_url">-->
<!--                        --><?php //esc_html_e('Or a S3 Server URL', 'backwpup'); ?>
<!--                    </label>-->
<!--                </th>-->
<!--                <td>-->
<!--                    <div class="card" style="margin-top:0;padding:10px">-->
<!--                        <table class="form-table">-->
<!--                            <tbody>-->
<!--                            <tr>-->
<!--                                <th scope="row">-->
<!--                                    <label for="s3base_url-s3">--><?php //esc_html_e('Endpoint', 'backwpup'); ?><!--<span-->
<!--                                            style="color:red">*</span></label>-->
<!--                                </th>-->
<!--                                <td>-->
<!--                                    <input-->
<!--                                        id="s3base_url"-->
<!--                                        name="s3base_url"-->
<!--                                        type="text"-->
<!--                                        value="--><?php //echo esc_attr(
//                                            BackWPup_Option::get($jobid, 's3base_url')
//                                        ); ?><!--"-->
<!--                                        class="regular-text"-->
<!--                                        autocomplete="off"-->
<!--                                    />-->
<!--                                    <p class="description">--><?php //esc_html_e(
//                                            'Leave it empty to use a destination from S3 service list',
//                                            'backwpup'
//                                        ); ?><!--</p>-->
<!--                                </td>-->
<!--                            </tr>-->
<!---->
<!--                            <tr>-->
<!--                                <th scope="row">-->
<!--                                    <label for="s3base_region">--><?php //esc_html_e(
//                                            'Region',
//                                            'backwpup'
//                                        ); ?><!--<span style="color:red">*</span></label>-->
<!--                                </th>-->
<!--                                <td>-->
<!--                                    <input type="text" name="s3base_region" value="--><?php //echo esc_attr(
//                                        BackWPup_Option::get($jobid, 's3base_region')
//                                    ); ?><!--" class="regular-text" autocomplete="off">-->
<!--                                    <p class="description">--><?php //esc_html_e(
//                                            'Specify S3 region like "us-west-1"',
//                                            'backwpup'
//                                        ); ?><!--</p>-->
<!--                                </td>-->
<!--                            </tr>-->
<!--                            </tbody>-->
<!---->
<!--                            <tbody class="custom_s3_advanced">-->
<!--                            <tr>-->
<!--                                <th scope="row">--><?php //esc_html_e('Multipart', 'backwpup'); ?><!--</th>-->
<!--                                <td>-->
<!--                                    <fieldset>-->
<!--                                        <legend class="screen-reader-text">-->
<!--                                            <span>--><?php //esc_html_e(
//                                                    'Multipart',
//                                                    'backwpup'
//                                                ); ?><!--</span>-->
<!--                                        </legend>-->
<!--                                        <label for="s3base_multipart">-->
<!--                                            <input name="s3base_multipart" type="checkbox"-->
<!--                                                   value="1"--><?php //echo checked(
//                                                BackWPup_Option::get(
//                                                    $jobid,
//                                                    's3base_multipart'
//                                                ),
//                                                true
//                                            ); ?><!-->-->
<!--                                            --><?php //esc_html_e(
//                                                'Destination supports multipart',
//                                                'backwpup'
//                                            ); ?><!-- </label>-->
<!--                                    </fieldset>-->
<!--                                </td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <th scope="row">--><?php //esc_html_e(
//                                        'Pathstyle-Only Bucket',
//                                        'backwpup'
//                                    ); ?><!--</th>-->
<!--                                <td>-->
<!--                                    <fieldset>-->
<!--                                        <legend class="screen-reader-text">-->
<!--                                            <span>--><?php //esc_html_e(
//                                                    'Pathstyle-Only Bucket',
//                                                    'backwpup'
//                                                ); ?><!--</span>-->
<!--                                        </legend>-->
<!--                                        <label-->
<!--                                            for="s3base_pathstylebucket">-->
<!--                                            <input name="s3base_pathstylebucket" type="checkbox"-->
<!--                                                   value="1"--><?php //echo checked(
//                                                BackWPup_Option::get(
//                                                    $jobid,
//                                                    's3base_pathstylebucket'
//                                                ),
//                                                true
//                                            ); ?><!-->-->
<!--                                            --><?php //esc_html_e(
//                                                'Destination provides only Pathstyle buckets',
//                                                'backwpup'
//                                            ); ?><!--    </label>-->
<!--                                        <p class="description">--><?php //esc_html_e(
//                                                'Example: http://s3.example.com/bucket-name',
//                                                'backwpup'
//                                            ); ?><!--</p>-->
<!---->
<!--                                    </fieldset>-->
<!--                                </td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <th scope="row">-->
<!--                                    <label for="s3base_version">Version</label>-->
<!--                                </th>-->
<!--                                <td>-->
<!--                                    <input type="text" name="s3base_version"-->
<!--                                           value="--><?php //echo !empty(
//                                           BackWPup_Option::get(
//                                               $jobid,
//                                               's3base_version'
//                                           )
//                                           ) ? esc_attr(
//                                               BackWPup_Option::get($jobid, 's3base_version')
//                                           ) : 'latest'; ?><!--"-->
<!--                                           placeholder="latest">-->
<!--                                    <p class="description">--><?php //esc_html_e(
//                                            'The S3 version for the API like "2006-03-01", default "latest"',
//                                            'backwpup'
//                                        ); ?><!--</p>-->
<!--                                </td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <th scope="row">-->
<!--                                    <label-->
<!--                                        for="s3base_signature">--><?php //esc_html_e(
//                                            'Signature',
//                                            'backwpup'
//                                        ); ?><!--</label>-->
<!--                                </th>-->
<!--                                <td>-->
<!--                                    <input type="text" name="s3base_signature"-->
<!--                                           value="--><?php //echo !empty(
//                                           BackWPup_Option::get(
//                                               $jobid,
//                                               's3base_signature'
//                                           )
//                                           ) ? esc_attr(
//                                               BackWPup_Option::get($jobid, 's3base_signature')
//                                           ) : 'v4'; ?><!--"-->
<!--                                           placeholder="v4">-->
<!--                                    <p class="description">--><?php //esc_html_e(
//                                            'The signature for the API like "v4"',
//                                            'backwpup'
//                                        ); ?><!--</p>-->
<!--                                </td>-->
<!--                            </tr>-->
<!--                            </tbody><!-- advanced section-->-->
<!--                        </table>-->
<!--                    </div>-->
<!--                </td><!-- custom s3 section-->-->
<!--            </tr>-->
        </table>

        <h3 class="title">
            <?php esc_html_e('S3 Access Keys', 'backwpup'); ?>
        </h3>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="s3accesskey">
                        <?php esc_html_e('Access Key', 'backwpup'); ?>
                    </label>
                </th>
                <td>
                    <input id="s3accesskey"
                           name="s3accesskey"
                           type="text"
                           value="<?php echo esc_attr(S3Testing_Option::get($jobid, 's3accesskey')); ?>"
                           class="regular-text"
                           autocomplete="off"
                    />
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="s3secretkey"><?php esc_html_e('Secret Key', 'backwpup'); ?></label></th>
                <td>
                    <input id="s3secretkey" name="s3secretkey" type="password"
                           value="<?php echo esc_attr(BackWPup_Encryption::decrypt(BackWPup_Option::get(
                               $jobid,
                               's3secretkey'
                           ))); ?>" class="regular-text" autocomplete="off"/>
                </td>
            </tr>
        </table>

        <h3 class="title">
            <?php esc_html_e('S3 Bucket', 'backwpup'); ?>
        </h3>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="s3bucketselected">
                        <?php esc_html_e('Bucket selection', 'backwpup'); ?>
                    </label>
                </th>
                <td>
                    <input id="s3bucketselected"
                           name="s3bucketselected"
                           type="hidden"
                           value="<?php echo esc_attr(BackWPup_Option::get($jobid, 's3bucket')); ?>"
                    />
                    <?php
                    if (BackWPup_Option::get($jobid, 's3accesskey')
                        && BackWPup_Option::get($jobid, 's3secretkey')
                    ) {
                        $this->edit_ajax(
                            [
                                's3accesskey' => BackWPup_Option::get($jobid, 's3accesskey'),
                                's3secretkey' => BackWPup_Option::get($jobid, 's3secretkey'),
                                's3bucketselected' => BackWPup_Option::get($jobid, 's3bucket'),
                                's3region' => BackWPup_Option::get($jobid, 's3region'),
                                's3base_url' => BackWPup_Option::get($jobid, 's3base_url'),
                                's3base_region' => BackWPup_Option::get($jobid, 's3base_region'),
                                's3base_multipart' => BackWPup_Option::get(
                                    $jobid,
                                    's3base_multipart'
                                ),
                                's3base_pathstylebucket' => BackWPup_Option::get(
                                    $jobid,
                                    's3base_pathstylebucket'
                                ),
                                's3base_version' => BackWPup_Option::get($jobid, 's3base_version'),
                                's3base_signature' => BackWPup_Option::get(
                                    $jobid,
                                    's3base_signature'
                                ),
                            ]
                        );
                    } ?>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="s3newbucket">
                        <?php esc_html_e('Create a new bucket', 'backwpup'); ?>
                    </label>
                </th>
                <td>
                    <input id="s3newbucket"
                           name="s3newbucket"
                           type="text"
                           value=""
                           size="63"
                           class="regular-text"
                           autocomplete="off"
                    />
                </td>
            </tr>
        </table>

        <h3 class="title">
            <?php esc_html_e('S3 Backup settings', 'backwpup'); ?>
        </h3>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="ids3dir">
                        <?php esc_html_e('Folder in bucket', 'backwpup'); ?>
                    </label>
                </th>
                <td>
                    <input id="ids3dir"
                           name="s3dir"
                           type="text"
                           value="<?php echo esc_attr(BackWPup_Option::get($jobid, 's3dir')); ?>"
                           class="regular-text"
                    />
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('File deletion', 'backwpup'); ?></th>
                <td>
                    <?php
                    if (BackWPup_Option::get($jobid, 'backuptype') === 'archive') {
                        ?>
                        <label for="ids3maxbackups">
                            <input id="ids3maxbackups"
                                   name="s3maxbackups"
                                   type="number"
                                   min="0"
                                   step="1"
                                   value="<?php echo esc_attr(BackWPup_Option::get($jobid, 's3maxbackups')); ?>"
                                   class="small-text"
                            />
                            &nbsp;<?php esc_html_e('Number of files to keep in folder.', 'backwpup'); ?>
                        </label>
                        <p>
                            <?php _e(
                                '<strong>Warning</strong>: Files belonging to this job are now tracked. Old backup archives which are untracked will not be automatically deleted.',
                                'backwpup'
                            ); ?>
                        </p>
                        <?php
                    } else { ?>
                        <label for="ids3syncnodelete">
                            <input class="checkbox" value="1"
                                   type="checkbox"
                                <?php checked(BackWPup_Option::get($jobid, 's3syncnodelete'), true); ?>
                                   name="s3syncnodelete"
                                   id="ids3syncnodelete"
                            />
                            <?php esc_html_e('Do not delete files while syncing to destination!', 'backwpup'); ?>
                        </label>
                    <?php } ?>
                </td>
            </tr>
        </table>

        <h3 class="title"><?php esc_html_e('Amazon specific settings', 'backwpup'); ?></h3>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="ids3storageclass">
                        <?php esc_html_e('Amazon: Storage Class', 'backwpup'); ?>
                    </label>
                </th>
                <td>
                    <?php $storageClass = BackWPup_Option::get($jobid, 's3storageclass'); ?>
                    <select name="s3storageclass"
                            id="ids3storageclass"
                            title="<?php esc_html_e('Amazon: Storage Class', 'backwpup'); ?>">
                        <option value=""
                            <?php selected('', $storageClass, true); ?>>
                            <?php esc_html_e('Standard', 'backwpup'); ?>
                        </option>
                        <option value="STANDARD_IA"
                            <?php selected('STANDARD_IA', $storageClass, true); ?>>
                            <?php esc_html_e('Standard-Infrequent Access', 'backwpup'); ?>
                        </option>
                        <option value="ONEZONE_IA"
                            <?php selected('ONEZONE_IA', $storageClass, true); ?>>
                            <?php esc_html_e('One Zone-Infrequent Access', 'backwpup'); ?>
                        </option>
                        <option value="REDUCED_REDUNDANCY"
                            <?php selected('REDUCED_REDUNDANCY', $storageClass, true); ?>>
                            <?php esc_html_e('Reduced Redundancy', 'backwpup'); ?>
                        </option>
                        <option value="INTELLIGENT_TIERING"
                            <?php selected('INTELLIGENT_TIERING', $storageClass, true); ?>>
                            <?php esc_html_e('Intelligent-Tiering', 'backwpup'); ?>
                        </option>
                        <option value="GLACIER_IR"
                            <?php selected('GLACIER_IR', $storageClass, true); ?>>
                            <?php esc_html_e('Glacier Instant Retrieval', 'backwpup'); ?>
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="ids3ssencrypt">
                        <?php esc_html_e('Server side encryption', 'backwpup'); ?>
                    </label>
                </th>
                <td>
                    <input class="checkbox"
                           value="AES256"
                           type="checkbox"
                        <?php checked(BackWPup_Option::get($jobid, 's3ssencrypt'), 'AES256'); ?>
                           name="s3ssencrypt"
                           id="ids3ssencrypt"
                    />
                    <?php esc_html_e('Save files encrypted (AES256) on server.', 'backwpup'); ?>
                </td>
            </tr>
        </table>

        <?php
    }
}