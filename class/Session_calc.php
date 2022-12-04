<?php

class Session
{
    /**
     * csrfトークンを発行する
     * @param null
     * @return string
     */
    public function create_csrf_token()
    {
        $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(24));
        return $_SESSION['csrf_token'];
    }

    /**
     * csrfトークンの存在確認と正誤判定
     * @param $csrf_token
     * @return bool
     */
    public function csrf_match_check($csrf_token)
    {

        if ($_SESSION['csrf_token'] !== $csrf_token) {
            return false;
        }

        return true;
    }

    /**
     * csrfセッション情報消去
     * @param null
     * @return null
     */
    public function csrf_token_unset()
    {
        unset($_SESSION['csrf_token']);
    }
}
