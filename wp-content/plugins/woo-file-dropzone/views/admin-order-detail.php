<?php defined('ABSPATH') or die('No script kiddies please!'); ?>

<table>
    <?php
    if (count($records)) {

        foreach ($records as $record) {

            /**
             * There is variation id then check get product variation data
             */
            $product = new Wc_Product($record['product_id']);
            if($record['variation_id'] != '0'){
                $variation = new WC_Product_Variation( $record['variation_id'] );
                echo "<tr><td>".$product->get_title()."</td></tr>";
                echo "<tr><td>".$variation->get_formatted_variation_attributes(true)."</td></tr>";
            }else{
                echo "<tr><td>".$product->get_title()."</td></tr>";
            }

            echo "<tr><td>";
            for ($i = 0; $i < count($record['fields']); $i++) {
                echo "<span style='font-weight: bold'>" . $record['fields'][$i][0] . " : </span> " . $record['fields'][$i][1] . "<br/>";
            }
            echo "</tr></td>";
            echo "<tr><td style='border-bottom: 1px solid lightgrey;padding-top: 10px; padding-bottom: 10px;'>";
            for ($j = 0; $j < count($record['files']); $j++) {
                echo "<div class='row'>..." . substr($record['files'][$j], strlen($record['files'][$j]) - 20, 20);
                echo "  <a target='_blank' href='" . WOO_FILE_DROPZONE_UPLOAD_CONTENT_URL . $record['files'][$j] . "'>";
                echo "<img style='vertical-align: middle' src='" . WOO_FILE_DROPZONE_PLUGIN_URL . "/assets/download-icon.png' /></a>";
                echo "<span class='woo-dropzone-delete-file' data-file='" . $record['files'][$j] . "' data-id='" . $record['id'] . "' >";
                echo "<img style='vertical-align:middle; cursor: pointer' src='" . WOO_FILE_DROPZONE_PLUGIN_URL . "/assets/delete-icon.png'/></span>";
                echo "</div>";
            }
            echo "</td></tr>";

        }

    } else {
        ?>
        <tr>
            <td> No Files Were Uploaded</td>
        </tr>
        <?php
    }
    ?>
</table>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.woo-dropzone-delete-file').click(function () {
            var _confirm = confirm('Are you sure to delete this file ?');
            if (!_confirm)
                return true;

            var _fileName = $(this).data('file');
            var _ID = $(this).data('id');
            var $this = $(this);
            $this.html($('.locked-saving img').clone());
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'JSON',
                data: {
                    'action': 'wc_file_dropzone_ajax_actions',
                    'do_action': 'delete_file',
                    ID: _ID,
                    fileName: _fileName
                },
                success: function (resp) {
                    $this.parents('div.row').remove();
                }
            })
        });
    });
</script>

