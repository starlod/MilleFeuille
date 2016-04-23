<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Constants;
use AppBundle\Util;

class AppController extends Controller
{
    // アラートメッセージ
    const ALERT_INFO      = "info";         // 通常メッセージ
    const ALERT_SUCCESS   = "success";      // 成功メッセージ
    const ALERT_WARNING   = "warning";      // 警告メッセージ
    const ALERT_DANGER    = "danger";       // エラーメッセージ

    /**
     * 翻訳メッセージを取得する
     *
     * @param message メッセージ
     * @param placeholder メッセージプレースホルダー
     */
    public function getTrans($message = '', $placeholder = array())
    {
        return $this->get('translator')->trans($message, $placeholder);
    }

    /**
     * フラッシュメッセージを表示する
     *
     * @param message フラッシュメッセージに表示する内容
     * @param class Bootstrapのアラートクラス名（alert-***）
     * @param overwrite フラッシュメッセージを上書きするか
     */
    private function showMessage($message = '', $overwrite = false, $class = self::ALERT_INFO)
    {
        if ($overwrite) {
            $this->get('session')->getFlashBag()->set($class, $message);
        } else {
            $this->get('session')->getFlashBag()->add($class, $message);
        }
    }

    /**
     * Show "Info" Alert Message
     * Output "Info" Log Message
     * @param message フラッシュメッセージに表示する内容
     * @param placeholder メッセージプレースホルダー
     * @param overwrite フラッシュメッセージを上書きするか
     */
    protected function showInfoMessage($message = '', $placeholder = array(), $overwrite = false)
    {
        if ($message) {
            $message = $this->getTrans($message, $placeholder);
            $this->showMessage($message, $overwrite, self::ALERT_INFO);
            Util::setInfoLog($message);
        }
    }

    /**
     * Show "Success" Alert Message
     * Output "Info" Log Message
     * @param message フラッシュメッセージに表示する内容
     * @param placeholder メッセージプレースホルダー
     * @param overwrite フラッシュメッセージを上書きするか
     */
    protected function showSuccessMessage($message = '', $placeholder = array(), $overwrite = false)
    {
        if ($message) {
            $message = $this->getTrans($message, $placeholder);
            $this->showMessage($message, $overwrite, self::ALERT_SUCCESS);
            Util::setNoticeLog($message);
        }
    }

    /**
     * Show "Warning" Alert Message
     * Output "Warning" Log Message
     * @param message フラッシュメッセージに表示する内容
     * @param placeholder メッセージプレースホルダー
     * @param overwrite フラッシュメッセージを上書きするか
     */
    protected function showWarningMessage($message = '', $placeholder = array(), $overwrite = false)
    {
        if ($message) {
            $message = $this->getTrans($message, $placeholder);
            $this->showMessage($message, $overwrite, self::ALERT_WARNING);
            Util::setWarningLog($message);
        }
    }

    /**
     * Show "Danger" Alert Message
     * Output "Error" Log Message
     * @param message フラッシュメッセージに表示する内容
     * @param placeholder メッセージプレースホルダー
     * @param overwrite フラッシュメッセージを上書きするか
     */
    protected function showErrorMessage($message = '', $placeholder = array(), $overwrite = false)
    {
        if ($message) {
            $message = $this->getTrans($message, $placeholder);
            $this->showMessage($message, $overwrite, self::ALERT_DANGER);
            Util::setErrorLog($message);
        }
    }

    /**
     * フォームバリデーションエラーがあるか
     * @param form
     * @return boolean true:valid, false:invalid
     */
    protected function isValid($form)
    {
        // フォームバリデーションエラーを取得
        $errors = $this->get('validator')->validate($form);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $this->showWarningMessage($error->getMessage(), $error->getParameters());
            }

            return false;
        }
        return true;
    }

    /**
     * ページャー
     * @param Query $query
     * @param Int $page
     * @param Int $pageRange
     */
    protected function getPaginator($query, $page = 1, $pageRange = Constants::PAGE_RANGE)
    {
        return $this->get('knp_paginator')->paginate($query, $page, $pageRange);
    }
}
