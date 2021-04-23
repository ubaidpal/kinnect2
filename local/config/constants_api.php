<?php
    /**
     * Created by   :  Muhammad Yasir
     * Project Name : local
     * Product Name : PhpStorm
     * Date         : 12-12-15 2:36 PM
     * File Name    : constants_api.php
     */
    return array(
        'ERROR_CODES'      => array(
            'INVALID_PARAM'    => 1,
            'RESULT_NOT_FOUND' => 2,
            'DETAIL_NOT_FOUND' => 3,
            'TOKEN_EXPIRED'    => 4,
            'ACCESS_DENIED'    => 5,
            'ALREADY_DONE'     => 6,
            'OTHER_ERROR'      => 7,
            'DELETED'          => 7,
        ),
        'ERROR_MESSAGES'   => array(
            'INVALID_PARAM'    => 'Invalid parameters!',
            'RESULT_NOT_FOUND' => 'Result not found!',
            'DETAIL_NOT_FOUND' => 'Detail not found!',
            'code_4'           => 'Access Token Expired!',
            'ACCESS_DENIED'    => 'Access Denied!',
            'TOKEN_EXPIRED'    => 'Access token is Expired!',
            'DELETED'          => 'Item is deleted!',

        ),
        'SUCCESS_MESSAGES' => array(
            'SUCCESS' => 'success',
        ),
    );
