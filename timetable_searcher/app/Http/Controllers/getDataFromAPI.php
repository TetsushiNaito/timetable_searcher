<?php

function getDataFromAPI( $handle, $url ) : array {
        curl_setopt( $handle, CURLOPT_URL, $url );
        curl_setopt( $handle, CURLOPT_RETURNTRANSFER, true );
        return json_decode( curl_exec( $handle ), FALSE );
}
