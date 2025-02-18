<?php
class S3Testing_JobType_DBDump extends S3Testing_JobTypes
{
    public function __construct()
    {
        $this->info['ID'] = 'DBDUMP';
        $this->info['name'] = __('DB Backup');
        $this->info['description'] = __('Database backup');
        $this->info['URI'] = __('http://backwpup.com');
        $this->info['author'] = 'WP Media';
        $this->info['authorURI'] = 'https://wp-media.me';
        $this->info['version'] = S3Testing::get_plugin_data('Version');
    }

    public function creates_file()
    {
        return true;
    }

    public function option_defaults()
    {
        global $wpdb;

        $defaults = [
            'dbdumpexclude' => [],
            'dbdumpfile' => sanitize_file_name(DB_NAME),
            'dbdumptype' => 'sql',
            'dbdumpfilecompression' => '',
        ];
        $dbtables = $wpdb->get_results('SHOW TABLES FROM `' . DB_NAME . '`', ARRAY_N);

        foreach ($dbtables as $dbtable) {
            if ($wpdb->prefix != substr((string)$dbtable[0], 0, strlen($wpdb->prefix))) {
                $defaults['dbdumpexclude'][] = $dbtable[0];
            }
        }

        return $defaults;

    }
    public function edit_tab($jobid)
    {
    global $wpdb; ?>
    <input name="dbdumpwpony" type="hidden" value="1"/>
    <h3 class="title"><?php _e('Settings for database backup', 'backwpup'); ?></h3>
    <p></p>
    <table class="form-table">
        <tr>
            <th scope="row"><?php _e('Tables to backup'); ?></th>
            <td>
                <input type="button" class="button-secondary" id="dball" value="<?php esc_attr_e('all'); ?>">&nbsp;
                <input type="button" class="button-secondary" id="dbnone" value="<?php esc_attr_e('none'); ?>">&nbsp;
                <input type="button" class="button-secondary" id="dbwp" value="<?php echo esc_attr($wpdb->prefix); ?>">
                <?php

                $tables = $wpdb->get_results('SHOW FULL TABLES FROM `' . DB_NAME . '`', ARRAY_N);

                echo '<fieldset id="dbtables"><div style="width: 30%; float:left; min-width: 250px; margin-right: 10px;">';
                $next_row = ceil(count($tables) / 3);
                $counter = 0;

                foreach ($tables as $table) {
                    $tabletype = '';
                    if ('BASE TABLE' !== $table[1]) {
                        $tabletype = ' <i>(' . strtolower(esc_html($table[1])) . ')</i>';
                    }
                    echo '<label for="idtabledb-' . esc_html($table[0]) . '""><input class="checkbox" type="checkbox"' . checked(!in_array($table[0], S3Testing_Option::get($jobid, 'dbdumpexclude'), true), true, false) . ' name="tabledb[]" id="idtabledb-' . esc_html($table[0]) . '" value="' . esc_html($table[0]) . '"/> ' . esc_html($table[0]) . $tabletype . '</label><br />';
                    ++$counter;
                    if ($next_row <= $counter) {
                        echo '</div><div style="width: 30%; float:left; min-width: 250px; margin-right: 10px;">';
                        $counter = 0;
                    }
                }
                echo '</div></fieldset>';
                ?>
            </td>
        </tr>
    </table>
        <?php
    }

    public function edit_form_post_save($id)
    {
        global $wpdb;

        if($_POST['dbdumpfilecompression'] === '' && $_POST['dbdumpfilecompression'] === '.gz') {
            S3Testing_Option::update($id, 'dbdumpfilecompression', $_POST['dbdumpfilecompression']);
        }

//        S3Testing_Option::update($id, 'dbdumpfile', S3Testing_Job::sanitize_file_name($_POST['dbdumpfile']));
        S3Testing_Option::update($id, 'dbdumpfile', S3Testing_Job::sanitize_file_name($this->option_defaults()['dbdumpfile']));

        $dbdumpexclude = [];
        $checked_db_tables = [];

        if (isset($_POST['tabledb'])) {
            foreach ($_POST['tabledb'] as $dbtable) {
                $checked_db_tables[] = sanitize_text_field($dbtable);
            }
        }

        $dbtables = $wpdb->get_results('SHOW TABLES FROM `' . DB_NAME . '`', ARRAY_N);

        foreach ($dbtables as $dbtable) {
            if (!in_array($dbtable[0], $checked_db_tables, true)) {
                $dbdumpexclude[] = $dbtable[0];
            }
        }

        S3Testing_Option::update($id, 'dbdumpexclude', $dbdumpexclude);
    }

    public function job_run(S3Testing_Job $job_object)
    {
        //build filename
        if ( empty( $job_object->steps_data[ $job_object->step_working ]['dbdumpfile'] ) ) {
            $job_object->steps_data[ $job_object->step_working ]['dbdumpfile'] = $job_object->generate_db_dump_filename( $job_object->job['dbdumpfile'], 'sql' ) . $job_object->job['dbdumpfilecompression'];
        }

        try {
            //Connect to database
            $sql_dump = new S3Testing_MySQLDump([
                'dumpfile' => S3Testing::get_plugin_data('TEMP'),
                $job_object->steps_data[$job_object->step_working]['dbdumpfile'],
            ]);

            //Exclude tables
            foreach ($sql_dump->tables_to_dump as $key => $table) {
                if (in_array($table, $job_object->job['dbdumpexclude'], true)) {
                    unset($sql_dump->tables_to_dump[$key]);
                }
            }

            if(count($sql_dump->tables_to_dump) == 0) {
                throw new S3Testing_MySQLDump_Exception(__('No tables to backup'));
                unset($sql_dump);
                return true;
            }

            //dump head
            if (!isset($job_object->steps_data[$job_object->step_working]['is_head'])) {
                $sql_dump->dump_head(true);
                $job_object->steps_data[$job_object->step_working]['is_head'] = true;
            }
            //dump tables
            $i = 0;
            foreach ($sql_dump->tables_to_dump as $table) {
                if ($i < count($sql_dump->tables_to_dump)) {
                    ++$i;

                    continue;
                }

                if (empty($job_object->steps_data[$job_object->step_working]['tables'][$table])) {
                    $num_records = $sql_dump->dump_table_head($table);
                    $job_object->steps_data[$job_object->step_working]['tables'][$table] = ['start' => 0,
                        'length' => 1000, ];
                    if ($job_object->is_debug()) {
                        $job_object->log(sprintf(__('Backup database table "%s" with "%s" records', 'backwpup'), $table, $num_records));
                    }
                }
            }

        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    public function admin_print_scripts()
    {
        if (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) {
            wp_enqueue_script('s3testingjobtypedbdump', S3Testing::get_plugin_data('URL') . '/assets/js/page_edit_jobtype_dbdump.js', ['jquery'], time(), true);
        } else {
            wp_enqueue_script('s3testingjobtypedbdump', S3Testing::get_plugin_data('URL') . '/assets/js/page_edit_jobtype_dbdump.min.js', ['jquery'], S3Testing::get_plugin_data('Version'), true);
        }
    }

}