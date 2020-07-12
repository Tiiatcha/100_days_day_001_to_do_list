<?php

class alerts{
    public static function alert($type) {
                $html = '<div class="alert alert-'.$type.' alert-dismissible fade show" role="alert">
                        <strong class="ca_alert_title" id="AJAX_title_text"></strong ><span class="ca_alert_text" id="AJAX_message_text"></span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                        </div>';
        return $html;
	}
}