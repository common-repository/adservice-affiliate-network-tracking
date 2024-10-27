<?php
    function astrk_json_remote_get($url, $auth) {
        return astrk_json_remote_request('GET', $url, $auth, null);
    }

    function astrk_json_remote_post($url, $auth, $body) {
        return astrk_json_remote_request('POST', $url, $auth, $body);
    }

    function astrk_json_remote_delete($url, $auth, $body) {
        return astrk_json_remote_request('DELETE', $url, $auth, $body);
    }

    function astrk_json_remote_request($method, $url, $auth, $body) {
        $headers = array();

        if ($method !== 'GET') {
            $headers['Content-Type'] = 'application/json';
        }

        if ($auth && isset($auth['uid']) && isset($auth['token'])) {
            $headers['Authorization'] = 'Basic ' . base64_encode($auth['uid'] . ':' . $auth['token']);
        }

        if ($body) {
            $body = wp_json_encode($body);
        }

        $result = wp_remote_request( $url, array(
                'body'          => $body,
                'data_format'   => 'body',
                'headers'       => $headers,
                'method'        => $method
            )
        );

        return json_decode($result['body']);
    }

    function escapeJavaScriptText($string)
{
    return str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$string), "\0..\37'\\")));
}

    
	function astrk_get_login_token($username, $password) {
        $auth = array(
            "username" => $username,
            "password" => $password
        );
        $r = astrk_json_remote_post("https://api.adservice.com/v2/client/account/logintoken", null, $auth);
        if(isset($r->data)) {
		    return array($r->data->uid, $r->data->login_token);
        }
	}

	function astrk_check_login($options) {
        $r = astrk_json_remote_get("https://api.adservice.com/v2/client/account/", $options);

        if (isset($r->success) && $r->success) {
            return $r;
        } else {
            return false;
        }
    }

    function astrk_get_campaigns($options) {
        $r = astrk_json_remote_get("https://api.adservice.com/v2/client/campaigns/?get_prices=1", $options);
        $campaigns = array();
        if (isset($r->success) && $r->success) {
            return $r;
        }
    }
?>
